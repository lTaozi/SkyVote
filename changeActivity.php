<?php
include_once './lib/Activity.class.php';

$activity_name  = $_POST['ac_name'];
$activity_host  = $_POST['ac_host'];
$activity_start = $_POST['ac_start'];
$activity_end   = $_POST['ac_end'];
$activity_intro = $_POST['ac_intro'];
$activity_vote  = $_POST['ac_vote'];
$activity_cycle = $_POST['ac_cycle'];
$activity_plat  = $_POST['ac_plat'];
$activity_rules = $_POST['ac_rules'];
$activity_key   = $_POST['ac_key'];
$anonymous      = $_POST['anonymous'];

if (!isset($activity_name) || !isset($activity_host) || !isset($activity_start) || !isset($activity_end) || !isset($activity_intro) || !isset($activity_vote) || !isset($activity_cycle) || !isset($activity_plat) || !isset($activity_rules) || !isset($activity_key)) {
    $array = array('code' => -1,
                   'msg'  => '失败，缺失参数' );
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
}else{
    if ($activity_start > $activity_end) {
        $array = array('code' => -4,
                       'msg'  => '修改失败，开始时间晚于结束时间' );
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
    }else{
        $ac  = new Activity();
        if ($ac->changeActivity($activity_name, $activity_host, $activity_start, $activity_end, $activity_intro, $activity_vote, $activity_cycle, $activity_plat, $activity_rules, $activity_key, $anonymous)) {
            $array = array('code' => 0,
                           'msg'  => '修改成功，即将跳转' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        }else{
            $array = array('code' => -2,
                           'msg'  => '失败，SQL语句执行出错' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        }        
    }
}




?>