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
$anonymous      = $_POST['anonymous'];

if (!isset($activity_name) || !isset($activity_host)) {
    $array = array('code' => -1,
                   'msg'  => '失败，缺失重要参数' );
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
}else if(isset($activity_name) && isset($activity_host)){
    if (!isset($activity_start) || !isset($activity_intro) || !isset($activity_vote) || !isset($activity_cycle) || !isset($activity_plat) || !isset($activity_end)) {
        $ac  = new Activity();
        //设置默认开始时间为一天后，结束时间为一星期后，后期可修改
        $startTime  = date("Y-m-d H:i:s", strtotime("+1 day"));
        $endTime    = date("Y-m-d H:i:s", strtotime("+8 days"));
        if ($ac->createActivity($activity_name, $activity_host, $startTime, $endTime, '默认介绍，请修改。','1', '1', '0', '0')) {
            $array = array('code' => 1,
                           'msg'  => '缺失参数，以缺省值创建成功' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        }else{
            $array = array('code' => -2,
                           'msg'  => '失败，SQL语句执行出错' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }else{
        if ($activity_start > $activity_end) {
            $array = array('code' => -4,
                           'msg'  => '创建失败，开始时间晚于结束时间' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        }else{
            $ac  = new Activity();
            if ($ac->createActivity($activity_name, $activity_host, $activity_start, $activity_end, $activity_intro, $activity_vote, $activity_cycle, $activity_plat, $anonymous)) {
                $array = array('code' => 0,
                               'msg'  => '创建成功，即将跳转' );
                echo json_encode($array, JSON_UNESCAPED_UNICODE);
            }else{
                $array = array('code' => -2,
                               'msg'  => '失败，SQL语句执行出错' );
                echo json_encode($array, JSON_UNESCAPED_UNICODE);
            }        
        }
    }
}else{
    $array = array('code' => -3,
                   'msg'  => '失败' );
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
}

