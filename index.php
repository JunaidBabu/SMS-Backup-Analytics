<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_CalendarService.php';
session_start();

$client = new Google_Client();
$client->setApplicationName("Google Calendar PHP Starter Application");

$client->setClientId('776685009882-v1kcqutjjt9k7h1e2qobjugqrgn9srel.apps.googleusercontent.com');
$client->setClientSecret('sDK-e4GvFjp58ZTx23c4FQjz');
//echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
//echo "Waassaap";
// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_oauth2_redirect_uri');
// $client->setDeveloperKey('insert_your_developer_key');
$cal = new Google_CalendarService($client);
if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
  unset($_SESSION['calendar']);
}
if (isset($_GET['calendar'])) {
  $_SESSION['calendar']=$_GET['calendar'];
}

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
	$_SESSION['token'] = $client->getAccessToken();
	if(isset($_SESSION['calendar'])){
		echo("<script>window.location = 'home.php';</script>");
	}
  $calList = $cal->calendarList->listCalendarList();
  $b = $calList['items'][0]['id'];

  	echo '<form ><select name=calendar>';
   foreach ($calList['items'] as $a)
  		echo '<option value='.$a['id'].'>'.$a['summary'].'</option>';
	echo '</select><input type=submit></form>';
//echo $a;
$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}