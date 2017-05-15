<?php
set_time_limit(0);
error_reporting(E_ALL);
include "lib/c2bot.php";
include "lib/tempmail.class.php";

if($_POST){
$ex_bio=array();
$exx_bio = trim($_POST['ex_bio']);
$ex_bio = explode("\n", $exx_bio);
$ex_bio = array_filter($ex_bio, 'trim');
$bio = file('bio.txt', FILE_IGNORE_NEW_LINES);
$lines = read_file('cookies/sifre.txt');
foreach ($lines as $line){
$parcala = explode(':',$line);
$username = $parcala[0];
$username = preg_replace('/\s+/', '', $username);
$password = $parcala[1];
$password = preg_replace('/\s+/', '', $password);

$randomBio = ( count($bio) > 0 ) ? $bio[rand(0, (count($bio) - 1))] : NULL;
$randomExBio = ( count($ex_bio) > 0 ) ? $ex_bio[rand(0, (count($ex_bio) - 1))] : NULL;
$update_bio=$randomExBio . PHP_EOL . $randomBio;
$update_bio=encodeURIComponent($update_bio);
$userAgent = randUserAgent();
		$bio_params = [
		'url' => "http://api.connected2.me/b/set_bio?nick={$username}&password={$password}&bio={$update_bio}",
		'options' => [
		CURLOPT_ENCODING => "gzip,deflate",
                CURLOPT_HTTPHEADER => [
                    'Accept: */*',
                    'Accept-Language: tr-TR,tr;q=0.8,en-US;q=0.5,en;q=0.3',
                    'Accept-Encoding: gzip, deflate',
                    'Referer: https://connected2.me',
                    'Content-Type: application/x-www-form-urlencoded',
                    'Origin: https://connected2.me',
                    'User-Agent: ' . $userAgent,
                    'Connection: keep-alive',
                    'Pragma: no-cache',
                    'Cache-Control: no-cache'
                ],
		]
		];
var_dump($bio_params);
$bio_up=cURLx($bio_params);
print_r($bio_up);
}
}

function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
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


?>