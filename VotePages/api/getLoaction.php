<?php
include_once '../../lib/DataBase.class.php';

$db = new DataBase('votesystem');
$db->query("SELECT vote_ip FROM record WHERE vote_area = ''");
$res = $db->results;


$i = 0;
foreach ($res as $key => $value) {
	$city = getCity($value[0]);
	if (!$city['city'] && !$city['region']) {
		echo $city['country'];
		$db->query("UPDATE record SET vote_area = '".$city['country']."' WHERE vote_ip = '".$value[0]."'");
	}elseif(!$city['city'] && $city['region']){
		echo $city['region'];
		$db->query("UPDATE record SET vote_area = '".$city['region']."' WHERE vote_ip = '".$value[0]."'");
	}elseif($city['city']){
		echo $city['city'];
		$db->query("UPDATE record SET vote_area = '".$city['city']."' WHERE vote_ip = '".$value[0]."'");
	}
	$i++;
	echo $i;
	sleep(rand(0.6,1.2));
	// if ($i == 10) {
	// 	break;
	// }
}

/**
 * 获取 IP  地理位置
 * 淘宝IP接口
 * @Return: array
 */
function getCity($ip = '')
{
    $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
    $ip=json_decode(file_get_contents($url));
    if((string)$ip->code=='1'){
        return false;
    }
    $data = (array)$ip->data;
    return $data;
}

?>