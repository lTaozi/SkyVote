<?php
include_once './lib/Activity.class.php';

$candidate_name    = $_POST['candidate_name'];
$candidate_contact = $_POST['candidate_contact'];
$candidate_type    = $_POST['candidate_type'];
$candidate_intro   = $_POST['candidate_intro'];
$candidate_key     = $_POST['candidate_key'];
$candidate_media   = $_POST['candidate_media'];
$activity_key      = $_POST['activity_key'];

if (!isset($candidate_name) || !isset($candidate_type) || !isset($candidate_intro) || !isset($candidate_contact)) {
    $array = array('code' => -1,
                   'msg'  => '失败，缺失重要参数' );
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
}else if(isset($candidate_name) && isset($candidate_type) && isset($candidate_intro) && isset($candidate_contact)){
    $ac  = new Activity();
    $candidate_key = ($candidate_key) ? $candidate_key : md5(time());
    if ($ac->addCandidate($candidate_name, $candidate_contact, $candidate_key, $candidate_intro, $candidate_type, $activity_key, $candidate_media)) {
        $array = array('code' => 0,
                       'msg'  => $candidate_key );
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
    }else{
        $array = array('code' => -2,
                       'msg'  => '失败，SQL语句执行出错' );
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
    }
}

