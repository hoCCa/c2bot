<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_start();

require 'iCurl.php';

class c2bot {

	public static $emailaddress = null; // Email address
	
    # Mobile API için kullanacak URL
    private $API_URL = 'http://api.c2me.cc/b/';

    # Çerezlerin tutulacağı klasor ismi
    private $cFolder = "cookies/";

    # Çerezlerin tutulacağı uzantı
    private $cExtension = ".json";

    # İşlemlerde kullanılan proksi listesi değişkeni
    private $proxyList = [];

    # İşlemlerde kullanılacak isim listesi
    private $nameList = [];

    # Takip edilecek listesi
    private $followList = [];

    # Random fotoğraf listesi
    private $pictures = [];

    # Random bio listesi
    private $bio = [];
	
	private $ex_bio = [];
	
	# Email
	private $email='';
	
	# TempMail
	private $tmpmail=null;
		
	# Cinsiyet
	private $gender = f;
	
	# Telefon numarası
	private $phone_number = '5355350000';

    # İşlemlerde var olan SQL bağlantısı kullanılacak mı?
    private $SQL = FALSE;

    # İşlem sonuç değişkeni 1
    private $success = NULL;

    # İşlem sonuç değişkeni 2
    private $clean = NULL;

    # İşlem sonuç değişkeni 3
    private $data = NULL;

    # İşlem sonuç değişkeni 4
    private $response = NULL;
	

    /*
     *  Genel Ayarların yapıldığı fonk.
     *
     *  @param $settings
     */
    public function __construct($settings = NULL)
    {
        if ( isset($settings) )
        {
            if ( is_array($settings) )
            {
                $this->SQL = ($settings['_sql'] == true) ? true : false;
                if ($settings['_folder']) $this->cFolder = $settings['_folder'];
                if ( stripos($settings['_extension'], '.') === FALSE )
                {
                    $this->cExtension = '.' . $settings['_extension'];
                } else {
                    $this->cExtension = '.' . str_replace(array('.'), NULL, $settings['_extension']);
                }
                if (is_array($settings['_proxys'])) $this->proxyList = $settings['_proxys'];
                if (is_array($settings['_names'])) $this->nameList = $settings['_names'];
                if (is_array($settings['_follow_ids'])) $this->followList = $settings['_follow_ids'];
                if (is_array($settings['_pictures'])) $this->pictures = $settings['_pictures'];
                if (is_array($settings['_bio'])) $this->bio = $settings['_bio'];
				if (is_array($settings['_ex_bio'])) $this->ex_bio = $settings['_ex_bio'];
				if (!empty($settings['_gender'])) $this->gender = $settings['_gender'];
				if (!empty($settings['_phone_number'])) $this->phone_number = $settings['_phone_number'];
				if (!empty($settings['_email'])) $this->email = $settings['_email'];
            }
        }
		$this->tmpmail = $this->setmail();
		$this->email = $this->emailaddress;
    }

