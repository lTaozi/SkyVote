<?php
include_once './lib/Activity.class.php';

$fn = (isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : false);
if ($fn) {
    preg_match("/^(.*?)\.(.*?)\?(.*?)$/", $fn, $matche);
    $fileName = md5(time()) . "." .$matche[2];
    file_put_contents(
        'VoteAdmin/pages/activity/img/bg/' . $fileName,
        file_get_contents('php://input')
    );
    $ac_key   = $matche[3];
    $ac  = new Activity();
    if (!isset($ac_key)) {
        $array = array('code' => -1,
                       'msg'  => '发生错误，请刷新重试。' );
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        exit();
    }else{
        if ($ac->changeAcImg($fileName, $ac_key)) {
            $array = array('code' => 0,
                           'msg'  => '背景图设置成功' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE); 
            exit();
        }else{
            $array = array('code' => -2,
                           'msg'  => '失败，SQL语句执行出错' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE);  
            exit();          
        }
    }
    exit();
}
?>