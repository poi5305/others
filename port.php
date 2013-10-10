<?php
ini_set("display_errors" , '0');
/*
$host = '140.113.15.90';
$port = 22;
$waitTimeoutInSeconds = 1;
if ($fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds)) {
  // It worked
  echo "ok";
} else {
  // It didn't work
  echo "no";
}
fclose($fp);
*/

$net = $argv[1]; //140.113.15.
$min = $argv[2]; // 1
$max = $argv[3]; // 255

$time_out = 1;
$one_time_limit = 500;

$tmp_file = "/tmp/tmp_ping_".time().rand(0,1000);
echo "$tmp_file\n";


$count = ceil(($max-$min+1)/$one_time_limit);

$times = 0;
for($i=$min;$i<=$max;$i+=$count)
{
	$p_min = $i;
	$p_max = $i+$count-1;
	if($p_max > $max)
		$p_max = $max;
	//echo "$p_min $p_max \n";

	$pid=pcntl_fork();
	if($pid==-1){
		exit();
	}else if($pid){
		
	}else{
		break;
	}
	
}


if($pid)
{
	echo "P\n";
	$status = null;
	sleep(1);
	$ip_info = Array();
	$ip_yes = Array();
	
	$file = File("$tmp_file");
	echo "...".count($file)."\n";
	while(count($file) != ($max-$min+1) )
	{
		echo count($file)."\n";
		$file = File("$tmp_file");
		
		sleep(1);
	}
	
	
	foreach($file as $line)
	{
		$ips = explode(":", trim($line));
		
		if($ips[1] == "Yes")
		{
			$ip_info[$ips[2]] = trim($line);
			$ip_yes[$ips[2]] = $ips[0];
		}
		
		
		//echo $ips[2];
	}
	for($i=$min;$i<=$max;$i++)
	{
		if(isset($ip_info[$i]))
			echo $ip_info[$i]."\n";
	}
	

}
else
{
	
	for($i=$p_min; $i<=$p_max; $i++)
	{
		$fpf = fopen("$tmp_file","a");
		if ($fp = @fsockopen($net,$i,$errCode,$errStr,$time_out)) 
		{
		  fwrite($fpf, $net.":Yes:$i\n");
		  stream_set_timeout($fp, 1);
		  echo fgets($fp);
		} 
		else 
		{
		  fwrite($fpf, $net.":No:$i\n");
		}
		@fclose($fp);
		fclose($fpf);
	}
	
}

?>