    /*
     *  Instagram için bot açma fonksiyonu
     *
     *  @param $count
     *
     *  @return json
     */
    public function createUser($count = 1)
    {
        $this->response = [];

        $opened_account = 0;
        for ($i = 1; $i <= $count; $i++)
        {
            $randomName = ( count($this->nameList) > 0 ) ? $this->nameList[rand(0, (count($this->nameList) - 1))] : NULL;
            $username = ($randomName) ? $this->generateUx($randomName) : $this->randomUsername();
			$password = $this->randomAlpha(10);
            $user_agent = $this->randUserAgent();
            $fake_ip = $this->randFakeIP();
           
            $first_name = ($randomName) ? $randomName : $username;
            $email_username = str_replace([".", "_"], NULL, $username);
			$email = $this->email;
			$email .='@gmail.com';
			$gender=$this->gender;
			//$email = "viay@binka.me";
			//$username="viay";
			//$password="serseri77";
            $post_data = "email={$email}&password={$password}&username={$username}";
          $headers = [
                'Accept: */*',
                'Content-Length: '.strlen($post_data),
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
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
                'url' => $this->API_URL."register",
                'options' => $options
            ];
            $data = $this->cURL($params);
			if(preg_match('/{"status": "OK"}/i', $data))
            {
				// Define the GuID
$guid = $this->GenerateGuid();
// Set the devide ID
$device_id = "android-".$guid;
				$parameters=[
					'url' => $this->API_URL."mobile_info?device_id={$device_id}&nick={$username}&password={$password}",
					'options' => [
                CURLOPT_HEADER => FALSE
            ]
				];
				$this->cURL($parameters);
				$parameters=[
					'url' => $this->API_URL."switch_status?nick={$username}&password={$password}",
					'options' => [
                CURLOPT_HEADER => FALSE
            ]
				];
				$this->cURL($parameters);
			
            $params = [
                'url' => $this->API_URL."set_birthday_and_gender?birthday=1993-06-18&gender={$gender}&nick={$username}&password={$password}",
                'options' => [
                CURLOPT_HEADER => FALSE
            ]
            ];
			$this->cURL($params);
                // Cookie File
                $cookie_file = $this->cFolder . $username . $this->cExtension;

                $json = json_encode([
                    "first_name"            => $first_name,
                    "username"              => $username,
                    "password"              => $password,
                    "email"                 => $email,
                    "fake_ip"               => $fake_ip,
                    "user_agent"            => $user_agent,
                    "changed_profile"       => false,
                    "web"                   => true,
                    "status"                => "ok"
                ], JSON_PRETTY_PRINT);
                //file_put_contents($cookie_file, $json);

				// ŞİFRELERİ TUT 
				
				$sifre = $username.":".$password;
				$sifre_file = $this->cFolder . "sifre.txt";
				file_put_contents($sifre_file, $sifre."\n",FILE_APPEND);

				/*
				$stat=true;
				while($stat){
					$msg=$this->getmails($this->email);
					if((!isset($msg["error"]))){
						if((!is_null($msg))){
							$stat=false;
						}
					}
				}
				$mail_text=$msg[0]["mail_text_only"];
				$verify_link=preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#',$mail_text,$verify);
				$verify_params = [
                'url' => $verify[0][0]
				];
				$verify_now=$this->cURL($verify_params);*/
				$this->change_pp($username,$password);
				$this->change_bio($username,$password); 				
                if (is_array($this->followList))
                {
                    foreach ($this->followList as $follow_id)
                    {
                        if ($follow_id){
                          $this->follow_($username,$password,$follow_id);
                    }
					}
                }
                $this->response[] = [
                    'email' => $email,
                    'username' => $username,
					'password'=> $password
                ];
                $opened_account++;
            }
        }

        return json_encode([
            'success'   => $opened_account,
            'accounts'  => $this->response
        ]);
    }
	

    /*
     *  Açılan bot hesaplara takip ettirme fonksiyonu
     *
     *  @param $user_id
     *  @param $count
     *
     *  @return json
     */

	 
	public function change_pp($username,$password){
		if (count($this->pictures) > 0)
                { 
                    $randomPicture = ( count($this->pictures) > 0 ) ? $this->pictures[rand(0, (count($this->pictures) - 1))] : NULL;
                    $this->singleChangeProfile($username, $password, $randomPicture);
                }
	}
	
	public function change_bio($username,$password){
		$randomBio = ( count($this->bio) > 0 ) ? $this->bio[rand(0, (count($this->bio) - 1))] : NULL;
		$randomExBio = ( count($this->ex_bio) > 0 ) ? $this->ex_bio[rand(0, (count($this->ex_bio) - 1))] : NULL;
		$bio=$randomExBio . PHP_EOL . $randomBio;
		$bio=$this->encodeURIComponent($bio);
		$bio_params = [
		'url' => $this->API_URL."set_bio?nick={$username}&password={$password}&bio={$bio}",
		'options' => [
		CURLOPT_ENCODING => "gzip,deflate",
                CURLOPT_HTTPHEADER => [
                    'Accept: */*',
                    'Accept-Language: tr-TR,tr;q=0.8,en-US;q=0.5,en;q=0.3',
                    'Accept-Encoding: gzip, deflate',
                    'Referer: https://connected2.me',
                    'Content-Type: multipart/form-data',
                    'Origin: https://connected2.me',
                    'User-Agent: ' . $userAgent,
                    'Connection: keep-alive',
                    'Pragma: no-cache',
                    'Cache-Control: no-cache'
                ],
		]
		];
		$bio_up=$this->cURL($bio_params);
	}
	
	
	public function follow_($username,$password,$follow_nick){
		$follow_params = [
		'url' => $this->API_URL."follow?nick={$username}&password={$password}&follow_nick={$follow_nick}"
		];
		$follow=$this->cURL($follow_params);
	}
	
