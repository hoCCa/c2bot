<?php
error_reporting(0);  
    require('iFunctions.php');

    class CurlResponse implements ArrayAccess, Countable, IteratorAggregate
    {
        private $response;
        private $__obj;

        function __construct($response)
        {
            foreach ($response as $k => $v) {
                $this->{$k} = $v;
            }
            if (isset($response->is_json) && $response->is_json) {
                $this->response = json_decode($response->content, TRUE, 512, JSON_BIGINT_AS_STRING);
                $this->__obj = json_decode($response->content, FALSE, 512, JSON_BIGINT_AS_STRING);
            }
        }

        public function getIterator()
        {
            if ($this->__obj) {
                return new ArrayIterator($this->__obj);
            } else {
                return new ArrayIterator($this->response);
            }
        }

        // Implementation of Countable::count() to support count($this)
        public function count()
        {
            return count($this->response);
        }

        // Next four functions are to support ArrayAccess interface
        // 1
        public function offsetSet($offset, $value)
        {
            $this->response[$offset] = $value;
        }

        // 2
        public function offsetExists($offset)
        {
            return isset($this->response[$offset]);
        }

        // 3
        public function offsetUnset($offset)
        {
            unset($this->response[$offset]);
        }

        // 4
        public function offsetGet($offset)
        {
            return isset($this->response[$offset]) ? $this->response[$offset] : NULL;
        }

        public function __get($name)
        {
            if (isset($this->__obj) && property_exists($this->__obj, $name)) {
                return $this->__obj->$name;
            }
            return NULL;
        }

        public function __isset($name)
        {
            $value = self::__get($name);
            return isset($value);
        }
    }

    function curl_header_func(&$headers)
    {
        return function ($ch, $header) use (&$headers) {
            $key = (string)$ch;
            $_header = trim($header);
            if (strpos($_header, ':') != FALSE) {
                list($name, $val) = explode(':', $_header, 2);
                $name = strtolower($name);
                $val = ltrim(rtrim($val));
                if (isset($headers[$key][$name])) {
                    $headers[$key][$name] .= PHP_EOL . $val;
                } else {
                    $headers[$key][$name] = $val;
                }
            }
            if (empty($_header)) {
                if (isset($headers[$key]["set-cookie"])) {
                    $_cookies = $headers[$key]["set-cookie"];
                    if (strpos($_cookies, PHP_EOL) != FALSE) $_cookies = explode(PHP_EOL, $_cookies);
                    else $_cookies = array($_cookies);
                    $_cookies = array_reverse($_cookies);
                    $cookiee = array();
                    $cookie = "";
                    foreach ($_cookies as $cook) {
                        $c = explode(";", $cook, 2);
                        if (isset($c[0])) {
                            list($name, $value) = explode("=", ltrim(rtrim($c[0])), 2);
                            if (isset($cookiee[$name])) continue;
                            $cookie .= "{$c[0]}; ";
                            $cookiee[$name] = $value;
                        }
                    }
                    $ret = curl_getinfo($ch, CURLINFO_HEADER_OUT);
                    list($_headers, $_content) = explode("\r\n\r\n", $ret, 2);
                    $_headers = explode("\n", str_replace("\r", "", $_headers));
                    foreach ($_headers as $h) {
                        if (strpos($h, ':') != FALSE) {
                            list($name, $value) = explode(":", $h, 2);
                            $name = strtolower($name);
                            $value = trim($value);
                            if ($name == "cookie") {
                                $_cookie = array_filter(explode(";", $value));
                                foreach ($_cookie as $_cook) {
                                    $_cook = rtrim(ltrim($_cook));
                                    if (!empty($_cook) && strpos($_cook, '=') != FALSE) {
                                        list($name, $value) = explode("=", $_cook, 2);
                                        if (!isset($cookiee[$name])) {
                                            $cookie .= "{$name}={$value}; ";
                                            $cookiee[$name] = $value;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    unset($_headers, $_content, $ret);
                    $cookie = trim($cookie);
                    $headers[$key]["http_cookie"] = $cookie;
                    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
                }
            }
            return strlen($header);
        };
    }

    /**
     * @param      $opt
     * @param null $ch
     * @param bool $is_close
     *
     * @return object|stdClass
     */
    function curl($opt, $ch = NULL, $is_close = TRUE)
    {
        $headers = array();
        $header_function = curl_header_func($headers);
        $ch = isset($ch) ? $ch : curl_init();
        curl_setopt_array($ch, curl_get_options($opt));
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, $header_function);
        $content = curl_exec($ch);
        $chan_key = (string)$ch;
        $headers = @$headers[$chan_key];
        $obj = new stdClass();
        if (is_json($content)) {
            $obj->is_json = TRUE;
        }
        $obj->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $obj->http_cookie = @$headers["http_cookie"];
        $obj->http_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        if (isset($opt["header_out"]) && $opt["header_out"]) {
            $ret = curl_getinfo($ch, CURLINFO_HEADER_OUT);
            list($_headers, $_content) = explode("\r\n\r\n", $ret, 2);
            $obj->http_header_out = explode("\n", str_replace("\r", "", $_headers));
            unset($_headers, $_content, $ret);
        }
        if (isset($opt["header"]) && $opt["header"]) {
            $obj->http_headers = $headers;
        }
        if ($is_close) {
            curl_close($ch);
        }
        $obj->content = $content;
        return new CurlResponse($obj);
    }

    function multi_curl($opts, $callback = NULL, $rolling_window = 5)
    {
        $__key = "%%%%&%%%%%%";
        $mh = curl_multi_init();
        $rolling_window = (sizeof($opts) < $rolling_window ? sizeof($opts) : $rolling_window);
        $result = array();
        $keys = array();
        $_keys = array();
        $i = 0;
        $chans = array();
        $headers = array();
        $running = NULL;
        $add = FALSE;
        $execStatus = NULL;
        $headers = array();
        $header_function = function ($ch, $header) use (&$headers) {
            $_header = trim($header);
            $colonPos = strpos($_header, ':');
            if ($colonPos > 0) {
                $key = (string)$ch;
                $name = strtolower(substr($_header, 0, $colonPos));
                $val = trim(substr($_header, $colonPos + 1));
                if (isset($headers[$key][$name])) {
                    if (is_scalar($headers[$key][$name])) {
                        $headers[$key][$name] = (array)array($headers[$key][$name], $val);
                    } elseif (is_array($headers[$key][$name])) {
                        $headers[$key][$name] = array_merge($headers[$key][$name], array($val));
                    }
                } else {
                    $headers[$key][$name] = $val;
                }
            }
            return strlen($header);
        };
        foreach ($opts as $key => $opt) {
            if (isset($opt["key"])) {
                unset($opts[$key]);
                $key = $opt["key"] . $__key . md5(uniqid(rand(), TRUE) . microtime(TRUE));
                $opts[$key] = $opt;
            }
            if ($i < $rolling_window) {
                $_opt = curl_get_options($opt);
                $_opt[CURLOPT_HEADERFUNCTION] = $header_function;
                $_opt[CURLOPT_FOLLOWLOCATION] = FALSE;
                $chan = curl_init();
                $chan_key = (string)$chan;
                curl_setopt_array($chan, $_opt);
                $code = curl_multi_add_handle($mh, $chan);
                $keys[$chan_key] = $key;
                $chans[$key] = &$chan;
                if ($code == CURLM_OK || $code == CURLM_CALL_MULTI_PERFORM) {
                    do {
                        $execStatus = curl_multi_exec($mh, $running);
                    } while ($execStatus === CURLM_CALL_MULTI_PERFORM);
                }
            } else {
                $_keys[] = $key;
            }
            $i++;
        }
        unset($i);
        while (($running || $add) && ($execStatus == CURLM_OK || $execStatus == CURLM_CALL_MULTI_PERFORM)) {
            $ms = curl_multi_select($mh, 10);
            if ($ms >= CURLM_CALL_MULTI_PERFORM) {
                do {
                    $execStatus = curl_multi_exec($mh, $running);
                } while ($execStatus === CURLM_CALL_MULTI_PERFORM);
            }
            $add = FALSE;
            while ($done = curl_multi_info_read($mh, $remains)) {
                $chan = $done["handle"];
                $chan_key = (string)$chan;
                $key = $keys[$chan_key];
                $content = curl_multi_getcontent($chan);
                $code = curl_getinfo($chan, CURLINFO_HTTP_CODE);
                $url = curl_getinfo($chan, CURLINFO_EFFECTIVE_URL);
                $header = $headers[$chan_key];
                $opt = &$opts[$key];
                $obj = new stdClass();
                if (isset($header["content-type"]) && strstr($header["content-type"], 'application/json')) {
                    $obj->is_json = TRUE;
                }
                if (isset($header["set-cookie"])) {
                    $_cookie = $header["set-cookie"];
                    if (is_scalar($_cookie)) {
                        $_cookie = array($_cookie);
                    }
                    $cookies = "";
                    $_cookies = array();
                    foreach ($_cookie as $c) {
                        $c = explode(";", $c, 2);
                        if (isset($c[0])) {
                            list($name, $value) = explode("=", ltrim(rtrim($c[0])), 2);
                            $cookies .= "{$c[0]}; ";
                            $_cookies[$name] = $value;
                        }
                    }
                    if (isset($opt["__cookies__"])) {
                        foreach ($opt["__cookies__"] as $name => $value) {
                            if (!isset($_cookies[$name])) {
                                $cookies .= "{$name}={$value}; ";
                                $_cookies[$name] = $value;
                            }
                        }
                    }
                    if (isset($opt["cookie"])) {
                        $_cookie = array_filter(explode(";", $opt["cookie"]));
                        foreach ($_cookie as $_cook) {
                            $_cook = rtrim(ltrim($_cook));
                            if (!empty($_cook)) {
                                list($name, $value) = explode("=", $_cook, 2);
                                if (!isset($_cookies[$name]) && isset($name) && isset($value)) {
                                    $cookies .= "{$name}={$value}; ";
                                }
                            }
                        }
                    }
                    $cookies = $cookies;
                    $obj->http_cookie = $cookies;
                }
                $obj->http_code = curl_getinfo($chan, CURLINFO_HTTP_CODE);
                if (($obj->http_code == 302 || $obj->http_code == 301)) {
                    if (isset($cookies)) {
                        $opt["__cookies__"] = $_cookies;
                        $opt["cookie"] = $cookies;
                    }
                    if (isset($headers["location"])) {
                        $path = parse_url($header["location"]);
                        if (isset($path["host"]) && isset($path['scheme'])) {
                            $opt["url"] = $header["location"];
                        } else {
                            $path = parse_url(curl_getinfo($chan, CURLINFO_EFFECTIVE_URL));
                            $opt["url"] = "{$path['scheme']}://{$path['host']}{$headers['location']}";
                        }
                        $opt["post"] = NULL;
                        curl_multi_remove_handle($mh, $chan);
                        $opt = curl_get_options($opt);
                        $opt[CURLOPT_HEADERFUNCTION] = $header_function;
                        $chan_key = (string)$chan;
                        curl_setopt_array($chan, $opt);
                        $code = curl_multi_add_handle($mh, $chan);
                        $keys[$chan_key] = $_key;
                        $chans[$_key] = &$chan;
                        if ($code == CURLM_OK || $code == CURLM_CALL_MULTI_PERFORM) {
                            do {
                                $execStatus = curl_multi_exec($mh, $running);
                            } while ($execStatus === CURLM_CALL_MULTI_PERFORM);
                        }
                        $add = TRUE;
                        continue;
                    }
                }
                $obj->http_url = $url;
                if (isset($opt["header_out"]) && $opt["header_out"]) {
                    $ret = curl_getinfo($chan, CURLINFO_HEADER_OUT);
                    list($_headers, $_content) = explode("\r\n\r\n", $ret, 2);
                    $obj->http_header_out = explode("\n", str_replace("\r", "", $_headers));
                    unset($_headers, $_content, $ret);
                }
                if (isset($opt["header"]) && $opt["header"]) {
                    $obj->http_headers = $header;
                }
                $obj->content = $content;
                $obj = new CurlResponse($obj);
                curl_multi_remove_handle($mh, $chan);
                curl_close($chan);
                unset($opts[$key], $chans[$key], $content, $headers[$chan_key], $keys[$chan_key]);

                if (isset($callback)) {
                    if (is_callable($callback)) {
                        if (stristr($key, $__key)) {
                            list($key,) = explode($__key, $key);
                        }
                        $pCount = (new ReflectionFunction($callback))->getNumberOfParameters();
                        switch ($pCount) {
                            case 0:
                                $r = call_user_func($callback);
                                break;
                            case 1:
                                $r = call_user_func($callback, $obj);
                                break;
                            case 2:
                                $r = call_user_func($callback, $obj, $key);
                                break;
                            case 3:
                                $r = call_user_func($callback, $obj, $key, $opt);
                                break;
                        }
                        if (isset($r)) {
                            if (isset($r["close_key"])) {
                                $_key = $r["close_key"];
                                $_chans = preg_grep("#^$_key$#siU", array_keys($chans));
                                $__keys = preg_grep("#^$_key$#siU", $_keys);
                                array_walk($_chans, function ($v, $k) use (&$chans, &$mh) {
                                    if (array_key_exists($v, $chans)) {
                                        $chan = &$chans[$v];
                                        @curl_multi_remove_handle($mh, $chan);
                                        @curl_close($chan);
                                    }
                                });
                                array_walk($__keys, function ($v, $k) use (&$_keys) {
                                    if (array_key_exists($k, $_keys)) {
                                        unset($_keys[$k]);
                                    }
                                });
                                unset($__keys, $_chans);
                            } elseif (isset($r["close_keys"])) {
                                $_key = $r["close_keys"];
                                $_chans = preg_grep("#(^{$_key}{$__key}|^$_key$)#siU", array_keys($chans));
                                $__keys = preg_grep("#(^{$_key}{$__key}|^$_key$)#siU", $_keys);
                                array_walk($_chans, function ($v, $k) use (&$chans, &$mh) {
                                    if (array_key_exists($v, $chans)) {
                                        $chan = &$chans[$v];
                                        @curl_multi_remove_handle($mh, $chan);
                                        @curl_close($chan);
                                    }
                                });
                                array_walk($__keys, function ($v, $k) use (&$_keys) {
                                    if (array_key_exists($k, $_keys)) {
                                        unset($_keys[$k]);
                                    }
                                });
                                unset($__keys, $_chans);
                            } elseif (is_array($r) && isset($r["url"])) {
                                $_key = NULL;
                                if (isset($r["key"])) {
                                    $_key = $r["key"];
                                    $opts[$_key] = $r;
                                } else {
                                    $opts[] = $r;
                                    end($opts);
                                    $_key = key($opts);
                                    reset($opts);
                                }
                                if (isset($_r["add_end"]) && $_r["add_end"]) {
                                    $_keys[] = $_key;
                                } else {
                                    array_splice($_keys, 0, 0, $_key);
                                }
                            } elseif (is_array($r) && count($r) > 0) {
                                foreach ($r as $_r) {
                                    if (isset($_r["close_key"])) {
                                        $_key = $_r["close_key"];
                                        $_chans = preg_grep("#^$_key$#siU", array_keys($chans));
                                        $__keys = preg_grep("#^$_key$#siU", $_keys);
                                        array_walk($_chans, function ($v, $k) use (&$chans, &$mh) {
                                            if (array_key_exists($v, $chans)) {
                                                $chan = &$chans[$v];
                                                @curl_multi_remove_handle($mh, $chan);
                                                @curl_close($chan);
                                            }
                                        });
                                        array_walk($__keys, function ($v, $k) use (&$_keys) {
                                            if (array_key_exists($k, $_keys)) {
                                                unset($_keys[$k]);
                                            }
                                        });
                                        unset($__keys, $_chans);
                                    } elseif (isset($_r["close_keys"])) {
                                        $_key = $_r["close_keys"];
                                        $_chans = preg_grep("#(^{$_key}{$__key}|^$_key$)#siU", array_keys($chans));
                                        $__keys = preg_grep("#(^{$_key}{$__key}|^$_key$)#siU", $_keys);
                                        array_walk($_chans, function ($v, $k) use (&$chans, &$mh) {
                                            if (array_key_exists($v, $chans)) {
                                                $chan = &$chans[$v];
                                                @curl_multi_remove_handle($mh, $chan);
                                                @curl_close($chan);
                                            }
                                        });
                                        array_walk($__keys, function ($v, $k) use (&$_keys) {
                                            if (array_key_exists($k, $_keys)) {
                                                unset($_keys[$k]);
                                            }
                                        });
                                        unset($__keys, $_chans);
                                    }
                                    if (is_array($_r) && isset($_r["url"])) {
                                        $_key = NULL;
                                        if (isset($_r["key"])) {
                                            $_key = $_r["key"] . $__key . md5(uniqid(rand(), TRUE) . microtime(TRUE));
                                            $opts[$_key] = $_r;
                                        } else {
                                            $opts[] = $_r;
                                            end($opts);
                                            $_key = key($opts);
                                            reset($opts);
                                        }
                                        if (isset($_r["add_end"]) && $_r["add_end"]) {
                                            $_keys[] = $_key;
                                        } else {
                                            array_splice($_keys, 0, 0, $_key);
                                        }
                                    }
                                }
                            } elseif (!is_array($r) && isset($r) && $r == FALSE) {
                                foreach ($chans as $key => $chan) {
                                    @curl_multi_remove_handle($mh, $chan);
                                    @curl_close($chan);
                                }
                                curl_multi_close($mh);
                                unset($keys, $_keys, $opts, $headers, $mh, $chans);
                                return;
                            }
                        }
                    } else {
                        $result[$key] = $d;
                    }
                } elseif ($callback != "NOFUNCTION") {
                    $result[$key] = $d;
                }
                $_key = array_shift($_keys);
                if (isset($_key)) {
                    $opt = $opts[$_key];
                    $opt = curl_get_options($opt);
                    $opt[CURLOPT_HEADERFUNCTION] = $header_function;
                    $chan = curl_init();
                    $chan_key = (string)$chan;
                    curl_setopt_array($chan, $opt);
                    $code = curl_multi_add_handle($mh, $chan);
                    $keys[$chan_key] = $_key;
                    $chans[$_key] = &$chan;
                    if ($code == CURLM_OK || $code == CURLM_CALL_MULTI_PERFORM) {
                        do {
                            $execStatus = curl_multi_exec($mh, $running);
                        } while ($execStatus === CURLM_CALL_MULTI_PERFORM);
                    }
                    $add = TRUE;
                }
            }
        }
        curl_multi_close($mh);
        return $result;
    }

    function generateUUID($type)
    {
        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );

        return $type ? $uuid : str_replace('-', '', $uuid);
    }

    function curl_get_options($opt)
    {
        $url = $opt["url"];
        if (isset($opt["querystring"])) {
            $querystring = $opt["querystring"];
            if (is_array($querystring)) {
                $querystring = urldecode(build_query($querystring));
            }
            $querystring = (substr($querystring, 0, 1) != "?" ? "?" : "") . $querystring;
            $url = $url . "{$querystring}";
        }
        $timeout = isset($opt["timeout"]) ? $opt["timeout"] : 30;
        $follow_location = isset($opt["follow_location"]) ? (bool)$opt["follow_location"] : TRUE;
        if (isset($opt["ip"])) {
            $fake_ip = $opt["ip"];
        } else {
            $fake_ip = fakeip();
        }
        $http_header = array(
            "Accept: */*",
            "Accept-Language: en;q=1",
            "HTTP_CLIENT_IP:" . $fake_ip,
            "HTTP_FORWARDED:" . $fake_ip,
            "HTTP_PRAGMA:" . $fake_ip,
            "HTTP_XONNECTION:" . $fake_ip,
            "HTTP_CACHE_INFO:" . $fake_ip,
            "HTTP_XPROXY:" . $fake_ip,
            "HTTP_PROXY_CONNECTION:" . $fake_ip,
            "HTTP_VIA:" . $fake_ip,
            "HTTP_X_COMING_FROM:" . $fake_ip,
            "HTTP_COMING_FROM:" . $fake_ip,
            "HTTP_X_FORWARDED_FOR:" . $fake_ip,
            "HTTP_X_FORWARDED:" . $fake_ip,
            "ZHTTP_CACHE_CONTROL:" . $fake_ip,
            "REMOTE_ADDR: " . $fake_ip,
            "REMOTE_ADDR" => $fake_ip,
            "X-Client-IP: " . $fake_ip,
            "Client-IP: " . $fake_ip,
            "HTTP_X_FORWARDED_FOR: " . $fake_ip,
            "X-Forwarded-For: " . $fake_ip,
            "Connection: Keep-Alive",
            "Keep-Alive: 300",
            "Cache-Control: max-age=0",
        );
        if (isset($opt["http_header"]) && is_array($opt["http_header"])) {
            $http_header = array_merge($http_header, $opt["http_header"]);
        }
        $user_agents = array(
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36",
        );
        $user_agent = (isset($opt["user_agent"]) ? $opt["user_agent"] : $user_agents[array_rand($user_agents)]);
        if (isset($opt["type"]) && ($opt["type"] == "instagram" || $opt["type"] == "instagram_api")) {
            $user_agent = array(
                '(19/4.4.2; 213dpi; 800x1280; samsung; SM-T235Y; degaslte; universal3470; en_GB)',
                '(16/4.1.2; 240dpi; 540x888; motorola; XT881; cdma_yangtze; mapphone_cdma; en_US)',
                '(10/2.3.4; 240dpi; 540x960; HTC/htc_asia_wwe; HTC Ruby; ruby; ruby; en_PK)',
                '(19/4.4.2; 480dpi; 1080x1920; samsung/Verizon; SCH-I545; jfltevzw; qcom; en_US)',
                '(19/4.4.2; 240dpi; 540x960; QMobile; QMobile i10; QMobile i10; mt6582; en_US)',
                '(19/4.4; 160dpi; 360x640; chromium; App Runtime for Chrome Dev; nacl_i686; arc; en_US)',
                '(17/4.2.2; 240dpi; 480x800; QMobile; A8i; QMobile; mt6572; en_US)',
                '(19/4.4.4; 320dpi; 720x1280; samsung; SAMSUNG-SM-G850A; slteatt; qcom; en_US)',
                '(19/4.4.2; 240dpi; 480x854; QMobile; X400; QMobile; mt6582; en_US)',
                '(17/4.2.2; 240dpi; 480x854; QMobile; i6; QMobile; mt6582; en_US)',
                '(16/4.1.2; 320dpi; 720x1280; QMobile; A600; s9201b; mt6589; en_US)',
                '(19/4.4.2; 240dpi; 480x854; alps/QMobile; QMobile i5i; QMobile i5i; mt6582; en_US)',
                '(18/4.3; 480dpi; 1080x1920; samsung; SM-N900T; hltetmo; qcom; en_US)',
                '(21/5.0; 480dpi; 1080x1920; samsung; SM-G900FD; klte; qcom; en_GB)',
                '(19/4.4.2; 240dpi; 480x854; HUAWEI; HUAWEI Y520-U22; HWY520-U; mt6572; en_US)',
                '(19/4.4.2; 213dpi; 800x1280; samsung; SM-T230NU; degaswifiue; pxa1088; en_US)',
                '(17/4.2.2; 160dpi; 600x976; QTab Q300/QTab; QTab Q300; reallytek82_tb_jb5; mt8312; en_US)',
                '(19/4.4.2; 160dpi; 800x1232; QTab; QTab V8; QTab V8; mt8382; en_US)',
                '(19/4.4.2; 240dpi; 480x854; QMobile; QMobile X300; J505_QMobile; mt6582; en_US)',
                '(18/4.3; 320dpi; 720x1280; OPPO; N5111; N1mini; qcom; en_US)',
                '(17/4.2.2; 160dpi; 600x976; alps/QTabQ100; QTab Q100; QTab Q100; mt8312; en_US)',
                '(19/4.4.2; 213dpi; 800x1280; samsung; SM-T230NU; degaswifiue; pxa1088; en_GB)',
                '(19/4.4.4; 320dpi; 720x1280; LENOVO/Lenovo; Lenovo P70-A; P70-A; mt6752; en_US)',
                '(15/4.0.4; 240dpi; 480x854; alps/Cynus; Cynus T2; s9081; mt6577; en_US)',
                '(17/4.2.2; 240dpi; 540x960; HUAWEI/Huawei; HUAWEI G730-U10; hwG730-U10; mt6582; en_US)',
                '(19/4.4.4; 640dpi; 1440x2560; samsung; SM-N910H; tre3g; universal5433; en_GB)',
                '(19/4.4.2; 240dpi; 480x854; HUAWEI; HUAWEI Y520-U22; HWY520-U; mt6572; en_US)',
                '(16/4.1.2; 320dpi; 720x1184; PANTECH/SKY; IM-A830L; ef46l; qcom; en_US)',
                '(15/4.0.4; 240dpi; 480x800; QMobile; A11Note; unknown; mt6577; en_US)',
                '(19/4.4.4; 640dpi; 1440x2560; samsung; SM-N910H; tre3g; universal5433; en_US)',
                '(19/4.4.4; 320dpi; 720x1280; samsung; GT-I9305; m3; smdk4x12; en_GB)',
                '(19/4.4.4; 320dpi; 720x1280; motorola; XT1080; obake-maxx; qcom; en_US)');
            $user_agent = $user_agent[array_rand($user_agent)];
            if ($opt["type"] == "instagram_api") {
                //$user_agent = "Instagram 7.5.0 Android $user_agent";

                $user_agent = "Instagram 9.4.0 Android (18/4.3; 320dpi; 720x1280; Samsung; GT-S6802B; GT-S6802B; qcom; en_US)";
                //$user_agent = "Instagram 7.5.0 Android (21/5.0.2; 640dpi; 1440x2560; samsung; SM-G920F; zerofltebmc; samsungexynos7420; en_US)";
                //$user_agent = "Instagram 6.21.2 Android (19/4.4.2; 480dpi; 1152x1920; Meizu; MX4; mx4; mt6595; en_US)";
            } else {
                $user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.69 Safari/537.36";
            }
            $http_header = array_merge($http_header, array(
                "x-instagram-ajax: 1",
                "x-requested-with: XMLHttpRequest",
            ));
        }

        if (isset($opt["type"]) && isset($opt['csrf_token']) && $opt["type"] == "instagram") {
            $http_header = array_merge($http_header, array(
                'x-csrftoken: ' . $opt['csrf_token'],
            ));
        }
        if (isset($opt["get"])) {
            $url .= "?" . build_query($opt["get"]);
        }
        $_opt = array(
            CURLOPT_URL            => $url,
            CURLOPT_USERAGENT      => $opt["user_agent"],
            CURLOPT_FOLLOWLOCATION => $follow_location,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_ENCODING       => "",
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_HTTPHEADER     => $http_header,
            CURLOPT_COOKIESESSION  => FALSE,
            CURLINFO_HEADER_OUT    => TRUE,
        );
        if (isset($opt["referer"])) {
            $_opt[CURLOPT_REFERER] = $opt["referer"];
        }
        if (isset($opt["cookie"])) {
            $_opt[CURLOPT_COOKIE] = $opt["cookie"];
        }
        if (isset($opt["encoding"])) {
            $_opt[CURLOPT_ENCODING] = $opt["encoding"];
        }
        if (isset($opt["verbose"])) {
            $_opt[CURLOPT_VERBOSE] = TRUE;
        }
        if (isset($opt["cookie_file"])) {
            $_opt[CURLOPT_COOKIEFILE] = $opt["cookie_file"];
        }
        if (isset($opt["cookie_jar"])) {
            $_opt[CURLOPT_COOKIEJAR] = $opt["cookie_jar"];
        }
        if (isset($opt["cookie_session"])) {
            $_opt[CURLOPT_COOKIESESSION] = $opt["cookie_session"];
        }
        if (isset($opt["nobody"])) {
            $_opt[CURLOPT_NOBODY] = $opt["nobody"];
        }
        if (isset($opt["file"])) {
            $_opt[CURLOPT_FILE] = $opt["file"];
        }
        if (isset($opt["binary_transfer"])) {
            $_opt[CURLOPT_BINARYTRANSFER] = $opt["binary_transfer"];
        }
        if (isset($opt["header_out"])) {
            $_opt[CURLINFO_HEADER_OUT] = $opt["header_out"];
        }
        if (isset($opt["proxy"])) {
            $_opt[CURLOPT_PROXY] = $opt["proxy"];
        }
        if (isset($opt["proxy_ip"]) && isset($opt["proxy_port"])) {
            $_opt[CURLOPT_PROXY] = $opt["proxy_ip"];
            $_opt[CURLOPT_PROXYPORT] = $opt["proxy_port"];
        }
        if (isset($opt["proxy_type"])) {
            $_opt[CURLOPT_PROXYTYPE] = $opt["proxy_type"];
        }
        if (isset($opt["interface"])) {
            $_opt[CURLOPT_INTERFACE] = $opt["interface"];
        }
        if (isset($opt["custom_request"])) {
            $_opt[CURLOPT_CUSTOMREQUEST] = $opt["custom_request"];
        }

        if (isset($opt["post"])) {
            $post = $opt["post"];
            if (isset($opt["type"]) && $opt["type"] == "instagram_api") {
                $guid = gen_uuid();
                if (isset($opt["guid"])) {
                    $guid = $opt["guid"];
                }
                $device_id = 'android-'.substr(md5(md5($opt["post"]['username'] . $opt["post"]['password']) . filemtime(__DIR__)), 16);
                $p = array(
                    "phone_id" => generateUUID(true),
                    "device_id" => $device_id,
                    "guid"      => $guid,
                    "_uuid"     => $device_id,
                    'login_attempt_count' => '0',
                    "Content-Type" => "application/x-www-form-urlencoded; charset=UTF-8",
                );
                if (isset($opt["no_guid"]) && $opt["no_guid"]) {
                    unset($p["device_id"], $p["guid"], $p["_uuid"]);
                }
                $post = array_merge($post, $p);
                $data = json_encode($post);
                //Key 7.5 : 6618606de1acf0d804b47831d5074f3f302478330565df90aac37264dc632147
                $sig = hash_hmac('sha256', $data, '3465a939e8f82f05c4f73187cb37fef6ee25fcf4ed6fc3eaca1e14da5b351d63');
                /* if (isset($opt["is_multipart"]) && $opt["is_multipart"]) {
                 } else {
                     $post = 'signed_body=' . $sig . '.' . urlencode($data) . '&ig_sig_key_version=4';
                 }*/
                /*$post = 'signed_body=' . $sig . '.' . urlencode($data) . '&ig_sig_key_version=4';*/
                $post = array(
                    "signed_body"        => $sig . '.' . $data,
                    "ig_sig_key_version" => "5",
                );
                if (isset($opt["_post"])) {
                    $post = array_merge($post, $opt["_post"]);
                }
            } elseif (isset($opt["type"]) && $opt["type"] == "instagram" && is_array($post)) {
                $post = http_build_query($post, '', '&');
            }
            $_opt[CURLOPT_HTTPGET] = FALSE;
            $_opt[CURLOPT_PUT] = FALSE;
            $_opt[CURLOPT_POST] = TRUE;
            $_opt[CURLOPT_POSTFIELDS] = 'ig_sig_key_version=4&signed_body=' . $sig . '.' . urlencode($data);
            $_opt[CURLOPT_HTTPHEADER] = array_merge($http_header, array("Expect: "));
        } else {
            if (isset($opt["_post"])) {
                $_opt[CURLOPT_HTTPGET] = FALSE;
                $_opt[CURLOPT_PUT] = FALSE;
                $_opt[CURLOPT_POST] = TRUE;
                $_opt[CURLOPT_POSTFIELDS] = $opt["_post"];
                $_opt[CURLOPT_HTTPHEADER] = array_merge($http_header, array("Expect: "));
            } else {
                $_opt[CURLOPT_POST] = 0;
                $_opt[CURLOPT_POSTFIELDS] = NULL;
                $_opt[CURLOPT_HTTPGET] = TRUE;
            }
        }
        if (isset($opt["put"])) {
            $put = $opt["put"];
            $_opt[CURLOPT_CUSTOMREQUEST] = "PUT";
            $_opt[CURLOPT_POSTFIELDS] = $put;
            $_opt[CURLOPT_HTTPHEADER] = array_merge($http_header, array("Expect: "));
        }
        if (isset($opt["delete"])) {
            $put = $opt["delete"];
            $_opt[CURLOPT_POSTFIELDS] = $put;
            $_opt[CURLOPT_CUSTOMREQUEST] = "DELETE";
            $_opt[CURLOPT_HTTPHEADER] = array_merge($http_header, array("Expect: "));
        }
        return $_opt;
    }

?>
