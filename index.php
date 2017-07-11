<?php 

session_start();
require "autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'NvrtYx5XKgHiGKROJBpA8i921');
define('CONSUMER_SECRET', 'PmtPCnSaZamJfqzBkz46O7pfGcEIetVxzJVtZ2VdZLDCZW3dDH');
define('OAUTH_CALLBACK', 'http://local.rtdemo/callback.php');

//echo "hello World";

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
	?>
</body>
</html>