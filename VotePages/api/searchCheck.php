<?php
include_once '../../lib/DataBase.class.php';

$keyword = $_GET['keyword'];
$cankey  = $_GET['cankey'];

$db = new DataBase("votesystem");
$db->query("SELECT count(uniquekey) AS num FROM candidate WHERE name like '%$keyword%' AND belong = '$cankey'");

$res = $db->results;
$res = $res[0]['num'];

if ($res > 0) {
	$data = array('code' => 0,
				  'msg'  => 'have');
	die(json_encode($data));
}else{
	$data = array('code' => -1,
				  'msg'  => '没有结果');
	die(json_encode($data));
}

?>