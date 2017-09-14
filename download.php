<?php

	require_once "controller.php";

if(isset($_REQUEST['format']))
{

	$td_t=array();
	if(count($td_t)==0)
	{
		for ($i=1; $i <= 16; $i++)
		{
			$td = $connection->get("statuses/user_timeline",['count'=>200,'exclude_replies'=>'true','include_rts'=>'true','contributor_details'=>'false','page'=>$i]);
			array_push($td_t, $td);
		}
	}

	if($_REQUEST['format']=="csv")
	{
		header("Content-type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=".$user->name." Tweets.csv");

		$file = fopen($user->name.' Tweets.csv', 'w');
 		
 		fputcsv($file, array('Tweet ID', 'Tweet'));
		 
		foreach ($td_t as $rows)
		{
			foreach ($rows as $row) {
				fputcsv($file, array($row->id_str,$row->text));
			}
		}
		
		fclose($file);

		readfile("./".$user->name." Tweets.csv");
		unlink("./".$user->name." Tweets.csv");	

	}
	elseif($_REQUEST['format']=="xls")
	{
		$file = fopen($user->name.' Tweets.xls', 'w');

		fputcsv($file, array('Tweet ID', 'Tweet'));

		foreach ($td_t as $rows) {
			foreach ($rows as $row) {
		    	fputcsv($file, array($row->id_str,$row->text), "\t", '"');
		    }
		}

		fclose($file);

		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".$user->name." Tweets.xls");
		readfile("./".$user->name." Tweets.xls");
		unlink("./".$user->name." Tweets.xls");
	}
	elseif($_REQUEST['format']=="xml")
	{
		header('Content-type: text/xml');
		header("Content-Disposition: attachment; filename=".$user->name." Tweets.xml");
		$file=new SimpleXMLElement('<xml/>');

		foreach ($td_t as $rows) {
			foreach ($rows as $row) {
				$tid=$file->addChild('Tweet');
				$tid->addChild("TweetID",$row->id_str);
				$tid->addChild("Tweetcontent",$row->text);
			}
		}

		$file->saveXML($user->name." Tweets.xml");
		readfile("./".$user->name." Tweets.xml");
		unlink("./".$user->name." Tweets.xml");
	}
	elseif($_REQUEST['format']=="json")
	{
		header("Content-type: application/json");
		header("Content-Disposition: attachment; filename=".$user->name." Tweets.json");
		
		$file=fopen($user->name." Tweets.json","w");

		$result = array();
		foreach ($td_t as $rows) {
			foreach ($rows as $row) {
				array_push($result, ["TweetID"=>$row->id_str,"Tweet"=>$row->text]);
			}
		}

		fwrite($file, json_encode($result));
		fclose($file);

		readfile("./".$user->name." Tweets.json");
		unlink("./".$user->name." Tweets.json");
	}
}
?>