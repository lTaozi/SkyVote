<?php
/*获取IP地址*/
if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
    $ip = getenv("HTTP_CLIENT_IP"); 
else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
    $ip = getenv("HTTP_X_FORWARDED_FOR"); 
else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
    $ip = getenv("REMOTE_ADDR"); 
else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
    $ip = $_SERVER['REMOTE_ADDR']; 
else 
    $ip = "unknown";

/*获取位置*/
$url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=".$ip;
$jsonD = file_get_contents($url);
$array = json_decode($jsonD, 1);
if ($array['ret'] == -1) {
	$city = '';
	$province = '';
}else{
	if ($array['city'] != '') {
		$city = $array['city'];
		$province = $array['province'];
	}elseif($array['province'] != ''){
		$city = $array['province'];
		$province = $array['province'];
	}elseif($array['country'] != ''){
		$city = $array['country'];
		$province = $array['province'];
	}	
}

session_start();
$_SESSION['ip']       = $ip;
$_SESSION['city'] = $city;
$_SESSION['province'] = $province;

$array  = array('code' => 0,
                'ip'   => $ip);
$jsonD  = json_encode($array, JSON_UNESCAPED_UNICODE);

echo $jsonD;