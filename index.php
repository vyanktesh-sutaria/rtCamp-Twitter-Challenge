<?php 

session_start();
require "autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'NvrtYx5XKgHiGKROJBpA8i921');
define('CONSUMER_SECRET', 'PmtPCnSaZamJfqzBkz46O7pfGcEIetVxzJVtZ2VdZLDCZW3dDH');
define('OAUTH_CALLBACK', 'http://local.rtdemo/callback.php');

//echo "hello World";
$user = null;

if(!isset($_SESSION['access_token']))
{
	$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token',array('oauth_callback'=>OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url=$connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token'] ));
	//echo $url;
	//header ('Location : $url');
}
else
{
	$access_token = $_SESSION['access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,$access_token['oauth_token'],$access_token['oauth_token_secret']);
	$user = $connection->get("account/verify_credentials");
	//echo $user->status->text;
	echo "<pre>";
	print_r($user);
	echo "<pre>";
}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php 
	if($user == null)
	{
		?>
		<a href="<?php echo $url?>">Log in With Twitter</a>
		<?php
	}
	else
	{
		echo "<h1>Tweets List</h1>";

		$tweets_result=$connection->get("statuses/user_timeline",['count'=>1]);
		print_r($tweets_result);

		echo "<h1>Followers List</h1>";
		$follower=$connection->get('followers/list');
		print_r($follower->users);

		$follower_name = array();
		foreach ($follower->users as $f) {
			array_push($follower_name, $f->name);
		}

		print_r($follower_name);

		echo "<h1>Followers List (JSON)</h1>";
		echo json_encode($follower_name);

		echo "<h1>Followers Tweets</h1>";

		$cnt=1;
		$tweets[]=$connection->get("statuses/user_timeline",['count'=>100,'screen_name'=>'Zahurafzal']);
		foreach ($tweets as $p) {
			foreach ($p as $k) {
				echo $cnt .":- ".$k->text."<br>";
				$cnt++;
			}
		}
	}
	?>
</body>
</html>