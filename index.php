<?php 
require 'controller.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
	<!-- bxSlider CSS file -->
	<link href="plugin/css/jquery.bxslider.css" rel="stylesheet" />
	<link rel="stylesheet" href="plugin/css/tweet.24e0cef9279c9cccaf5e72165aa3517a.light.ltr.css">
	<!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">    
    <!--Import jQuery-ui CSS-->
    <link rel="stylesheet" href="plugin/css/jquery-ui.css">

    <script src="./plugin/js/jquery-1.9.1.js"></script>
	<script src="plugin/js/bootstrap.min.js"></script>
	<!--Import jQuery-ui js -->
	<script src="plugin/js/jquery-ui.js"></script>
	<!-- bxSlider Javascript file -->
	<script src="plugin/js/jquery.bxslider.js"></script>

	<style type="text/css">
		.bx-viewport{
			height: 510px !important;
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function(){

			var slider = $('.bxslider').bxSlider();

			$("#followersrch").autocomplete({
        		source:<?php echo $follower_name; ?>,
        		focus: function( event, ui ) {
	                $( "#followersrch" ).val( ui.item.label );
                    return false;
               	},
               	select: function( event, ui ) {
               		$( "#followersrch" ).val( ui.item.label );
					//$(".bxslider").load("controller.php?flwsrch="+ui.item.value);
					// $('.bxslider').bxSlider();
					$.ajax({
						url:"controller.php?flwsrch="+ui.item.value,
						type:"post",
						success:function(response){
							slider.destroySlider();
							$(".bxslider").html(response).bxSlider();
						},
						failure:function(response){
							console.log(response);
						}
					});
					return false;
               }
        	});
		});
	</script>
