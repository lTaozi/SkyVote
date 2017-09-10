<?php
include_once 'rootConfig.php';

// 接受数据
$username = $_POST['username'] ;
$password = $_POST['password'] ;

if ($username === Username && $password === Password) {
	$data = array('code' => 0,
				  'msg'  => 'success');
	die(json_encode($data));
}else{
	$data = array('code' => -1,
				  'msg'  => 'fail');
	die(json_encode($data));
}

?>