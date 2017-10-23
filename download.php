<?php

	require_once "controller.php";


if(isset($_REQUEST['format']))
{
	if($_REQUEST['data']=="flw")
	{
		define("ID", "FollowerName");
		define("VALUE", "FollowerScreenName");
		define("FILE_NAME", $_SESSION['flwdwn']);
		define("ROOT", "Follower");
		$cursor=-1;
		// $flwdwn=$connection->get('followers/list',["screen_name"=>$_SESSION['flwdwn'],"count"=>200,"cursor"=>$cursor]);
		$td_t = array();
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
	else
	{
		define("ID", "TweetID");
		define("VALUE", "Tweet");
		define("FILE_NAME", $user->name);
		define("ROOT", "Tweet");
		$td_t=array();
		for ($i=1; $i <= 15; $i++)
		{
			$td = $connection->get("statuses/user_timeline",['count'=>200,'exclude_replies'=>'true','include_rts'=>'true','contributor_details'=>'false','page'=>$i]);
			array_push($td_t, $td);
		}
	}

	if($_REQUEST['format']=="csv")
	{
		header("Content-type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=".FILE_NAME.".csv");

		$file = fopen(FILE_NAME.'.csv', 'w');
 		
 		fputcsv($file, array(ID, VALUE));
		 
		foreach ($td_t as $rows)
		{
			foreach ($rows as $row) {
				fputcsv($file, array($row->id_str,$row->text));
			}
		}
		
		fclose($file);

		readfile("./".FILE_NAME.".csv");
		unlink("./".FILE_NAME.".csv");	

	}
	elseif($_REQUEST['format']=="xls")
	{
		$file = fopen(FILE_NAME.'.xls', 'w');

		fputcsv($file, array(ID, VALUE));

		foreach ($td_t as $rows) {
			foreach ($rows as $row) {
		    	fputcsv($file, array($row->id_str,$row->text), "\t", '"');
		    }
		}

		fclose($file);

		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".FILE_NAME.".xls");
		readfile("./".FILE_NAME.".xls");
		unlink("./".FILE_NAME.".xls");
	}
	elseif($_REQUEST['format']=="xml")
	{
		header('Content-type: text/xml');
		header("Content-Disposition: attachment; filename=".FILE_NAME.".xml");
		$file=new SimpleXMLElement('<xml/>');

		foreach ($td_t as $rows) {
			foreach ($rows as $row) {
				$tid=$file->addChild(ROOT);
				$tid->addChild(ID,$row->id_str);
				$tid->addChild(VALUE,$row->text);
			}
		}

		$file->saveXML(FILE_NAME.".xml");
		readfile("./".FILE_NAME.".xml");
		unlink("./".FILE_NAME.".xml");
	}
	elseif($_REQUEST['format']=="json")
	{
		header("Content-type: application/json");
		header("Content-Disposition: attachment; filename=".FILE_NAME.".json");
		
		$file=fopen(FILE_NAME.".json","w");

		$result = array();
		foreach ($td_t as $rows) {
			foreach ($rows as $row) {
				array_push($result, [ID=>$row->id_str,VALUE=>$row->text]);
			}
		}

		fwrite($file, json_encode($result));
		fclose($file);

		readfile("./".FILE_NAME.".json");
		unlink("./".FILE_NAME.".json");
	}
	else
	{
		?>
			<script type="text/javascript">
				alert("Something went wrong try after sometime");
			</script>
		<?php
	}
}
?>