<?php
set_time_limit(0);
error_reporting(E_ALL);
$lines = read_file('cookies/sifre.txt');
$directory = "storyImages/";
$images = glob($directory . "*.jpg");
foreach ($lines as $line){
$parcala = explode(':',$line);
$username = $parcala[0];
$username = preg_replace('/\s+/', '', $username);
$password = $parcala[1];
$password = preg_replace('/\s+/', '', $password);
// data fields for POST request
$fields = array("hasEditing"=>"false", "nick"=>"{$username}", "password"=>"{$password}","source"=>"gallery","type"=>"photo");
// files to upload
$image=$images[rand(0,(count($images)-1))];
$file=file_get_contents($image);
$url_data = http_build_query($fields);
$boundary = uniqid();
$delimiter = '-------------' . $boundary;
$user_agent = 'Connected2/1.105.1 (iPhone; iOS 11.2; Scale/2.00)';
$fake_ip = randFakeIP();
$post_data = build_data_files($boundary, $fields, $file);
				$headers = [
                'Accept: */*',
				'Accept-Encoding: gzip, deflate',
				'Content-Type: multipart/form-data; boundary=' . $delimiter,
				'Content-Length: ' . strlen($post_data),
                'User-Agent: ' . $user_agent,
                'Connection: keep-alive',
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

            $options = [
                CURLOPT_HEADER => TRUE,
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => $headers
            ];

            $params = [
                'url' => "http://api.c2me.cc/b/send_story",
                'options' => $options
            ];
            $res = cURLx($params);
}

			
function build_data_files($boundary, $fields, $file){
    $data = '';
    $eol = "\r\n";

    $delimiter = '-------------' . $boundary;

    foreach ($fields as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
            . $content . $eol;
    }


        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="file"; filename="image"' . $eol
            //. 'Content-Type: image/png'.$eol
            . 'Content-Transfer-Encoding: binary'.$eol
            ;

        $data .= $eol;
        $data .= $file . $eol;
    $data .= "--" . $delimiter . "--".$eol;
    return $data;
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