<?php
/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/4/16 0016
 * Time: 15:42
 */
include_once "lib/config.php";
include_once "lib/db.class.php";
$id  = isset($_GET['id']) ? $_GET['id'] : "";
if($id == ""){
    exit('[]');
}
$sql = "select count(towho) as count , vote_area from record
        where towho =  '$id' group by vote_area  ";
$rs = $dbClass->query($sql);
$data = array();
$i = 0;
while ($r = $dbClass->getone($rs)){
    $color  = '#'.substr(md5(rand()),0,6);
    $data[$i]['value'] = (int)$r['count'];
    $data[$i]['highlight'] = "#f56954";
    $data[$i]['color'] = $color;
    $data[$i]['label'] = $r['vote_area'];


    $i++;
}
echo (json_encode($data,JSON_UNESCAPED_UNICODE));