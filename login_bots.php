<?php
set_time_limit(0);
error_reporting(E_ALL);
include "lib/c2bot.php";
include "lib/tempmail.class.php";

$lines = read_file('cookies/sifre.txt');
foreach ($lines as $line){
$parcala = explode(':',$line);
$username = $parcala[0];
$username = preg_replace('/\s+/', '', $username);
$password = $parcala[1];
$password = preg_replace('/\s+/', '', $password);
$post_data = array(
"register" => "false",
"nick" => $username,
"pass" => $password,
"rememberMe" =>"true"
);
$post_data_string = http_build_query($post_data);
$fake_ip = randFakeIP();
$userAgent = randUserAgent();
		$online_params = [
		'url' => "https://connected2.me/api/sign-in",
		'options' => [
		CURLOPT_POST => TRUE,
		CURLOPT_POSTFIELDS => $post_data_string,
		CURLOPT_REFERER => "https://connected2.me",
		CURLOPT_HTTPHEADER => [
                    'Accept: */*',
					'Accept-Encoding: gzip, deflate, br',
                    'Accept-Language: en-GB,en;q=0.8,tr;q=0.6',
                    'Connection: keep-alive',
					'Content-Length: '.strlen($post_data_string),
					'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Origin: https://connected2.me',
                    'Referer: https://connected2.me',
                    'User-Agent: ' . $userAgent,
                    'X-Requested-With: XMLHttpRequest',
                'X_FORWARDED_FOR: ' . $fake_ip,
                'REMOTE_ADDR: ' . $fake_ip,
                'HTTP_CLIENT_IP:' . $fake_ip,
                'HTTP_FORWARDED:' . $fake_ip,
                'HTTP_PRAGMA:' . $fake_ip,
                'HTTP_XONNECTION:' . $fake_ip,
                'HTTP_CACHE_INFO:' . $fake_ip,
                'HTTP_XPROXY:' . $fake_ip,
                'HTTP_PROXY_CONNECTION:' . $fake_ip,
                'HTTP_VIA:' . $fake_ip,
                'HTTP_X_COMING_FROM:' . $fake_ip,
                'HTTP_COMING_FROM:' . $fake_ip,
                'HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'HTTP_X_FORWARDED:' . $fake_ip,
                'HTTP_CACHE_CONTROL:' . $fake_ip,
                'HTTP_CLIENT_IP:' . $fake_ip,
                'HTTP_FORWARDED:' . $fake_ip,
                'HTTP_PRAGMA:' . $fake_ip,
                'HTTP_XONNECTION:' . $fake_ip,
                'HTTP_CACHE_INFO:' . $fake_ip,
                'HTTP_XPROXY:' . $fake_ip,
                'HTTP_PROXY_CONNECTION:' . $fake_ip,
                'HTTP_VIA:' . $fake_ip,
                'HTTP_X_COMING_FROM:' . $fake_ip,
                'HTTP_COMING_FROM:' . $fake_ip,
                'HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'HTTP_X_FORWARDED:' . $fake_ip,
                'ZHTTP_CACHE_CONTROL:' . $fake_ip,
                'REMOTE_ADDR: ' . $fake_ip,
                'REMOTE_ADDR:' . $fake_ip,
                'X-Client-IP:' . $fake_ip,
                'Client-IP: ' . $fake_ip,
                'HTTP_X_FORWARDED_FOR: ' . $fake_ip,
                'X-Forwarded-For: ' . $fake_ip,
                'x-HTTP_PRAGMA:' . $fake_ip,
                'x-HTTP_XONNECTION:' . $fake_ip,
                'x-HTTP_CACHE_INFO:' . $fake_ip,
                'x-HTTP_XPROXY:' . $fake_ip,
                'x-HTTP_PROXY_CONNECTION:' . $fake_ip,
                'x-HTTP_VIA:' . $fake_ip,
                'x-HTTP_X_COMING_FROM:' . $fake_ip,
                'x-HTTP_COMING_FROM:' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'x-HTTP_X_FORWARDED:' . $fake_ip,
                'x-HTTP_CACHE_CONTROL:' . $fake_ip,
                'x-HTTP_CLIENT_IP:' . $fake_ip,
                'x-HTTP_FORWARDED:' . $fake_ip,
                'x-HTTP_PRAGMA:' . $fake_ip,
                'x-HTTP_XONNECTION:' . $fake_ip,
                'x-HTTP_CACHE_INFO:' . $fake_ip,
                'x-HTTP_XPROXY:' . $fake_ip,
                'x-HTTP_PROXY_CONNECTION:' . $fake_ip,
                'x-HTTP_VIA:' . $fake_ip,
                'x-HTTP_X_COMING_FROM:' . $fake_ip,
                'x-HTTP_COMING_FROM' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR' . $fake_ip,
                'x-HTTP_X_FORWARDED:' . $fake_ip,
                'x-ZHTTP_CACHE_CONTROL:' . $fake_ip,
                'x-REMOTE_ADDR: ' . $fake_ip,
                'x-REMOTE_ADDR' . $fake_ip,
                'X-Client-IP:' . $fake_ip,
                'x-Client-IP: ' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR: ' . $fake_ip,
                'X-Forwarded-For: ' . $fake_ip,
                ],
		]
		];
$online=cURLx($online_params);

		$online_params = [
		'url' => "https://connected2.me/",
		'options' => [
		CURLOPT_REFERER => "https://connected2.me",
		CURLOPT_HTTPHEADER => [
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
					'Accept-Encoding: gzip, deflate, sdch, br',
                    'Accept-Language: en-GB,en;q=0.8,tr;q=0.6',
                    'Connection: keep-alive',
					'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Origin: https://connected2.me',
                    'Referer: https://connected2.me',
                    'User-Agent: ' . $userAgent,
					'Upgrade-Insecure-Requests: 1',
                    'X-Requested-With: XMLHttpRequest',
                'X_FORWARDED_FOR: ' . $fake_ip,
                'REMOTE_ADDR: ' . $fake_ip,
                'HTTP_CLIENT_IP:' . $fake_ip,
                'HTTP_FORWARDED:' . $fake_ip,
                'HTTP_PRAGMA:' . $fake_ip,
                'HTTP_XONNECTION:' . $fake_ip,
                'HTTP_CACHE_INFO:' . $fake_ip,
                'HTTP_XPROXY:' . $fake_ip,
                'HTTP_PROXY_CONNECTION:' . $fake_ip,
                'HTTP_VIA:' . $fake_ip,
                'HTTP_X_COMING_FROM:' . $fake_ip,
                'HTTP_COMING_FROM:' . $fake_ip,
                'HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'HTTP_X_FORWARDED:' . $fake_ip,
                'HTTP_CACHE_CONTROL:' . $fake_ip,
                'HTTP_CLIENT_IP:' . $fake_ip,
                'HTTP_FORWARDED:' . $fake_ip,
                'HTTP_PRAGMA:' . $fake_ip,
                'HTTP_XONNECTION:' . $fake_ip,
                'HTTP_CACHE_INFO:' . $fake_ip,
                'HTTP_XPROXY:' . $fake_ip,
                'HTTP_PROXY_CONNECTION:' . $fake_ip,
                'HTTP_VIA:' . $fake_ip,
                'HTTP_X_COMING_FROM:' . $fake_ip,
                'HTTP_COMING_FROM:' . $fake_ip,
                'HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'HTTP_X_FORWARDED:' . $fake_ip,
                'ZHTTP_CACHE_CONTROL:' . $fake_ip,
                'REMOTE_ADDR: ' . $fake_ip,
                'REMOTE_ADDR:' . $fake_ip,
                'X-Client-IP:' . $fake_ip,
                'Client-IP: ' . $fake_ip,
                'HTTP_X_FORWARDED_FOR: ' . $fake_ip,
                'X-Forwarded-For: ' . $fake_ip,
                'x-HTTP_PRAGMA:' . $fake_ip,
                'x-HTTP_XONNECTION:' . $fake_ip,
                'x-HTTP_CACHE_INFO:' . $fake_ip,
                'x-HTTP_XPROXY:' . $fake_ip,
                'x-HTTP_PROXY_CONNECTION:' . $fake_ip,
                'x-HTTP_VIA:' . $fake_ip,
                'x-HTTP_X_COMING_FROM:' . $fake_ip,
                'x-HTTP_COMING_FROM:' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'x-HTTP_X_FORWARDED:' . $fake_ip,
                'x-HTTP_CACHE_CONTROL:' . $fake_ip,
                'x-HTTP_CLIENT_IP:' . $fake_ip,
                'x-HTTP_FORWARDED:' . $fake_ip,
                'x-HTTP_PRAGMA:' . $fake_ip,
                'x-HTTP_XONNECTION:' . $fake_ip,
                'x-HTTP_CACHE_INFO:' . $fake_ip,
                'x-HTTP_XPROXY:' . $fake_ip,
                'x-HTTP_PROXY_CONNECTION:' . $fake_ip,
                'x-HTTP_VIA:' . $fake_ip,
                'x-HTTP_X_COMING_FROM:' . $fake_ip,
                'x-HTTP_COMING_FROM' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR' . $fake_ip,
                'x-HTTP_X_FORWARDED:' . $fake_ip,
                'x-ZHTTP_CACHE_CONTROL:' . $fake_ip,
                'x-REMOTE_ADDR: ' . $fake_ip,
                'x-REMOTE_ADDR' . $fake_ip,
                'X-Client-IP:' . $fake_ip,
                'x-Client-IP: ' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR: ' . $fake_ip,
                'X-Forwarded-For: ' . $fake_ip,
                ],
		]
		];
$online=cURLx($online_params);

		$online_params = [
		'url' => "https://api.connected2.me/b/mobile_info?nick={$username}&password={$password}&phoneType=w",
		'options' => [
		CURLOPT_REFERER => "https://connected2.me",
		CURLOPT_HTTPHEADER => [
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
					'Accept-Encoding: gzip, deflate, sdch, br',
                    'Accept-Language: en-GB,en;q=0.8,tr;q=0.6',
                    'Connection: keep-alive',
					'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Origin: https://connected2.me',
                    'Referer: https://connected2.me',
                    'User-Agent: ' . $userAgent,
					'Upgrade-Insecure-Requests: 1',
                    'X-Requested-With: XMLHttpRequest',
                'X_FORWARDED_FOR: ' . $fake_ip,
                'REMOTE_ADDR: ' . $fake_ip,
                'HTTP_CLIENT_IP:' . $fake_ip,
                'HTTP_FORWARDED:' . $fake_ip,
                'HTTP_PRAGMA:' . $fake_ip,
                'HTTP_XONNECTION:' . $fake_ip,
                'HTTP_CACHE_INFO:' . $fake_ip,
                'HTTP_XPROXY:' . $fake_ip,
                'HTTP_PROXY_CONNECTION:' . $fake_ip,
                'HTTP_VIA:' . $fake_ip,
                'HTTP_X_COMING_FROM:' . $fake_ip,
                'HTTP_COMING_FROM:' . $fake_ip,
                'HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'HTTP_X_FORWARDED:' . $fake_ip,
                'HTTP_CACHE_CONTROL:' . $fake_ip,
                'HTTP_CLIENT_IP:' . $fake_ip,
                'HTTP_FORWARDED:' . $fake_ip,
                'HTTP_PRAGMA:' . $fake_ip,
                'HTTP_XONNECTION:' . $fake_ip,
                'HTTP_CACHE_INFO:' . $fake_ip,
                'HTTP_XPROXY:' . $fake_ip,
                'HTTP_PROXY_CONNECTION:' . $fake_ip,
                'HTTP_VIA:' . $fake_ip,
                'HTTP_X_COMING_FROM:' . $fake_ip,
                'HTTP_COMING_FROM:' . $fake_ip,
                'HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'HTTP_X_FORWARDED:' . $fake_ip,
                'ZHTTP_CACHE_CONTROL:' . $fake_ip,
                'REMOTE_ADDR: ' . $fake_ip,
                'REMOTE_ADDR:' . $fake_ip,
                'X-Client-IP:' . $fake_ip,
                'Client-IP: ' . $fake_ip,
                'HTTP_X_FORWARDED_FOR: ' . $fake_ip,
                'X-Forwarded-For: ' . $fake_ip,
                'x-HTTP_PRAGMA:' . $fake_ip,
                'x-HTTP_XONNECTION:' . $fake_ip,
                'x-HTTP_CACHE_INFO:' . $fake_ip,
                'x-HTTP_XPROXY:' . $fake_ip,
                'x-HTTP_PROXY_CONNECTION:' . $fake_ip,
                'x-HTTP_VIA:' . $fake_ip,
                'x-HTTP_X_COMING_FROM:' . $fake_ip,
                'x-HTTP_COMING_FROM:' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'x-HTTP_X_FORWARDED:' . $fake_ip,
                'x-HTTP_CACHE_CONTROL:' . $fake_ip,
                'x-HTTP_CLIENT_IP:' . $fake_ip,
                'x-HTTP_FORWARDED:' . $fake_ip,
                'x-HTTP_PRAGMA:' . $fake_ip,
                'x-HTTP_XONNECTION:' . $fake_ip,
                'x-HTTP_CACHE_INFO:' . $fake_ip,
                'x-HTTP_XPROXY:' . $fake_ip,
                'x-HTTP_PROXY_CONNECTION:' . $fake_ip,
                'x-HTTP_VIA:' . $fake_ip,
                'x-HTTP_X_COMING_FROM:' . $fake_ip,
                'x-HTTP_COMING_FROM' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR' . $fake_ip,
                'x-HTTP_X_FORWARDED:' . $fake_ip,
                'x-ZHTTP_CACHE_CONTROL:' . $fake_ip,
                'x-REMOTE_ADDR: ' . $fake_ip,
                'x-REMOTE_ADDR' . $fake_ip,
                'X-Client-IP:' . $fake_ip,
                'x-Client-IP: ' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR: ' . $fake_ip,
                'X-Forwarded-For: ' . $fake_ip,
                ],
		]
		];
$online=cURLx($online_params);

$post_data="nick={$username}&event_type=login&event_value=0&attributes=%7B%22short_shuffle%22%3Atrue%7D";
$online_params = [
		'url' => "https://connected2.me/api/send-event",
		'options' => [
		CURLOPT_POST => TRUE,
		CURLOPT_POSTFIELDS => $post_data,
		CURLOPT_HTTPHEADER => [
                    'Accept: */*',
					'Host: connected2.me',
					'Accept-Encoding: gzip, deflate, br',
					'Content-Length: '.strlen($post_data),
					'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept-Language: en-GB,en;q=0.8,tr;q=0.6',
                    'Referer: https://connected2.me',
                    'Origin: https://connected2.me',
                    'User-Agent: ' . $userAgent,
                    'Connection: keep-alive',
                    'X-Requested-With: XMLHttpRequest',
                'X_FORWARDED_FOR: ' . $fake_ip,
                'REMOTE_ADDR: ' . $fake_ip,
                'HTTP_CLIENT_IP:' . $fake_ip,
                'HTTP_FORWARDED:' . $fake_ip,
                'HTTP_PRAGMA:' . $fake_ip,
                'HTTP_XONNECTION:' . $fake_ip,
                'HTTP_CACHE_INFO:' . $fake_ip,
                'HTTP_XPROXY:' . $fake_ip,
                'HTTP_PROXY_CONNECTION:' . $fake_ip,
                'HTTP_VIA:' . $fake_ip,
                'HTTP_X_COMING_FROM:' . $fake_ip,
                'HTTP_COMING_FROM:' . $fake_ip,
                'HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'HTTP_X_FORWARDED:' . $fake_ip,
                'HTTP_CACHE_CONTROL:' . $fake_ip,
                'HTTP_CLIENT_IP:' . $fake_ip,
                'HTTP_FORWARDED:' . $fake_ip,
                'HTTP_PRAGMA:' . $fake_ip,
                'HTTP_XONNECTION:' . $fake_ip,
                'HTTP_CACHE_INFO:' . $fake_ip,
                'HTTP_XPROXY:' . $fake_ip,
                'HTTP_PROXY_CONNECTION:' . $fake_ip,
                'HTTP_VIA:' . $fake_ip,
                'HTTP_X_COMING_FROM:' . $fake_ip,
                'HTTP_COMING_FROM:' . $fake_ip,
                'HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'HTTP_X_FORWARDED:' . $fake_ip,
                'ZHTTP_CACHE_CONTROL:' . $fake_ip,
                'REMOTE_ADDR: ' . $fake_ip,
                'REMOTE_ADDR:' . $fake_ip,
                'X-Client-IP:' . $fake_ip,
                'Client-IP: ' . $fake_ip,
                'HTTP_X_FORWARDED_FOR: ' . $fake_ip,
                'X-Forwarded-For: ' . $fake_ip,
                'x-HTTP_PRAGMA:' . $fake_ip,
                'x-HTTP_XONNECTION:' . $fake_ip,
                'x-HTTP_CACHE_INFO:' . $fake_ip,
                'x-HTTP_XPROXY:' . $fake_ip,
                'x-HTTP_PROXY_CONNECTION:' . $fake_ip,
                'x-HTTP_VIA:' . $fake_ip,
                'x-HTTP_X_COMING_FROM:' . $fake_ip,
                'x-HTTP_COMING_FROM:' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR:' . $fake_ip,
                'x-HTTP_X_FORWARDED:' . $fake_ip,
                'x-HTTP_CACHE_CONTROL:' . $fake_ip,
                'x-HTTP_CLIENT_IP:' . $fake_ip,
                'x-HTTP_FORWARDED:' . $fake_ip,
                'x-HTTP_PRAGMA:' . $fake_ip,
                'x-HTTP_XONNECTION:' . $fake_ip,
                'x-HTTP_CACHE_INFO:' . $fake_ip,
                'x-HTTP_XPROXY:' . $fake_ip,
                'x-HTTP_PROXY_CONNECTION:' . $fake_ip,
                'x-HTTP_VIA:' . $fake_ip,
                'x-HTTP_X_COMING_FROM:' . $fake_ip,
                'x-HTTP_COMING_FROM' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR' . $fake_ip,
                'x-HTTP_X_FORWARDED:' . $fake_ip,
                'x-ZHTTP_CACHE_CONTROL:' . $fake_ip,
                'x-REMOTE_ADDR: ' . $fake_ip,
                'x-REMOTE_ADDR' . $fake_ip,
                'X-Client-IP:' . $fake_ip,
                'x-Client-IP: ' . $fake_ip,
                'x-HTTP_X_FORWARDED_FOR: ' . $fake_ip,
                'X-Forwarded-For: ' . $fake_ip,
                ],
		]
		];
$online=cURLx($online_params);

$online_params=[
'url' => 'https://x.connected2.me/http-bind',
];
}

function read_file($file){
if ($file = fopen($file, "r")) {
    while(!feof($file)) {
        $lines[] = fgets($file);
        # do same stuff with the $line
    }
    fclose($file);
}
return $lines;
}


function randFakeIP(){

        $hello = rand(40,200);
        $RandIp = $hello ."." . rand(0, 255) . "." . rand(0, 255) . "." . rand(0, 255) . "";

        return $RandIp;
    }
	
	
function randUserAgent(){

        $random_version = rand(40, 50);
        return "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/{$random_version}.36 (KHTML, like Gecko) Chrome/" . rand(40,50) . ".0.2357." . rand(180,200) . " Safari/{$random_version}.36";
    }

function cURLx($params){
	$ch = curl_init();
    $options = array(
        CURLOPT_URL => $params['url'],
		CURLOPT_HEADER => FALSE,
		CURLOPT_FOLLOWLOCATION => FALSE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        );
	if ( is_array($params['options']) )
        {
            foreach($params['options'] as $option => $value) {
                $options[$option] = $value;
            }
        }
	curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
    return $response;
    }


?>