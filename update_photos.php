<?php
set_time_limit(0);
error_reporting(E_ALL);
include "lib/c2bot.php";

if ($_GET["g"] == "f"){
$pictures = glob("botImages/f/*.{jpg}", GLOB_BRACE);
}else{
$pictures = glob("botImages/m/*.{jpg}", GLOB_BRACE);	
}
$lines = read_file('cookies/sifre.txt');
foreach ($lines as $line){
$parcala = explode(':',$line);
$username = $parcala[0];
$username = preg_replace('/\s+/', '', $username);
$password = $parcala[1];
$password = preg_replace('/\s+/', '', $password);
$settings = [
    '_pictures'         => $pictures,
];
$boting = new c2bot($settings);
$boting->change_pp($username,$password);
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