<?php
include_once '../lib/DataBase.class.php';

$db = new DataBase("furonxuezi", false, true);

$i = $_GET['id'];
$sql = "SELECT * FROM `record_list` WHERE toCandidate =".$i;
$db->query($sql);
$result = $db->fetchArray(MYSQL_ASSOC);

foreach ($result as $key => $value) {
    $unix = strtotime($value['voteTime']) - 1480000000;
    echo $unix."<br/>";
}