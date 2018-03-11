<?php
error_reporting(E_ALL);
session_start();

/* ENABLE FOR LOGIN 
if($_SESSION['login']!=md5('xianthi')){
if($_POST){
$pass=$_POST["pass"];
$_SESSION['login']=md5($pass);
header('Location: c2bot.php');
}
	echo "
<!--
Only those who want to see it....
//-->
<h1>Not Found</h1> 
<p>The requested URL was not found on this server.</p> 
<p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request.</p> 
<hr> 
<address>Apache/2.2.22 (Unix) mod_ssl/2.2.22 OpenSSL/1.0.0-fips mod_auth_passthrough/2.1 mod_bwlimited/1.4 FrontPage/5.0.2.2635 Server at Port 80</address> 
    <style> 
        input { margin:0;background-color:#fff;border:1px solid #fff; } 
    </style> 
    <pre align=center> 
    <form method=post> 
    <input type=password name=pass> 
    </form></pre>
";
	exit;
}*/
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<title>c2Bot</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font awesome -->
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
$(document).ready(function () {
$('#bot').on('submit', function(event) {
  // Stop form from submitting normally
  event.preventDefault();
 
  // Get some values from elements on the page:
  var $form = $( this ),
    cnt = $form.find( "input[name='count']" ).val(),
	accs = $form.find("textarea[name='accounts']").val(),
	bio = $form.find("textarea[name='ex_bio']").val(),
    url = "c2.php";
  for (i = 0; i < parseInt(cnt); i++) { 
  // Send the data using post
  var posting = $.post( url, { count: cnt,accounts:accs,ex_bio:bio } );
   }
  // Put the results in a div
  posting.done(function( data ) {
	  console.log(data);
    $( "#result" ).empty().append( "request success." );
	$("html, body").animate({ scrollTop: 0 }, "slow");
  });

});


$('#update_bio').on('submit', function(event) {
 console.log("form post ediliyor.");
  // Stop form from submitting normally
  event.preventDefault();
 
  // Get some values from elements on the page:
  var $form = $( this ),
	bio = $form.find("textarea[name='ex_biox']").val(),
    url = "update_bio.php";
 
  // Send the data using post
  var posting = $.post( url, { ex_bio:bio} );
 
  // Put the results in a div
  posting.done(function( data ) {
	  console.log(data);
    $( "#result" ).empty().append( "request success." );
	$("html, body").animate({ scrollTop: 0 }, "slow");
  });
});

$( "#login_bots" ).click(function() {
 var url = "login_bots.php";
 var getting = $.get(url);
 getting.done(function(data){
	 console.log(data);
 $( "#result" ).empty().append( "request success." );
 $("html, body").animate({ scrollTop: 0 }, "slow");
 });
});

$( "#send_story" ).click(function() {
 var url = "sendstory.php";
 var getting = $.get(url);
 getting.done(function(data){
	 console.log(data);
 $( "#result" ).empty().append( "request success." );
 $("html, body").animate({ scrollTop: 0 }, "slow");
 });
});


});
</script>
</head>
<body>
<div class="container">
<div class="row">
<div id="result"></div>
</div>

<div class="row">
<div class="page-header">
<h1>Create Bot</h1>
</div>
<form id="bot">
<div class="form-group">
<label> Bot Number : </label><input class="form-control" type="text" value="100" name="count" />
</div>
<div class="form-group">
<label> Follow : </label><br><textarea class="form-control" rows="3" name="accounts"></textarea>
<small class="form-text text-muted">Type in the lower line as a row.</small>
</div>
<div class="form-group">
<label> Bot Extra Bio : </label><br><textarea class="form-control" rows="3" name="ex_bio"></textarea>
<small class="form-text text-muted">Type in the lower line as a row.</small>
</div>
<input type="submit" value="Gönder">
</form>
</div>

<div class="row">
<div class="page-header">
<h1>Lets Online</h1>
</div>
<input id="login_bots" name="login_bots" type="button" value="Do it!" />
</div>

<div class="row">
<div class="page-header">
<h1>Send Story</h1>
</div>
<input id="send_story" name="send_story" type="button" value="Send!" />
</div>


<div class="row">
<div class="page-header">
<h1>Update Bio</h1>
</div>
<form id="update_bio">
<div class="form-group">
<label> New Extra Bio: </label><br><textarea class="form-control" rows="3" name="ex_biox"></textarea>
<small class="form-text text-muted">Type in the lower line as a row.</small>
</div>
<input type="submit" value="Gönder">
</form>
</div>




</div>
</body>
</html>
