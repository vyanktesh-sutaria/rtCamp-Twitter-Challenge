<?php
ini_set("display_errors",1);
session_start();
require "autoload.php";
include_once "googleloginfunc.php";

use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'NvrtYx5XKgHiGKROJBpA8i921');
define('CONSUMER_SECRET', 'PmtPCnSaZamJfqzBkz46O7pfGcEIetVxzJVtZ2VdZLDCZW3dDH');
define('OAUTH_CALLBACK', 'http://local.rtdemo.com/callback.php');

$user = $flwdwn = null;

if(isset($_REQUEST['btnlogout']))
{
	$user=null;
	$tweets=null;
	$connection=null;
	$request_token=null;
	session_destroy();
	$url=null;
}

if(!isset($_SESSION['access_token']))
{
	$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token',array('oauth_callback'=>OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url=$connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token'] ));
}
if($user=="" && isset($_SESSION['access_token']))
{

	$access_token = $_SESSION['access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,$access_token['oauth_token'],$access_token['oauth_token_secret']);
	$user = $connection->get("account/verify_credentials");

	$tweets=$connection->get("statuses/user_timeline",['count'=>10]);

	$follower=$connection->get('followers/list',["count"=>200]);
	$follower_name = array();
	if(isset($follower->users))
	{
		foreach ($follower->users as $f) {
			array_push($follower_name, ["label"=>$f->name,"value"=>$f->screen_name,"img"=>$f->profile_image_url_https]);
		}
	}

	$follower_name = json_encode($follower_name);
}

if(isset($_REQUEST['flwdwn']))
{
	$flwdwn=$connection->get('users/lookup',["screen_name"=>$_REQUEST['flwdwn']]);

	if(isset($flwdwn->errors))
	{
		echo "No User Found";
	}
	else
	{
		echo "Success";
		$_SESSION['flwdwn']=$flwdwn[0]->screen_name;
	}
}

if(isset($_REQUEST['format']))
{
	$file = fopen("cron.txt","a");
	$email = $_REQUEST['email'];
	$format = $_REQUEST['format'];
	$str = "*/15 * * * * php ".getcwd()."/flwdwn.php ".$format." -1 ".$_SESSION['flwdwn']." ".$email." \n";

	$result = fwrite($file,$str);
	if($result == true)
	{
		$cmd = "sudo bash ".getcwd()."/cron.sh";
		shell_exec($cmd);
	}
}

if(isset($_REQUEST['flwsrch']))
{
	$tweets_fw=$connection->get("statuses/user_timeline",['count'=>10,'screen_name'=>$_REQUEST['flwsrch']]);

	if($tweets_fw == null)
	{
		?>
		<li>
			<div class="col-xs-7" style="background-color: white;border-radius: 10px;height: 4em">
				<p class="text-center" style="font-size: 1.2em;padding: 1em;">No Tweets are available to display</p>
			</div>
		</li>
		<?php
	}
	else
	{
		foreach($tweets_fw as $t)
		{
		?>
		  <li>	<div class="EmbeddedTweet EmbeddedTweet--edge EmbeddedTweet--mediaForward media-forward js-clickToOpenTarget js-tweetIdInfo tweet-InformationCircle-widgetParent">

	<article class="MediaCard
	           MediaCard--mediaForward
	           
	           customisable-border" data-scribe="component:card" dir="ltr">
	  <div class="MediaCard-media">
	            
	<a class="MediaCard-borderOverlay" href="<?php if(isset($t->entities->media)) {echo $t->entities->media[0]->url;} else {echo "";}?>" role="presentation" tabindex="-1" title="View image on Twitter">
	  <span class="u-hiddenVisually">View image on Twitter</span>
	</a>


	    <div class="MediaCard-widthConstraint js-cspForcedStyle" style="max-width: 600px" data-style="max-width: 600px" data-csp-fix="true">
	      <div class="MediaCard-mediaContainer js-cspForcedStyle" style="padding-bottom: 56.3333%" data-style="padding-bottom: 56.3333%" data-csp-fix="true">
	          <a class="MediaCard-mediaAsset
	                    NaturalImage
	" href="<?php if(isset($t->entities->media)) {echo $t->entities->media[0]->url;} else {echo "";}?>" data-scribe="element:photo"><img class="NaturalImage-image" data-srcset="https%3A%2F%2Fpbs.twimg.com%2Fmedia%2FBm54nBCCYAACwBi.jpg%3Alarge 960w,https%3A%2F%2Fpbs.twimg.com%2Fmedia%2FBm54nBCCYAACwBi.jpg 600w,https%3A%2F%2Fpbs.twimg.com%2Fmedia%2FBm54nBCCYAACwBi.jpg%3Asmall 340w" width="600" height="338" title="View image on Twitter" alt="View image on Twitter" src="<?php if(isset($t->entities->media)) {echo $t->entities->media[0]->media_url;} else {echo "assets/images/twitter.jpg";}?>"></a>
	      </div>
	    </div>
	  </div>
	</article>

	    <div class="EmbeddedTweet-tweet">
	<blockquote class="Tweet h-entry js-tweetIdInfo subject expanded
	                   is-deciderHtmlWhitespace" cite="https://twitter.com/Interior/status/463440424141459456" data-tweet-id="463440424141459456" data-scribe="section:subject">
	  <div class="Tweet-header u-cf">
	    
	<div class="TweetAuthor " data-scribe="component:author">
	  <a class="TweetAuthor-link Identity u-linkBlend" data-scribe="element:user_link" href="https://twitter.com/<?php echo $t->user->screen_name?>" aria-label="US Dept of Interior (screen name: <?php echo $t->user->screen_name?>)">
	    <span class="TweetAuthor-avatar Identity-avatar">
	      <img class="Avatar Avatar--edge" data-scribe="element:avatar" data-src-2x="https://pbs.twimg.com/profile_images/432081479/DOI_LOGO_bigger.jpg" alt="" data-src-1x="https://pbs.twimg.com/profile_images/432081479/DOI_LOGO_normal.jpg" src="<?php echo $t->user->profile_image_url_https;?>">
	    </span>
	    <span class="TweetAuthor-name Identity-name customisable-highlight" title="<?php echo $t->user->name?>" data-scribe="element:name"><?php echo $t->user->name?></span>
	    <?php 
	    	if($t->user->verified == "true")
	    	{
	    ?>
	    <span class="TweetAuthor-verifiedBadge" data-scribe="element:verified_badge"><div class="Icon Icon--verified " aria-label="Verified Account" title="Verified Account" role="img"></div>
	<b class="u-hiddenVisually">✔</b></span>
		<?php
			}
		?>
	    <span class="TweetAuthor-screenName Identity-screenName" title="@<?php echo $t->user->screen_name?>" data-scribe="element:screen_name" dir="ltr">@<?php echo $t->user->screen_name?></span>
	  </a>
	</div>

	  </div>
	  <div class="Tweet-body e-entry-content" data-scribe="component:tweet">
	    
	    <p class="Tweet-text e-entry-title" lang="en" dir="ltr"><?php echo $t->text;?></p>


	    <div class="Tweet-metadata dateline">
	      

	<time class="dt-updated" datetime="" pubdate="" title="">
		<?php 
			$date = explode(' ',$t->created_at); 
			$date[3]=explode(":",$date[3]);
			echo $date[2]." ".$date[1]." ".$date[5]." - ".$date[3][0].":".$date[3][1];
		?>
	</time>
	    </div>

	</blockquote>
	</div>
	  </div></li>
	  <?php
		}
	}
}

?>