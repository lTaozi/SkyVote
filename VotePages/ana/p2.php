<?php
include_once '../lib/DataBase.class.php';

$db = new DataBase("furonxuezi", false, true);

$sql = "SELECT * FROM `record_list` WHERE position = '' AND id > 19999 AND id < 23000";
$db->query($sql);
$result = $db->fetchArray(MYSQL_ASSOC);

$city_list = array();
$i = 0;
foreach ($result as $key => $value) {
    $i++;
    echo "$i<br/>";
    // $isJyw = preg_match('/^172.\d+.\d+.\d+/', $value['ip']);
    // if (!$isJyw) {
        // if ($value['position'] == "") {
            $position     = @file_get_contents("http://yfree.cc/ipTool/ipToPosition.php?ip=".$value['ip']);
            // $ip_array    = json_decode($ip_data, 1);
            // $position    = $ip_array['data']['city'];
            $sql = "UPDATE `record_list` SET position = '$position' WHERE id = ".$value['id'];
            $db->query($sql);
            
        // }        
    // }
    // else{
    //     $sql = "UPDATE `record_list` SET position = '湘潭市' WHERE id = ".$value['id'];
    //     $db->query($sql);
    // }
}

