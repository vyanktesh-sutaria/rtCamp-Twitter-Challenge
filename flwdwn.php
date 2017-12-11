<?php

//cron format cursor screen_name email

$path = $argv[0];
$format=$argv[1];
$int_cursor = $argv[2];
$screen_name=$argv[3];
$email = $argv[4];

require_once("controller.php");

// shell_exec("sudo touch /var/www/html/rtcamp/tmp");
// shell_exec("echo '$argv[0] $argv[1] $argv[2] $argv[3] $argv[4]' >> /var/www/html/rtcamp/tmp");

define("ID", "FollowerName");
define("VALUE", "FollowerScreenName");
define("FILE_NAME", $screen_name);
define("ROOT", "Follower");
$cursor = $int_cursor;

if($cursor!=0)
{
    // $flwdwn=$connection->get('followers/list',["screen_name"=>$_SESSION['flwdwn'],"count"=>200,"cursor"=>$cursor]);
    $td_t = array();
    for ($i=1; $cursor!=0; $i++) { 
        $flwdwn=$connection->get('followers/list',["screen_name"=>$screen_name,"count"=>200,"cursor"=>$cursor]);
        if(!isset($flwdwn->users))
        {
            $reading = fopen(__DIR__.'/cron.txt', 'r');
            $writing = fopen(__DIR__.'/cron.tmp', 'w');
            
            $replaced = false;
            
            while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line,"*/15 * * * * php $path $format $int_cursor $screen_name $email ")) {
                $line = "*/15 * * * * php $path $format $cursor $screen_name $email \n";
                $replaced = true;
            }
            fputs($writing, $line);
            }
            fclose($reading); fclose($writing);
            // might as well not overwrite the file if we didn't replace anything
            if ($replaced) 
            {
            rename(__DIR__.'/cron.tmp', __DIR__.'/cron.txt');
            } else {
            unlink(__DIR__.'/cron.tmp');
            }
            shell_exec("chmod 777 ".__DIR__.'/cron.txt');
            $cmd = "sudo bash ".__DIR__."/cron.sh";
            shell_exec($cmd);
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

    if($format == "xml")
    {
        if($int_cursor == -1)
        {
            $file=new SimpleXMLElement('<xml/>');
        }
        else
        {
            $file = simplexml_load_file(__DIR__."/".FILE_NAME.'.'.$format);
        }
        
        foreach ($td_t as $rows) {
            foreach ($rows as $row) {
                $tid=$file->addChild(ROOT);
                $tid->addChild(ID,$row->id_str);
                $tid->addChild(VALUE,$row->text);
            }
        }

        $file->saveXML(__DIR__."/".FILE_NAME.'.'.$format);
    }
    else if($format == "json")
    {
        $file=fopen(__DIR__."/".FILE_NAME.'.'.$format, 'a');
        
        $result = json_decode(file_get_contents(__DIR__."/".FILE_NAME.'.'.$format),true);
        foreach ($td_t as $rows) {
            foreach ($rows as $row) {
                array_push($result, [ID=>$row->id_str,VALUE=>$row->text]);
            }
        }

        fwrite($file, json_encode($result));
        fclose($file);
    }
    else if($format == "xls")
    {

        $file = fopen(__DIR__."/".FILE_NAME.'.'.$format, 'a');

        if($int_cursor == -1)
        {
            fputcsv($file, array(ID, VALUE));
        }

        foreach ($td_t as $rows)
        {
            foreach ($rows as $row) {
                fputcsv($file, array($row->id_str,$row->text));
            }
        }

        fclose($file);
    }
    else
    {
        $file = fopen(__DIR__."/".FILE_NAME.'.'.$format, 'a');
        
        if($int_cursor == -1)
        {
            fputcsv($file, array(ID, VALUE));
        }

        foreach ($td_t as $rows)
        {
            foreach ($rows as $row) {
                fputcsv($file, array($row->id_str,$row->text));
            }
        }

        fclose($file);
        
    }
}

if($cursor == 0)
{
	require_once('PHPMailer-master/PHPMailerAutoload.php');
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug = 0;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587; 
	$mail->Username = "vsutaria72@gmail.com";  
	$mail->Password = "1ofthewonder@indai./";
	
	$mail->SetFrom('vsutaria72@gmail.com', 'RTCamp Twitter');
	$mail->AddAddress($email);
	$mail->Subject = "Follower Data";
	$mail->AltBody = "";
	$mail->MsgHTML("Your requested follower data is in file attached below");
	$mail->AddAttachment(__DIR__."/".FILE_NAME.'.'.$format);

	if($mail->Send()){
		$reading = fopen(__DIR__.'/cron.txt', 'r');
		$writing = fopen(__DIR__.'/cron.tmp', 'w');
		
		$replaced = false;
		
		while (!feof($reading)) {
		$line = fgets($reading);
		if (stristr($line,"*/15 * * * * php $path $format $int_cursor $screen_name $email ")) {
			$line = "";
			$replaced = true;
		}
		fputs($writing, $line);
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) 
		{
		rename(__DIR__.'/cron.tmp', __DIR__.'/cron.txt');
		} else {
		unlink(__DIR__.'/cron.tmp');
        }
        shell_exec("chmod 777 ".__DIR__.'/cron.txt');
        $cmd = "sudo bash ".__DIR__."/cron.sh";
		shell_exec($cmd);
		unlink(__DIR__."/".FILE_NAME.'.'.$format);
	}
	else{
		echo "Error while sending mail : ".$mail->ErrorInfo;
	}

}
?>