<?php
include_once './lib/Activity.class.php';

$fn = (isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : false);
if ($fn) {
    preg_match("/^(.*?)\.(.*?)\?(.*?)$/", $fn, $matche);
    $fileName =  md5($matche[1]) . "_" . md5(time()) . "." .$matche[2];
    file_put_contents(
        'VoteAdmin/pages/candidate/img/img/' . $fileName,
        file_get_contents('php://input')
    );
    $candidate_key = $matche[3];
    $ac  = new Activity();
    if (!isset($candidate_key)) {
        $array = array('code' => -1,
                       'msg'  => '发生错误，请刷新重试。' );
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        exit();
    }else{
        if ($ac->setCandidateImg($fileName, $candidate_key)) {
            $array = array('code' => 0,
                           'msg'  => '候选人添加成功，即将跳转。' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE); 
            exit();
        }else{
            $array = array('code' => -2,
                           'msg'  => '失败，SQL语句执行出错。' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE);  
            exit();          
        }
    }
    exit();
}else{
    $array = array('code' => 1,
                   'msg'  => '没有选择文件' );
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
    exit();
}
?>