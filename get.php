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
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
$cal = new Google_CalendarService($client);
//echo $_SESSION['calendar'];
 $client->setAccessToken($_SESSION['token']);
if ($client->getAccessToken()) {
//echo $_SESSION['calendar'];

  $calList = $cal->calendarList->listCalendarList();
  //print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
$calendarId = $_SESSION['calendar'];
$events = $cal->events->listEvents($calendarId);
//$minCheck = date(DATE_ATOM, mktime(0, 0, 0, 1, 1, date("Y") -1));
//$maxCheck = date(DATE_ATOM, mktime(0, 0, 0, 12, 31, date("Y") +1));
$minCheck = date(DATE_ATOM, strtotime($_GET["start"]));
$maxCheck = date(DATE_ATOM, strtotime($_GET["end"]));
function getStringBetween($str,$from,$to)
{
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
}


$totalResult=array();
$a=0;
do{
if(isset($result['nextPageToken'])){
   $result= $cal->events->listEvents($calendarId, array('singleEvents' => 'true', 'pageToken' => $result['nextPageToken'], 'timeMin' => $minCheck, 'timeMax' => $maxCheck));

}else{
   $result= $cal->events->listEvents($calendarId, array('singleEvents' => 'true', 'timeMin' => $minCheck, 'timeMax' => $maxCheck));
}
array_push($totalResult, $result['items']);
$a++;
//}while($a==3);
}while(isset($result['nextPageToken']) && !empty($result['nextPageToken']));
//echo sizeof($totalResult);
//$events['items'] = $totalResult[1];
//echo sizeof($events['items']);
$json = json_encode($totalResult);
// echo $json;
// return;
//$data = json_decode($json);

//echo json_encode($events['items'][$i]);
//echo $json;  //json_decode(json_encode(
$finResult=array();
for ($j=0; isset($totalResult[$j]);$j++){
	$events['items'] = $totalResult[$j];

	for($i=0; isset($events['items'][$i]); $i++){
		
		$id = ($j*sizeof($totalResult[$j])+$i+1)." : ";
		$strDes = $events['items'][$i]['description'];

		if (strpos($strDes,'START') !== false) { // Should take care of the Notifications.
		    break;
		}

		$number = getStringBetween($strDes, ":", "(");
		$type = getStringBetween($strDes, "(", "call");
		$time_seconds = 0;
		$summary = $events['items'][$i]['summary'];
		$start = $events['items'][$i]['start']['dateTime'];
		$end = $events['items'][$i]['end']['dateTime'];
		
		

		if (!strcmp($type,"missed ")==0){
			$str_time = substr($strDes, -8);
			//$str_time = "23:12:95";

			$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

			sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

			$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
			
		}
		//echo '{$id, $number, $type, $time_seconds, $start, $end, $summary}';
		$item = array(
			"id" => $id,
			"number" => $number,
			"type" => $type,
			"time" => $time_seconds,
			"start" => $start,
			"end" => $end,
			"summary" => $summary);
		
		array_push($finResult, $item);
			
		//echo $dur;
		//echo "___ ";
		//echo $strDes;
		//print "<br/>";

	}
}
echo json_encode($finResult);
}else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}