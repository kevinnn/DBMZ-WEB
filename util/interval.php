<?php
ignore_user_abort();
set_time_limit(0);
include dirname(dirname(__FILE__)).'/config/config.php';
include __ROOT__.'/controller/yungouController.php';
date_default_timezone_set('PRC');

$startTime = date('Y-m-d H:i:s');

$now = strtotime($startTime);

$date = date('Y-m-d');
$ymd = explode("-", $date);
for($s = mktime(0,4,0,$ymd[1],$ymd[2],$ymd[0]),$e = mktime(1,59,0,$ymd[1],$ymd[2],$ymd[0]); $s <= $e; $s+=300) {
	if ($now < $s) {
		$endTime = $s;
		break;
	}
	if ($s == $e) {
		$s = mktime(9,59,0,$ymd[1],$ymd[2],$ymd[0]);
		$e = mktime(23,59,0,$ymd[1],$ymd[2],$ymd[0]);
	}
}

$now = strtotime(date('Y-m-d H:i:s'));

$sleep_time = $endTime-$now;
$switch = include __ROOT__.'/util/switch.php';
while ($switch) {
	$timeStart = time();
	$switch = include __ROOT__.'/util/switch.php';
	$result = yungouController::process();
	file_put_contents(__ROOT__."/log.log",$result."\n",FILE_APPEND);
	$timeEnd = time();
	$sleep_time = $sleep_time-($timeEnd-$timeStart);
	sleep($sleep_time);
	$sleep_time = 300;
}
?>