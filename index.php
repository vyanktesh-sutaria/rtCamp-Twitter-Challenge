<?php 
require 'controller.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>RtCamp twitter Project</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
	<!-- bxSlider CSS file -->
	<link href="assets/css/jquery.bxslider.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/tweet.24e0cef9279c9cccaf5e72165aa3517a.light.ltr.css">
	<!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">    
    <!--Import jQuery-ui CSS-->
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <!--Import Custom CSS-->
    <link rel="stylesheet" href="assets/css/custom.css">

    <script src="assets/js/jquery-1.9.1.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<!--Import jQuery-ui js -->
	<script src="assets/js/jquery-ui.js"></script>
	<!-- bxSlider Javascript file -->
	<script src="assets/js/jquery.bxslider.js"></script>

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
					$.ajax({
						url:"controller.php?flwsrch="+ui.item.value,
						type:"post",
						success:function(response){
							slider.destroySlider();
							slider.html(response).bxSlider();
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
<body style="overflow-x: hidden; ">
<form method="post">
	<?php 
	if($user == null)
	{
		?>
		<div class="container" style="background-color: #00aced; margin: 0;width: 100%;height: 47.25em;">
			<div class="row" style="margin: 2em;">
				<center>
					<img src="assets/images/logo.png"/><br>
					<a href="<?php echo $url?>" class="btn pulse" style="color: white; font-size: 36px;width: 20%;background-color: #1dcaff">Login</a>
				</center>
			</div>
		</div>
		<?php
	}
	else
	{
		?>
		<div class="row" style="background-color: #03a9f4">
			<div class="col-xs-12">
				<div class="col-xs-2">
					<img src="assets/images/twitterlogo.png" height="60px" width="60px" />
				</div>
				<div class="col-xs-8" style="margin-top: 1em;">
					<input type="text" class="form-control" id="followersrch" autocomplete="off" placeholder="Search follower" />
				</div>
				<div class="col-xs-2">
					<input type="submit" name="btnlogout" class="btn btn-danger" style="float: right;margin: 1em 0;" value="Logout" />
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="row" style="margin-top: 1em;">
			<div class="col-xs-12">
				<div class="col-xs-3" style="float: right; margin: 0 5.5em 1.5em 0;">
					<div class = "btn-group">
						<button type = "button" class = "btn btn-primary dropdown-toggle" data-toggle = "dropdown">
							Download Tweets 
							<span class = "caret"></span>
						</button>
						
						<ul class = "dropdown-menu">
							<li><a href = "download.php?format=csv">csv format</a></li>
							<li><a href = "download.php?format=xls">excel format</a></li>
							<li><a href = "download.php?format=xml">XML format</a></li>
							<li><a href = "download.php?format=json">json format</a></li>
							<li>
							<a href="<?php echo $client->createAuthUrl(); ?>">Google SpreadSheet(Login Required)</a>
							</li>
						</ul>

					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-xs-8 col-xs-offset-2">
							<ul class="bxslider">
	<?php
	if($tweets == null)
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
	  <a class="TweetAuthor-link Identity u-linkBlend" data-scribe="element:user_link" href="https://twitter.com/<?php echo $user->screen_name?>" aria-label="US Dept of Interior (screen name: <?php echo $user->screen_name?>)">
	    <span class="TweetAuthor-avatar Identity-avatar">
	      <img class="Avatar Avatar--edge" data-scribe="element:avatar" data-src-2x="https://pbs.twimg.com/profile_images/432081479/DOI_LOGO_bigger.jpg" alt="" data-src-1x="https://pbs.twimg.com/profile_images/432081479/DOI_LOGO_normal.jpg" src="<?php echo $user->profile_image_url_https;?>">
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
  ?>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-xs-8 col-xs-offset-2">
					<div class="panel panel-default">
						<div class="panel-heading"><center>Followers List</center></div>
						<div class="panel-body">
							<ul>
								<?php
								foreach (new LimitIterator(new ArrayIterator(json_decode($follower_name)),0,10) as $flw) 
								{
									?>
									<li>
										<div class="col-xs-6" style="padding: 0.5em 0;">
											<div class="col-xs-3" style="padding: 0;">
												<img src="<?php echo $flw->img; ?>" style="border-radius: 100%;" height="50px" width="50px" />
											</div>
											<div class="col-xs-9" style="padding: 0;">
												<span><?php echo $flw->label;?></span>
												<span class="TweetAuthor-screenName Identity-screenName">@<?php echo $flw->value;?></span>
											</div>
										</div>
									</li>
									<?php
								}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
	}
	?>
	</form>
</body>
</html>