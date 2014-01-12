<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_CalendarService.php';
require_once 'src/contrib/Google_Oauth2Service.php';
session_start();
$client = new Google_Client();
$client->setApplicationName("Google Calendar PHP Starter Application");

$client->setClientId('776685009882-v1kcqutjjt9k7h1e2qobjugqrgn9srel.apps.googleusercontent.com');
$client->setClientSecret('sDK-e4GvFjp58ZTx23c4FQjz');
//echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/plus.me',
        'https://www.googleapis.com/auth/calendar'));  
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
  unset($_SESSION['email']);
}
if (isset($_GET['calendar'])) {
  $_SESSION['calendar']=$_GET['calendar'];
}
if (isset($_GET['change'])) {
  unset($_SESSION['calendar']);
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
  $oauth2 = new Google_Oauth2Service($client);
  $user = $oauth2->userinfo->get();
  $_SESSION['email'] = $user['email'];

//print_r($user['email']);
  if(isset($_SESSION['calendar'])){
    echo("<script>window.location = 'home.php';</script>");
  }
  $calList = $cal->calendarList->listCalendarList();
  //print_r($_SESSION['token'], true);
  //print(json_encode($client->getEmail()));

  $b = $calList['items'][0]['id'];

    $t = '<form ><table>
  <tr>
    <td><select name=calendar class="form-control" >';
   foreach ($calList['items'] as $a)
      $t.= '<option value='.$a['id'].'>'.$a['summary'].'</option>';
  $t.= '</select></td>
    <td><input class="btn btn-default" type=submit></input></td>
  </tr>
</table></form>'; 
  //echo '<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
 // <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>';

   echo displ("Choose the calendar ".$t);
//echo $a;
$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  //<INPUT type="submit" class="submit button" value="Google">
  //<a href="$authUrl"> <button class='submit button'>Google</button></a>
  $t = "<a href=$authUrl> <button class='btn btn-primary btn-lg'>Connect Google</button></a>";
  //print "<a class='login' href='$authUrl'>Connect Me!</a>";
  echo displ($t);
}
function displ($arg){
  $email=$_SESSION['email'];
  $str = <<<EOF

  <style>
  .content {
       display: table-cell;
    text-align: center;
    vertical-align: middle;
    }
    html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    display: table
}
  </style>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
    </script>
     <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js">
    </script>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Call Log Analytics</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">How it works</a></li>
      <li><a href="#">About Us</a></li>
    </ul>
    
    <ul class="nav navbar-nav navbar-right" style="
    padding-right: 20;
">
      
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">$email<b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="#">Calendar</a></li>
          <li class="divider"></li>
          <li><a href="./?logout">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
    
     

    
<div style=' width:600px; height:300px; position:absolute; left:46%; top:50%; margin-left:-100px; margin-top:-100px'>$arg</div>
   
  </body>

EOF;
return $str;
}