<?php
include_once '../lib/DataBase.class.php';
date_default_timezone_set('PRC');  //时区设置

session_start();
$openId = $_SESSION["openId"];
$nickname = $_SESSION["nickName"];
$content = $_GET['content'];
$date  = date("Y-m-d H:i:s");
// $openId = "osvcaw-JiPeaENHEVTMtShJzocGg";

$db = new DataBase("furonxuezi", false, true);
$sql = "INSERT INTO problem (nickname, openId, content, subDate) VALUES ('".$nickname."', '$openId', '$content', '$date')";
$db->query($sql);


