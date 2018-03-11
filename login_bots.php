<?php
set_time_limit(0);
error_reporting(E_ALL);
$proxy_file = @fopen('proxies.txt', 'r'); 
if ($proxy_file) {
   $proxies = explode(PHP_EOL, fread($proxy_file, filesize('proxies.txt')));
}
$rand_proxy=( count($proxies) > 0 ) ? $proxies[rand(0, (count($proxies) - 1))] : NULL;
$lines = read_file('cookies/sifre.txt');
foreach ($lines as $line){
$parcala = explode(':',$line);
$username = $parcala[0];
$username = preg_replace('/\s+/', '', $username);
$password = $parcala[1];
$password = preg_replace('/\s+/', '', $password);
$user_agent = 'Connected2/1.105.1 (iPhone; iOS 11.2; Scale/2.00)';
$fake_ip = randFakeIP();
$get_header = [
                'Accept: */*',
				'Accept-Encoding: gzip, deflate',
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
			    'User-Agent: ' . $user_agent,
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
            ];
			$get_options = [
                CURLOPT_HEADER => TRUE,
                CURLOPT_HTTPHEADER => $get_header
            ];
			if ($rand_proxy != NULL){
			if (proxy_check($rand_proxy) != false){
				$get_options = [
                CURLOPT_HEADER => TRUE,
                CURLOPT_HTTPHEADER => $get_header,
				CURLOPT_PROXY => $rand_proxy
				];
			}
			}
				$device_id=GenerateGuid();


$online_params = [
		'url' => "http://api.c2me.cc/b/mobile_info?device_id={$device_id}&nick={$username}&password={$password}",
		'options' => $get_options 
		];
$online=cURLx($online_params);
$online_params = [
		'url' => "http://api.c2me.cc/b/switch_status?nick={$username}&password={$password}",
		'options' => $get_options
		];
$online=cURLx($online_params);
}
function randFakeIP(){

        $hello = rand(40,200);
        $RandIp = $hello ."." . rand(0, 255) . "." . rand(0, 255) . "." . rand(0, 255) . "";

        return $RandIp;
    }
	
function cURLx($params){
	$ch = curl_init();
    $options = array(
        CURLOPT_URL => $params['url'],
		CURLOPT_HEADER => FALSE,
		CURLOPT_FOLLOWLOCATION => FALSE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_SSL_VERIFYPEER => FALSE
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
	
function proxy_check($proxy){
	$post_data=preparePostFields(array("proxy"=>$proxy));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, "Connected2/1.105.1 (iPhone; iOS 11.2; Scale/2.00)");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, 'https://hidemy.life/api/checkproxy');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest"));
	$response = curl_exec($ch);
	$response = json_decode($response);
	return $response->online;
}

function preparePostFields($array) {
  $params = array();

  foreach ($array as $key => $value) {
    $params[] = $key . '=' . urlencode($value);
  }

  return implode('&', $params);
}

function GenerateGuid() {
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
?>