</head>
<body>
<form method="post">
	<?php 
	if($user == null)
	{
		?>
		<div class="container">
			<div class="row">
				<center>
					<a href="<?php echo $url?>"><img src="images/twitter_login.png" height="50px" /></a>
				</center>
			</div>
		</div>
		<?php
	}
	else
	{
		?>
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-6 col-lg-8 col-md-offset-3 col-lg-offset-2" style="margin-top: 2em;">
					<input type="text" class="form-control" id="followersrch" autocomplete="off" />
				</div>
				<input type="submit" name="btnlogout" class=" col s1 m1 l1 btn btn-danger" style="float: right;margin-top: 2em;" value="Logout" />
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="row" style="margin-top: 3em;">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-sm-12 col-md-6 col-lg-2" style="float: right; margin: 0 7em 2em 0;">
						<div class = "btn-group">
							<button type = "button" class = "btn btn-primary dropdown-toggle" data-toggle = "dropdown">
								Download Tweets 
								<span class = "caret"></span>
							</button>
							
							<ul class = "dropdown-menu">
								<li><a href = "download.php?format=csv">csv format</a></li>
								<li><a href = "download.php?format=xls">excel format</a></li>
								<li>
								<a href="<?php echo $client->createAuthUrl(); ?>">Google SpreadSheet<?php if($client->getAccessToken() == null){ echo "(Login Required)";} ?></a>
								<?php
								// if(!isset($_COOKIE['credentials']))
								// {
								?>
								<?php
								// }
								// else
								// {
								?>
								<!-- <a href = "download.php?format=glss">google spreadsheet</a> -->
								<?php
								// }
								?>
								</li>
								<li><a href = "download.php?format=xml">XML format</a></li>
								<li><a href = "download.php?format=json">json format</a></li>
							</ul>

						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="col-sm-12 col-md-6 col-lg-8 col-md-offset-3 col-lg-offset-2">
							<ul class="bxslider" style="margin: 1em 12em;">
	<?php
	foreach($tweets as $t)
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
" href="<?php if(isset($t->entities->media)) {echo $t->entities->media[0]->url;} else {echo "";}?>" data-scribe="element:photo"><img class="NaturalImage-image" data-srcset="https%3A%2F%2Fpbs.twimg.com%2Fmedia%2FBm54nBCCYAACwBi.jpg%3Alarge 960w,https%3A%2F%2Fpbs.twimg.com%2Fmedia%2FBm54nBCCYAACwBi.jpg 600w,https%3A%2F%2Fpbs.twimg.com%2Fmedia%2FBm54nBCCYAACwBi.jpg%3Asmall 340w" width="600" height="338" title="View image on Twitter" alt="View image on Twitter" src="<?php if(isset($t->entities->media)) {echo $t->entities->media[0]->media_url;} else {echo "images/twitter.jpg";}?>"></a>
      </div>
    </div>
  </div>
</article>

  
  
  


    <div class="EmbeddedTweet-tweet">
<blockquote class="Tweet h-entry js-tweetIdInfo subject expanded
                   is-deciderHtmlWhitespace" cite="https://twitter.com/Interior/status/463440424141459456" data-tweet-id="463440424141459456" data-scribe="section:subject">
  <div class="Tweet-header u-cf">
    <!-- <div class="Tweet-brand u-floatRight">
      <span class="u-hiddenInNarrowEnv">
<a class="FollowButton FollowButton--edge follow-button profile" data-scribe="component:followbutton" href="https://twitter.com/Interior" role="button" title="Follow US Dept of Interior on Twitter"><span class="FollowButton-bird"><div class="Icon Icon--twitter " aria-label="" title="" role="presentation"></div>
</span> Follow</a>
</span>
      <span class="u-hiddenInWideEnv"><a href="https://twitter.com/download" data-scribe="element:logo"><div class="Icon Icon--twitter " aria-label="Get Twitter app" title="Get Twitter app" role="img"></div>
</a></span>
    </div> -->
    
<div class="TweetAuthor " data-scribe="component:author">
  <a class="TweetAuthor-link Identity u-linkBlend" data-scribe="element:user_link" href="https://twitter.com/<?php echo $user->screen_name?>" aria-label="US Dept of Interior (screen name: <?php echo $user->screen_name?>)">
    <span class="TweetAuthor-avatar Identity-avatar">
      <img class="Avatar Avatar--edge" data-scribe="element:avatar" data-src-2x="https://pbs.twimg.com/profile_images/432081479/DOI_LOGO_bigger.jpg" alt="" data-src-1x="https://pbs.twimg.com/profile_images/432081479/DOI_LOGO_normal.jpg" src="<?php echo $user->profile_image_url;?>">
    </span>
    <span class="TweetAuthor-name Identity-name customisable-highlight" title="<?php echo $user->name?>" data-scribe="element:name"><?php echo $user->name?></span>
    <?php 
    	if($user->verified == "true")
    	{
    ?>
    <span class="TweetAuthor-verifiedBadge" data-scribe="element:verified_badge"><div class="Icon Icon--verified " aria-label="Verified Account" title="Verified Account" role="img"></div>
<b class="u-hiddenVisually">✔</b></span>
	<?php
		}
	?>
    <span class="TweetAuthor-screenName Identity-screenName" title="@<?php echo $user->screen_name?>" data-scribe="element:screen_name" dir="ltr">@<?php echo $user->screen_name?></span>
  </a>
</div>

  </div>
  <div class="Tweet-body e-entry-content" data-scribe="component:tweet">
    
    <p class="Tweet-text e-entry-title" lang="en" dir="ltr"><?php echo $t->text;?><!-- Sunsets don't get much better than this one over <a href="https://twitter.com/GrandTetonNPS" class="PrettyLink profile customisable h-card" dir="ltr" data-mentioned-user-id="44991932" data-scribe="element:mention"><span class="PrettyLink-prefix">@</span><span class="PrettyLink-value">GrandTetonNPS</span></a>. <a href="https://twitter.com/hashtag/nature?src=hash" data-query-source="hashtag_click" class="PrettyLink hashtag customisable" dir="ltr" rel="tag" data-scribe="element:hashtag"><span class="PrettyLink-prefix">#</span><span class="PrettyLink-value">nature</span></a> <a href="https://twitter.com/hashtag/sunset?src=hash" data-query-source="hashtag_click" class="PrettyLink hashtag customisable" dir="ltr" rel="tag" data-scribe="element:hashtag"><span class="PrettyLink-prefix">#</span><span class="PrettyLink-value">sunset</span></a>  --></p>


    <div class="Tweet-metadata dateline">
      

<time class="dt-updated" datetime="" pubdate="" title="">
	<?php 
		$date = explode(' ',$t->created_at); 
		$date[3]=explode(":",$date[3]);
		echo $date[2]." ".$date[1]." ".$date[5]." - ".$date[3][0].":".$date[3][1];
		//print_r($date)
	?>
</time>
    </div>


    <!-- <ul class="Tweet-actions" data-scribe="component:actions" role="menu" aria-label="Tweet actions">
      <li class="Tweet-action">
<a class="TweetAction TweetAction--replyEdge web-intent" href="https://twitter.com/intent/tweet?in_reply_to=463440424141459456" data-scribe="element:reply"><div class="Icon Icon--reply TweetAction-icon Icon--replyEdge" aria-label="Reply" title="Reply" role="img"></div>
</a>
</li>
      <li class="Tweet-action">
<a class="TweetAction TweetAction--retweetEdge web-intent" href="https://twitter.com/intent/retweet?tweet_id=463440424141459456" data-scribe="element:retweet"><div class="Icon Icon--retweet TweetAction-icon Icon--retweetEdge" aria-label="Retweet" title="Retweet" role="img"></div>
    <span class="TweetAction-stat" data-scribe="element:retweet_count" aria-hidden="true">2,528</span>
    <span class="u-hiddenVisually">2,528 Retweets</span>
</a>
</li>
      <li class="Tweet-action">
<a class="TweetAction TweetAction--heartEdge web-intent" href="https://twitter.com/intent/like?tweet_id=463440424141459456" data-scribe="element:heart"><div class="Icon Icon--heart TweetAction-icon Icon--heartEdge" aria-label="Like" title="Like" role="img"></div>
    <span class="TweetAction-stat" data-scribe="element:heart_count" aria-hidden="true">3,584</span>
    <span class="u-hiddenVisually">3,584 likes</span>
</a>
</li>
    </ul>
  </div> -->
</blockquote>
</div>
  </div></li>
  <?php
	}
  ?>
				</div>
			</div>
		</div>
		<!-- // echo "<h1>Followers List</h1>";
		// $follower=$connection->get('followers/list');
		// print_r($follower->users);

		// $follower_name = array();
		// foreach ($follower->users as $f) {
		// 	array_push($follower_name, $f->name);
		// }

		// print_r($follower_name);

		// echo "<h1>Followers List (JSON)</h1>";
		// echo json_encode($follower_name);

		// echo "<h1>Followers Tweets</h1>";

		// $cnt=1;
		// $tweets[]=$connection->get("statuses/user_timeline",['count'=>100,'screen_name'=>'Zahurafzal']);
		// foreach ($tweets as $p) {
		// 	foreach ($p as $k) {
		// 		echo $cnt .":- ".$k->text."<br>";
		// 		$cnt++;
		// 	}
		// } -->
		<?php
	}
	?>
	</form>
</body>
</html>