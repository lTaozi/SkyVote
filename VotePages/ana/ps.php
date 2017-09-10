<?php
include_once '../lib/DataBase.class.php';

$db = new DataBase("furonxuezi", false, true);

$sql = "SELECT * FROM `record_list` WHERE position = '' AND id > 3605 AND id < 10000";
$db->query($sql);
$result = $db->fetchArray(MYSQL_ASSOC);

$city_list = array();

$i = 0;

// print_r($result);
foreach ($result as $key => $value) {
    $i++;
    echo "$i<br/>";
    $position     = @file_get_contents("http://yfree.cc/ipTool/ipToPosition.php?ip=".$value['ip']);
    $sql = "UPDATE `record_list` SET position = '$position' WHERE id = ".$value['id'];
    $db->query($sql);
}