    public function follow($user_id, $count, $accounts = NULL)
    {
        set_time_limit(0);
        if (is_array($accounts))
            $cookies = $accounts;
        else
            $cookies = $this->getCookies($count);

        $key = 0;
        foreach ($cookies as $cookie) {

            // Cookie Data
            $user = json_decode(file_get_contents($cookie));

            if ($user->web) // Web Cookie
            {
                $fake_ip = ($user->fake_ip) ? $user->fake_ip : $this->randFakeIP();
                $user_agent = ($user->user_agent) ? $user->user_agent : $this->randUserAgent();

                $opts[$key] = [
                    "url"         => "https://www.instagram.com/web/friendships/{$user_id}/follow/",
                    "user_agent"  => $user_agent,
                    "cookie"      => $user->cookie,
                    "header"      => TRUE,
                    "header_out"  => TRUE,
                    "post"        => [],
                    "http_header" => [
                        'origin: https://www.instagram.com',
                        'accept-encoding: gzip, deflate, br',
                        'accept-language: tr-TR,tr;q=0.8,en-US;q=0.6,en;q=0.4',
                        "User-Agent: {$user_agent}",
                        'x-requested-with: XMLHttpRequest',
                        'x-csrftoken: ' . $user->csrftoken,
                        'x-instagram-ajax: 1',
                        'content-type: application/x-www-form-urlencoded',
                        'cookie:' . $user->cookie,
                        'accept: */*',
                        "referer: https://www.instagram.com/{$user->username}/",
                        'authority: www.instagram.com',
                        'content-length: 0',
                        'X_FORWARDED_FOR: ' . $fake_ip,
                        'REMOTE_ADDR: ' . $fake_ip
                    ],
                    "key" => $key
                ];
            }

            $data[$key] = [
                "username"  => $user->username,
                "id"        => $user->id
            ];

            $key++;
        }

        //print_r($opts);

        $this->success = 0;
        $this->clean = 0;
        $this->data = [];
        multi_curl($opts, function ($que, $key) use (&$data) {
            $_data = $data[$key];
            if ($que->status == "ok" && $que->http_code == 200) {
                if ($this->SQL)
                    DB::query("UPDATE `accounts` SET `last_action_time` = NOW() WHERE `user_id` = '{$_data['id']}'");
                $this->success++;
            } elseif ($que->status == "fail" && $que->message == "login_required") {
                if ($this->SQL)
                    DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$_data['id']}'");
                @unlink($this->cFolder . $_data['username'] . ".json");
                $this->clean++;
            } elseif ($que->status == "fail" && $que->message == "checkpoint_required") {
                if ($this->SQL)
                    DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$_data['id']}'");
                @unlink($this->cFolder . $_data['username'] . ".json");
                $this->clean++;
            } elseif ($que->status == "fail" && $que->message == "unauthorized") {
                if ($this->SQL)
                    DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$_data['id']}'");
                @unlink($this->cFolder . $_data['username'] . ".json");
                $this->clean++;
            }
            $this->data[] = [
                'username' => $_data['username'],
                'json' => $que->content
            ];
        }, 250);

        return json_encode([
            'success'    => $this->success,
            'clean'      => $this->clean,
            'response'   => $this->data
        ]);
    }

    /*
     *  Açılan bot hesaplara takip ettirme fonksiyonu
     *
     *  @param $user_id
     *  @param $count
     *
     *  @return json
     */

    public function like($media_id, $count, $accounts = NULL)
    {
        set_time_limit(0);
        if (is_array($accounts))
            $cookies = $accounts;
        else
            $cookies = $this->getCookies($count);

        $key = 0;
        foreach ($cookies as $cookie) {

            // Cookie Data
            $user = json_decode(file_get_contents($cookie));

            if ($user->web) // Web Cookie
            {
                $fake_ip = ($user->fake_ip) ? $user->fake_ip : $this->randFakeIP();
                $user_agent = ($user->user_agent) ? $user->user_agent : $this->randUserAgent();

                $opts[$key] = [
                    "url"         => "https://www.instagram.com/web/likes/{$media_id}/like/",
                    "user_agent"  => $user_agent,
                    "cookie"      => $user->cookie,
                    "header"      => TRUE,
                    "header_out"  => TRUE,
                    "post"        => [
                        "media_id"  => $media_id
                    ],
                    "http_header" => [
                        'origin: https://www.instagram.com',
                        'accept-encoding: gzip, deflate, br',
                        'accept-language: tr-TR,tr;q=0.8,en-US;q=0.6,en;q=0.4',
                        "User-Agent: {$user_agent}",
                        'x-requested-with: XMLHttpRequest',
                        'x-csrftoken: ' . $user->csrftoken,
                        'x-instagram-ajax: 1',
                        'content-type: application/x-www-form-urlencoded',
                        'cookie:' . $user->cookie,
                        'accept: */*',
                        "referer: https://www.instagram.com/{$user->username}/",
                        'authority: www.instagram.com',
                        'content-length: 0',
                        'X_FORWARDED_FOR: ' . $fake_ip,
                        'REMOTE_ADDR: ' . $fake_ip
                    ],
                    "key" => $key
                ];
            }

            $data[$key] = [
                "username"  => $user->username,
                "id"        => $user->id
            ];

            $key++;
        }

        //print_r($opts);

        $this->success = 0;
        $this->clean = 0;
        $this->data = [];
        multi_curl($opts, function ($que, $key) use (&$data) {
            $_data = $data[$key];
            if ($que->status == "ok" && $que->http_code == 200) {
                if ($this->SQL)
                    DB::query("UPDATE `accounts` SET `last_action_time` = NOW() WHERE `user_id` = '{$_data['id']}'");
                $this->success++;
            } elseif ($que->status == "fail" && $que->message == "login_required") {
                if ($this->SQL)
                    DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$_data['id']}'");
                @unlink($this->cFolder . $_data['username'] . ".json");
                $this->clean++;
            } elseif ($que->status == "fail" && $que->message == "checkpoint_required") {
                if ($this->SQL)
                    DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$_data['id']}'");
                @unlink($this->cFolder . $_data['username'] . ".json");
                $this->clean++;
            } elseif ($que->status == "fail" && $que->message == "unauthorized") {
                if ($this->SQL)
                    DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$_data['id']}'");
                @unlink($this->cFolder . $_data['username'] . ".json");
                $this->clean++;
            }
            $this->data[] = [
                'username' => $_data['username'],
                'json' => $que->content
            ];
        }, 250);

        return json_encode([
            'success'    => $this->success,
            'clean'      => $this->clean,
            //'response'   => $this->data
        ]);
    }

    /*
     *  Açılan bot hesapların resim güncelleme fonksiyonu
     *
     *  @param $username
     *  @param $photo
     *
     *  @return json
     */
    public function singleChangeProfile($username, $password, $photo)
    {

        $cookie = $this->cFolder . $username . $this->cExtension;
        $user_data = json_decode(file_get_contents($cookie));
        $userAgent = ($user_data->user_agent) ? $user_data->user_agent : $this->randUserAgent();

        $params = [
            'url' => $this->API_URL.'upload_profile_picture',
            'options' => [
                CURLOPT_POST => TRUE,
                CURLOPT_POSTFIELDS => [
                    'file' => $this->cURLValue($photo),
					'nick' => $username,
					'password' => $password
                ],
                CURLOPT_ENCODING => "gzip,deflate",
                CURLOPT_HTTPHEADER => [
                    'Accept: */*',
                    'Accept-Language: tr-TR,tr;q=0.8,en-US;q=0.5,en;q=0.3',
                    'Accept-Encoding: gzip, deflate',
                    'Referer: https://connected2.me',
                    'Content-Type: multipart/form-data',
                    'Origin: https://connected2.me',
                    'User-Agent: ' . $userAgent,
                    'Connection: keep-alive',
                    'Pragma: no-cache',
                    'Cache-Control: no-cache'
                ],
            ]
        ];

        $que = json_decode($this->cURL($params));
    }

	
	
	/*
	** ChangeBio
	*/
	    public function changeBio($username,$email,$phone_number,$gender,$bio)
    {

        $cookie = $this->cFolder . $username . $this->cExtension;
        $user_data = json_decode(file_get_contents($cookie));

        $csrf_token = $user_data->csrftoken;
        $userAgent = ($user_data->user_agent) ? $user_data->user_agent : $this->randUserAgent();

        $params = [
            'url' => 'https://www.instagram.com/accounts/edit/',
            'options' => [
                CURLOPT_POST => TRUE,
				CURLOPT_VERBOSE => FALSE,
                CURLOPT_POSTFIELDS => [
					'email' => $email,
                    'biography' => $bio
                ],
                CURLOPT_ENCODING => "gzip,deflate",
                CURLOPT_HTTPHEADER => [
                    'Accept: */*',
                    'Accept-Language: tr-TR,tr;q=0.8,en-US;q=0.5,en;q=0.3',
                    'Accept-Encoding: gzip, deflate',
                    'Referer: https://www.instagram.com/',
                    'DNT: 1',
                    'Content-Type: multipart/form-data',
                    'Origin: https://www.instagram.com/',
                    'X-CSRFToken: ' . trim($csrf_token),
                    'X-Requested-With: XMLHttpRequest',
                    'User-Agent: ' . $userAgent,
                    'X-Instagram-AJAX: 1',
                    'Connection: keep-alive',
                    'Pragma: no-cache',
                    'Cache-Control: no-cache'
                ],
                CURLOPT_COOKIE => $user_data->cookie
            ]
        ];

        $que = json_decode($this->cURL($params));
        if ($que->status == "ok") {

            if ($this->SQL)
                DB::query("UPDATE `accounts` SET `picture` = '{$que->profile_pic_url}', `changed_profile` = '1' WHERE `user_id` = '{$user_data->id}'");

            $_data['user_array']['changed_profile'] = true;
            file_put_contents($this->cFolder . $_data['username'] . $this->cExtension, json_encode($_data['user_array'], JSON_PRETTY_PRINT));

            $this->success++;
        } elseif ($que->status == "fail" && $que->message == "login_required") {
            if ($this->SQL)
                DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$user_data->id}'");
            @unlink($this->cFolder . $user_data->username . ".json");
            $this->clean++;
        } elseif ($que->status == "fail" && $que->message == "checkpoint_required") {
            if ($this->SQL)
                DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$user_data->id}'");
            @unlink($this->cFolder . $user_data->username . ".json");
            $this->clean++;
        } elseif ($que->status == "fail" && $que->message == "unauthorized") {
            if ($this->SQL)
                DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$user_data->id}'");
            @unlink($this->cFolder . $user_data->username . ".json");
            $this->clean++;
        }
    }
    /*
     *  Açılan bot hesapların toplu resim güncelleme fonksiyonu
     *
     *  @param $username
     *  @param $photo
     *
     *  @return json
     */
    public function multipleChangeProfile($count, $pictures)
    {
        $cookies = $this->getCookies($count);

        $key = 0;
        foreach ($cookies as $cookie)
        {
            $user = json_decode(file_get_contents($cookie));
            $user_array = json_decode(file_get_contents($cookie), true);

            shuffle($pictures);
            shuffle($pictures);
            $photo = ( count($pictures) > 0 ) ? $pictures[rand(0, (count($pictures) - 1))] : NULL;

            if ($user->changed_profile == false) // Web Cookie
            {
                $userAgent = ($user->user_agent) ? $user->user_agent : $this->randUserAgent();

                $opts[$key] = [
                    "url"         => "https://www.instagram.com/accounts/web_change_profile_picture/",
                    "user_agent"  => $userAgent,
                    "cookie"      => $user->cookie,
                    "encoding"    => "gzip,deflate",
                    "header"      => TRUE,
                    "header_out"  => TRUE,
					"verbose"	  => FALSE,
                    "_post"        => [
                        'profile_pic' => $this->cURLValue($photo)
                    ],
                    "http_header" => [
                        'Accept: */*',
                        'Accept-Language: tr-TR,tr;q=0.8,en-US;q=0.5,en;q=0.3',
                        'Accept-Encoding: gzip, deflate',
                        'Referer: https://www.instagram.com/',
                        'DNT: 1',
                        'Content-Type: multipart/form-data',
                        'Origin: https://www.instagram.com/',
                        'X-CSRFToken: ' . trim($user->csrftoken),
                        'X-Requested-With: XMLHttpRequest',
                        'User-Agent: ' . $userAgent,
                        'X-Instagram-AJAX: 1',
                        'Connection: keep-alive',
                        'Pragma: no-cache',
                        'Cache-Control: no-cache'
                    ],
                    "key" => $key
                ];
            }

            $data[$key] = [
                "username"      => $user->username,
                "id"            => $user->id,
                "user_array"    => $user_array
            ];

            $key++;
        }

        $this->success = 0;
        $this->clean = 0;
        $this->data = [];
        multi_curl($opts, function ($que, $key) use (&$data) {
            $_data = $data[$key];
            if ($que->status == "ok" && $que->http_code == 200) {

                if ($this->SQL)
                    DB::query("UPDATE `accounts` SET `picture` = '{$que->profile_pic_url}', `changed_profile` = '1' WHERE `user_id` = '{$_data['id']}'");

                $_data['user_array']['changed_profile'] = true;
                file_put_contents($this->cFolder . $_data['username'] . $this->cExtension, json_encode($_data['user_array'], JSON_PRETTY_PRINT));

                $this->success++;
            } elseif ($que->status == "fail" && $que->message == "login_required") {
                if ($this->SQL)
                    DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$_data['id']}'");
                @unlink($this->cFolder . $_data['username'] . ".json");
                $this->clean++;
            } elseif ($que->status == "fail" && $que->message == "checkpoint_required") {
                if ($this->SQL)
                    DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$_data['id']}'");
                @unlink($this->cFolder . $_data['username'] . ".json");
                $this->clean++;
            } elseif ($que->status == "fail" && $que->message == "unauthorized") {
                if ($this->SQL)
                    DB::query("DELETE FROM `accounts` WHERE `user_id` = '{$_data['id']}'");
                @unlink($this->cFolder . $_data['username'] . ".json");
                $this->clean++;
            }
            $this->data[] = [
                'username' => $_data['username'],
                'json' => $que->content
            ];
        }, 250);

        return json_encode([
            'success'    => $this->success,
            'clean'      => $this->clean,
            'response'   => $this->data
        ]);
    }

	 /*
     *  Açılan hesaba fotoğraflar yükler
     *
     *  @param $username
     *  @param $photo
     *
     *  @return json
     */
    public function addPhotos($username, $count, $pictures)
    {
        $cookie = $this->cFolder . $username . $this->cExtension;
        $user_data = json_decode(file_get_contents($cookie));
        $csrf_token = $user_data->csrftoken;
        $userAgent = ($user_data->user_agent) ? $user_data->user_agent : $this->randUserAgent();
		$userAgent = 'Instagram 6.21.2 Android (19/4.4.2; 480dpi; 1152x1920; Meizu; MX4; mx4; mt6595; en_US)';
	
// Define the GuID
$guid = $this->GenerateGuid();
// Set the devide ID
$device_id = "android-".$guid;
/* LOG IN */
// You must be logged in to the account that you wish to post a photo too
// Set all of the parameters in the string, and then sign it with their API key using SHA-256
$data = '{"device_id":"'.$device_id.'","guid":"'.$guid.'","username":"'.$user_data->username.'","password":"'.$user_data->password.'","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}';
$sig = $this->GenerateSignature($data);
$data = 'signed_body='.$sig.'.'.urlencode($data).'&ig_sig_key_version=6';
$login = $this->SendRequest('accounts/login/', true, $data, $userAgent, $user_data->cookie);
for($i=0;$i<$count;$i++){
		shuffle($pictures);
        shuffle($pictures);
        $photo = ( count($pictures) > 0 ) ? $pictures[rand(0, (count($pictures) - 1))] : NULL;
		$data = $this->GetPostData($photo);
		$post = $this->SendRequest('media/upload/', true, $data, $userAgent, $user_data->cookie);  
	}
	return TRUE;
}

    /*
     *  Açılan bot hesapların çağırma fonksiyonu
     *
     *  @param $count
     *  @param $extension
     *
     *  @return array
     */
    protected function getCookies($count, $extension = "json")
    {
        $data_dir = glob($this->cFolder . "*." . $extension);
        shuffle($data_dir);
        shuffle($data_dir);
        shuffle($data_dir);
        $data_dir = array_slice($data_dir, 0, $count);
        $data = [];
        foreach ($data_dir as $file) {
            $data[] = $file;
        }
        return $data;
    }

    /*
     *  İşlemler için kullanılacak fake ip
     *
     *  @return mixed
     */
    protected function randFakeIP(){

        $hello = rand(40,200);
        $RandIp = $hello ."." . rand(0, 255) . "." . rand(0, 255) . "." . rand(0, 255) . "";

        return $RandIp;
    }


    /*
     *  İşlemler için kullanılacak tarayıcı name
     *
     *  @return mixed
     */
    protected function randUserAgent(){

        $random_version = rand(40, 50);
        return "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/{$random_version}.36 (KHTML, like Gecko) Chrome/" . rand(40,50) . ".0.2357." . rand(180,200) . " Safari/{$random_version}.36";
    }

    /*
     *  İşlemler için parse fonk.
     *
     *  @return mixed
     */
    protected function parseString($first, $end, $data)
    {
        @preg_match_all('/' . preg_quote($first, '/') .
            '(.*?)'. preg_quote($end, '/').'/i', $data, $m);
        return $m[1];
    }

    /*
     *  Belirlenen ismin kullanıcı adına çevrilmesi
     *
     *  @param $string
     *
     *  @return mixed
     */
    public function generateU($str, $options = [])
    {
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
        $defaults = [
            'delimiter' => '',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => true
        ];
        $options = array_merge($defaults, $options);
        $char_map = [
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',
            // Latin symbols
            '©' => '(c)',
            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',
            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',
            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',
            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        ];
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
        $str = trim($str, $options['delimiter']);
        $characters = ['_', '.', ''];
        $randomCharacter = ( count($characters) > 0 ) ? $characters[rand(0, (count($characters) - 1))] : NULL;
        $characters2 = ["_", "", rand(0, 99) . "_", rand(0, 99) . "."];
        $randomFirstCharacter = ( count($characters2) > 0 ) ? $characters2[rand(0, (count($characters2) - 1))] : NULL;
        $firt_or_end = ['first', 'end'];
        $select = ( count($firt_or_end) > 0 ) ? $firt_or_end[rand(0, (count($firt_or_end) - 1))] : NULL;
        if ($select == 'first')
            $str =  $randomFirstCharacter . $str . $randomCharacter;
        else
            $str =  $str . $randomCharacter . rand(0, 999);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }

	    /*
     *  Belirlenen ismin kullanıcı adına çevrilmesi
     *
     *  @param $string
     *
     *  @return mixed
     */
    public function generateUx($str, $options = [])
    {
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
        $defaults = [
            'delimiter' => '',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => true
        ];
        $options = array_merge($defaults, $options);
        $char_map = [
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',
            // Latin symbols
            '©' => '(c)',
            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',
            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',
            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',
            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        ];
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
        $str = trim($str, $options['delimiter']);
        $characters = [''];
        $randomCharacter = ( count($characters) > 0 ) ? $characters[rand(0, (count($characters) - 1))] : NULL;
        $characters2 = ["", rand(0, 99)];
        $randomFirstCharacter = ( count($characters2) > 0 ) ? $characters2[rand(0, (count($characters2) - 1))] : NULL;
        $firt_or_end = ['first', 'end'];
        $select = ( count($firt_or_end) > 0 ) ? $firt_or_end[rand(0, (count($firt_or_end) - 1))] : NULL;
        if ($select == 'first')
            $str =  $randomFirstCharacter . $str . $randomCharacter;
        else
            $str =  $str . $randomCharacter . rand(0, 999);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }
    /*
     *  İşlemlerde kullanılan temel cURL fonksiyonu
     *
     *  @param $params
     *
     *  @return mixed
     */
    protected function cURL($params){
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
	
	public function SendRequest($url, $post, $post_data, $user_agent, $cookies) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/api/v1/'.$url);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if($post) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    $response = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    return array($http, $response);
}

    /*
     *  Fotoğraf işlemlerinde kullanılan cURLFILE fonksiyonu
     *
     *  @param $filename
     *
     *  @return mixed
     */
    protected function cURLValue($filename)
    {
        $image = getimagesize($filename);

        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $image['mime'], $filename);
        }

        // Use the old style if using an older version of PHP
        $value = "@{$filename};filename=" . $filename;
        if ($image['mime']) {
            $value .= ';type=' . $image['mime'];
        }

        return $value;
    }

    /*
     *  Sahte(fake) parametreler oluşturma fonksiyonu
     *
     *  @param $limit
     *
     *  @return mixed
     */
    protected function randomAlpha($limit = 6)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $variable = array();
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $limit; $i++) {
            $n = rand(0, $alphaLength);
            $variable[] = $alphabet[$n];
        }
        return implode($variable);
    }

    /*
     *  Sahte(fake) isim oluşturma fonksiyonu
     *
     *  @param $lang
     *
     *  @return string
     */
    protected function randomUsername()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz';
        $variable = array();
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 5; $i++) {
            $n = rand(0, $alphaLength);
            $variable[] = $alphabet[$n];
        }
        $variable[] = rand(10, 999999);
        return implode($variable);
    }

    /*
     *  Şifre oluşturma fonksiyonu
     *
     *  @param $limit
     *
     *  @return integer
     */
    protected function randomNumbers($limit = 6)
    {
        $alphabet = '0123456789';
        $variable = array();
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $limit; $i++) {
            $n = rand(0, $alphaLength);
            $variable[] = $alphabet[$n];
        }
        return implode($variable);
    }

    /*
     *  Sahte(fake) işlemlerinde kullanılan DEVIDE kaydı oluşturma fonk.
     *
     *  @return mixed
     */
    protected function qsField()
    {
        $return = "";
        for ($i = 1; $i <= 250; $i++)
        {
            $return .= $this->randomNumbers(rand(1,3)) . ',';
        }

        return substr($return, 0, -1);
    }

    /*
     *  Sahte(fake) DEVICE bilgisi oluşturma
     *
     *  @return mixed
     */
    protected function guid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
	public function GenerateGuid() {
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
	public function GenerateSignature($data) {
    return hash_hmac('sha256', $data, '25eace5393646842f0d0c3fb2ac7d3cfa15c052436ee86b5406a8433f54d24a5');
}
	public function GetPostData($filename) {
    if(!$filename) {
        echo "The image doesn't exist ".$filename;
    } else {
        $post_data = array('device_timestamp' => time(), 
                            'photo' => '@'.$filename);
        return $post_data;
    }
}


//TEMPMAİL

	/**
	 * Doing request calls for api .
	 */
	public function call($request, $addressid = null) {
		switch ($request) {
			case 'domains' :
				$target = 'http://api.temp-mail.ru/request/domains/format/json';
				break;
			
			default :
				$target = 'http://api.temp-mail.ru/request/' . $request . '/id/' . $addressid . '/format/json';
				break;
		}
		$handle = curl_init ( $target );
		curl_setopt ( $handle, CURLOPT_HEADER, false );
		curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
		$httpCode = curl_getinfo($handle , CURLINFO_HTTP_CODE); // this results 0 every time
		$result = curl_exec ( $handle );
		curl_close ( $handle );
		return json_decode ( $result, true );
	}
	
	/**
	 * Setting an email address with a predefined address or a random address .
	 */
	public function setmail($email = null) {
		if (is_null ( $email )) {
			$domains = $this->domains ();
			$domain = $domains [array_rand ( $domains )];
			$email = $this->generateRandomString () . $domain;
		}
		
		$this->emailaddress = $email;
	}
	
	/**
	 * Requests for inbox . $email is optional if there is already an email address .
	 */
	public function getmails($email = null) {
		$email = ! is_null ( $email ) ? $email : $this->emailaddress;
		$mails = $this->call ( 'mail', md5 ( $email ) );
		return $mails;
	}
	
	/**
	 * Requests for sources . $email is optional if there is already an email address .
	 */
	public function getsources($email = null) {
		$email = ! is_null ( $email ) ? $email : $this->emailaddress;
		$sources = $this->call ( 'source', md5 ( $email ) );
		return $sources;
	}
	
	/**
	 * Deletes an email by the id assigned for each email .
	 */
	public function delete($emailid) {
		$delete = $this->call ( 'delete', md5 ( $emailid ) );
		
		if ($delete ['result'] != 'success') {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Requests for available domains .
	 */
	public function domains() {
		$result = $this->call ( 'domains' );
		return $result;
	}
	
	/**
	 * Generating a random string to make an email address .
	 * http://stackoverflow.com/a/4356295
	 */
	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen ( $characters );
		$randomString = '';
		for($i = 0; $i < $length; $i ++) {
			$randomString .= $characters [rand ( 0, $charactersLength - 1 )];
		}
		return $randomString;
	}
	public function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}
}

