<?php
require_once 'vendor/autoload.php';
require_once 'controller.php';


define('CLIENT_SECRET_PATH', __DIR__.'/vendor/client_secret.json');
$json = json_decode(file_get_contents(CLIENT_SECRET_PATH), true);
define('REDIRECT_URI', $json['web']['redirect_uris'][1]);

// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/sheets.googleapis.com-php-quickstart.json
define('SCOPES', implode(' ', array(
  Google_Service_Sheets::SPREADSHEETS_READONLY,
  	'https://www.googleapis.com/auth/drive',
    'https://www.googleapis.com/auth/drive.file',
    'https://www.googleapis.com/auth/spreadsheets')
));

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */


	$client = new Google_Client();
	$client->setScopes(SCOPES);
	$client->setAuthConfig(CLIENT_SECRET_PATH);
	$client->setAccessType('offline');
	$client->setRedirectUri(REDIRECT_URI);


if(isset($_GET['code']))
{
	$accessToken = null;
  // Load previously authorized credentials from a file.
  // $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (isset($_COOKIE['credentials'])) {
    $accessToken = json_decode($_COOKIE['credentials']);
  } else {
    // Request authorization from the user.
    $authCode = trim($_GET['code']);

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    // Store the credentials to disk.
    // if(!file_exists(dirname($credentialsPath))) {
    //   mkdir(dirname($credentialsPath), 0700, true);
    // }
    // file_put_contents($credentialsPath, json_encode($accessToken));
    // printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken(json_encode($accessToken));

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    //file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    //setcookie("credentials",json_encode($client->getAccessToken()));
  }

$td_t=array();

if(json_decode($_REQUEST['state'])->data == "flw")
{
	define("ID", "FollowerName");
	define("VALUE", "FollowerScreenName");
	define("FILE_NAME", $_SESSION['flwdwn']);
	$cursor=-1;
	
	for ($i=1; $i<=15,$cursor!=0; $i++) { 
	$flwdwn=$connection->get('followers/list',["screen_name"=>$_SESSION['flwdwn'],"count"=>200,"cursor"=>$cursor]);
	if(!isset($flwdwn->users))
	{
	  break;
	}
	foreach ($flwdwn->users as $f) {
	  $tmp=new stdClass;
	  $tmp->id_str=$f->name;
	  $tmp->text=$f->screen_name;
	  array_push($td_t, [$tmp]);
	}
	$cursor = $flwdwn->next_cursor;
	}
}
else if(json_decode($_REQUEST['state'])->data == "tweet")
{
  define("ID", "TweetID");
  define("VALUE", "Tweet");
  define("FILE_NAME", $user->name);

  if(count($td_t)==0)
  {
    for ($i=1; $i <= 16; $i++)
    {
      $td=array();
      $td = $connection->get("statuses/user_timeline",['count'=>200,'exclude_replies'=>'true','include_rts'=>'true','contributor_details'=>'false','page'=>$i]);
      if(count($td)!=0)
      {
        array_push($td_t, $td);
      }
    }
  }
}

$service = new Google_Service_Sheets($client);

$requestBody = new Google_Service_Sheets_Spreadsheet();
$properties = new Google_Service_Sheets_SpreadsheetProperties();
$properties->setTitle(FILE_NAME);
$requestBody->setProperties($properties);

$response = $service->spreadsheets->create($requestBody);

$options = array('valueInputOption' => 'RAW');


$values = array([ID,VALUE]);
foreach ($td_t as $rows) {
	foreach ($rows as $row) {
		array_push($values, [$row->id_str,$row->text]);
	}
}

$cnt = 200*count($td_t);
$range='A1:B'.$cnt;

$body   = new Google_Service_Sheets_ValueRange(['values' => $values]);
 
$result = $service->spreadsheets_values->update($response->spreadsheetId, $range, $body, $options);

header("location:./");
}

?>