<?php
include_once './lib/Activity.class.php';

$fn = (isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : false);
if ($fn) {
    preg_match("/^(.*?)\.(.*?)\?(.*?)$/", $fn, $matche);
    $fileName = md5(time()). "." .$matche[2];
    file_put_contents(
        'VoteAdmin/pages/activity/excel/voter/' . $fileName,
        file_get_contents('php://input')
    );
    $ac_key   = $matche[3];
    $ac  = new Activity();
    if (!isset($ac_key)) {
        $array = array('code' => -1,
                       'msg'  => '发生错误，请重试。' );
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        exit();
    }else{
        $res = $ac->importVoter('VoteAdmin/pages/activity/excel/voter/' . $fileName, $ac_key);
        if(isset($res)){
            $array = array('code' => 0,
                           'msg'  => $res );
            echo json_encode($array, JSON_UNESCAPED_UNICODE); 
            exit();
        }else{
            $array = array('code' => -1,
                           'msg'  => '发生错误，请重试。' );
            echo json_encode($array, JSON_UNESCAPED_UNICODE); 
            exit(); 
        }
    }
    exit();
}
?>