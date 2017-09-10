<?php
include_once "../../lib/Vote.class.php";
include_once "../../lib/Activity.class.php";
date_default_timezone_set('PRC');  //时区设置

$openId   = $_POST['openId'];
$cankey   = $_POST['cankey'];
$ackey    = $_POST['ackey'];
$username = $_POST['username'];
$plat     = $_POST['plat'];
$ip       = $_POST['ip'];


$vote = new Vote();
// 黑名单检查
$isBlack = $vote->queryBlack($openId);
if ($isBlack) {
	$array = array('code' => -6,
                   'msg'  =>  "帐号已被拉黑！");
    $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
    echo $res_json;
    exit;
}
// 非法操作检查
if ($_SESSION['ip'] != $ip) {
	$vote->blacklistAdd($openId, $plat, $username);
	$array = array('code' => -5,
                   'msg'  =>  "进行了非法操作，帐号拉黑！");
    $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
    echo $res_json;
    exit;
}else{
	// 微信关注检查
	if ($plat === '1' && $username == '') {
		$array = array('code' => -7,
           			   'msg'  =>  "请关注指定公众号再投票。");
	    $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
	    echo $res_json;
	    exit;
	}else{
		$ac = new Activity();
		$ac_json  = $ac->getActivityInfo($ackey);
		$ac_array = json_decode($ac_json, 1);
		$ac_array = $ac_array[0];
		if ($ac_array['anonymous']) {
			$res  = $vote->voteForCanAnonymous($openId, $cankey, $ackey, $username, $plat);
			echo $res;					
		}else{
			$res  = $vote->voteForCan($openId, $cankey, $ackey, $username, $plat);
			echo $res;			
		}


	}

}


