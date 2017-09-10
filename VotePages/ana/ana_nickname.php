<?php
include_once '../lib/DataBase.class.php';

$db = new DataBase("furonxuezi", false, true);

    $i = $_GET['id'];
    $type = $_GET['type'];
    $start = $_GET['start'];
    $end = $_GET['end'];

        $sql = "SELECT * FROM `candidate` WHERE id = ".$i;
        $db->query($sql);
        $result = $db->fetchArray(MYSQL_ASSOC);
        $list = $result[0]['fromWho'];

// print_r($list);
        $list = json_decode($list, 1);
foreach ($list as $key => $value) {
    // echo "string";
    $sql = "SELECT * FROM `browse` WHERE id =".$value;
    $db->query($sql);
    $result = $db->fetchArray(MYSQL_ASSOC);
    $nickname = $result[0]['nickname'];
    echo "$nickname<br/>";
    // echo "$value";
}