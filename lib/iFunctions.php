<?php
    function encode_rfc3986($string)
    {
        if (is_array($string)) {
            return array_map('encode_rfc3986', $string);
        } elseif (is_scalar($string))
            return str_ireplace(
                array('+', '%7E'),
                array(' ', '~'),
                rawurlencode($string)
            );
    }

    function xmlstr_to_array($xmlstr)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xmlstr);
        $root = $doc->documentElement;
        $output = domnode_to_array($root);
        $output['@root'] = $root->tagName;
        return $output;
    }

    function domnode_to_array($node)
    {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = domnode_to_array($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;
                        if (!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    } elseif ($v || $v === '0') {
                        $output = (string)$v;
                    }
                }
                if ($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
                    $output = array('@content' => $output); //Change output into an array.
                }
                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $a = array();
                        foreach ($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string)$attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) == 1 && $t != '@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }

    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    function transliterateTurkishChars($inputText)
    {
        $search = array('ç', 'Ç', 'ğ', 'Ğ', 'ı', 'İ', 'ö', 'Ö', 'ş', 'Ş', 'ü', 'Ü');
        $replace = array('c', 'C', 'g', 'G', 'i', 'I', 'o', 'O', 's', 'S', 'u', 'U');
        $outputText = str_replace($search, $replace, $inputText);
        return $outputText;
    }

    function array_random($array)
    {
        return $array[array_rand($array)];
    }

    function random_str($length = 20)
    {
        $strs = array_merge(range("a", "z"), range("A", "Z"));
        $strs = array_merge($strs, range("0", "9"));
        $strs[] = "_";
        shuffle($strs);
        $strs = array_slice($strs, 0, $length);
        return implode("", $strs);
    }

    function random_string($length = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_"), 0, $length);
    }

    function normalizeUrl($url = NULL)
    {
        $urlParts = parse_url($url);
        $scheme = strtolower($urlParts['scheme']);
        $host = strtolower($urlParts['host']);
        $port = isset($urlParts['port']) ? intval($urlParts['port']) : 0;
        $retval = strtolower($scheme) . '://' . strtolower($host);
        if (!empty($port) && (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443)))
            $retval .= ":{$port}";
        $retval .= $urlParts['path'];
        if (!empty($urlParts['query'])) {
            $retval .= "?{$urlParts['query']}";
        }
        return $retval;
    }

    function fakeip()
    {
        return rand(1, 255) . "." . rand(1, 255) . "." . rand(1, 255) . "." . rand(1, 255);
        //return "85.103." . rand(1, 255) . "." . rand(1, 255);
        return long2ip(mt_rand(0, 65537) * mt_rand(0, 65535));
    }

    function buildHttpQueryRaw($params)
    {
        $retval = '';
        foreach ((array)$params as $key => $value)
            $retval .= "{$key}={$value}&";
        $retval = substr($retval, 0, -1);
        return $retval;
    }

    function random_number($length = 15)
    {
        $timestamp = time();
        $timestamp = str_split($timestamp);
        $numbers = array_merge(range("0", "9"), $timestamp);
        shuffle($numbers);
        $numbers = array_slice($numbers, 0, $length);
        return implode("", $numbers);
    }

    function array_random_mail()
    {
        $mails = array(
            "@mail.ru",
            "@yahoo.com",
            "@gmail.com",
            "@yandex.com",
            "@hotmail.com",
            "@outlook.com",
            "@windowslive.com",
        );
        return (random_string(20) . array_random($mails));
    }

    function exec_background($cmd)
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B " . $cmd, "r"));
        } else {
            shell_exec($cmd . " > /dev/null 2>/dev/null &");
        }
    }

    function gen_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535));
    }

    function build_query($data, $key = NULL)
    {
        $query = array();
        if (empty($data)) {
            return $key . '=';
        }
        $is_array_assoc = is_array_assoc($data);
        foreach ($data as $k => $value) {
            if (is_string($value) || is_numeric($value)) {
                $brackets = $is_array_assoc ? '[' . $k . ']' : '[]';
                $query[] = urlencode(is_null($key) ? $k : $key . $brackets) . '=' . encode_rfc3986($value);
            } else if (is_array($value)) {
                $nested = is_null($key) ? $k : $key . '[' . $k . ']';
                $query[] = build_query($value, $nested);
            }
        }
        $c = "";
        foreach ($query as $value) {
            $c .= $value . "&";
        }
        $c = rtrim($c, '&');
        return $c;
    }

    function is_array_assoc($array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

?>
