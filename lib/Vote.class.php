<?php
$dir = dirname(__FILE__);
include_once $dir.'/DataBase.class.php';
include_once $dir.'/Activity.class.php';
date_default_timezone_set('PRC');  //时区设置

/**
* 投票类
*/
class Vote
{
    private $db;
    private $ip;
    private $city;
    private $province;
    private $starttime;
    private $endtime;

    function __construct()
    {
        $db = new DataBase("votesystem");
        $this->db = $db;
        session_start();
        $this->ip       = $_SESSION['ip'];  
        $this->city     = $_SESSION['city'];  
        $this->province = $_SESSION['province'];  
    }

    /*检查是否有票可投*/
    function _checkStatus($openId, $ackey){
        $db = $this->db;
        // 查询限制
        $ac = new Activity();
        $db->query("SELECT refreshcycle, refreshballot, starttime, endtime FROM activitys WHERE activity_key = '$ackey'");
        $res = $db->results;
        $db->results = '';
        $cycle = $res[0]['refreshcycle'];
        $votes = $res[0]['refreshballot'];
        $starttime = $res[0]['starttime'];
        $endtime   = $res[0]['endtime'];
        $nowtime   = date("Y-m-d H:i:s");
        if ($nowtime > $endtime) {
            return '-1';  //已结束
        }elseif($nowtime < $starttime){
            return '-2';  //未开始
        }
        // 查询次数
        $starttime = date("Y-m-d 00:00:00");
        $endtime   = date("Y-m-d H:i:s", strtotime(date("Y-m-d 00:00:00"))+86400*$cycle);
        $this->starttime = $starttime;
        $this->endtime   = $endtime;  
        $db->query("SELECT count(id) as num FROM record WHERE user_key = '$openId' AND activity_key = '$ackey' AND votetime > '$starttime' AND votetime < '$endtime'");
        $res = $db->results;
        //判断
        if ($res[0]['num'] < $votes) {
            return '1'; //可投
        }else{
            return "-3";  //无票
        }
    }

    /*检查是否有票可投*/
    function _checkStatus_an($openId, $ackey){
        $db = $this->db;
        // 查询限制
        $ac = new Activity();
        $db->query("SELECT refreshcycle, refreshballot, starttime, endtime FROM activitys WHERE activity_key = '$ackey'");
        $res = $db->results;
        $db->results = '';
        $cycle = $res[0]['refreshcycle'];
        $votes = $res[0]['refreshballot'];
        $starttime = $res[0]['starttime'];
        $endtime   = $res[0]['endtime'];
        $nowtime   = date("Y-m-d H:i:s");
        if ($nowtime > $endtime) {
            return '-1';  //已结束
        }elseif($nowtime < $starttime){
            return '-2';  //未开始
        }
        // 查询次数
        $starttime = date("Y-m-d 00:00:00");
        $endtime   = date("Y-m-d H:i:s", strtotime(date("Y-m-d 00:00:00"))+86400*$cycle);
        $db->query("SELECT count(id) as num FROM record_anonymous WHERE user_key = '$openId' AND activity_key = '$ackey' AND votetime > '$starttime' AND votetime < '$endtime'");
        $res = $db->results;
        //判断
        if ($res[0]['num'] < $votes) {
            return '1'; //可投
        }else{
            return "-3";  //无票
        }
    }

    /*投票*/
    function voteForCan($openId, $cankey, $ackey, $username, $plat){
        $db = $this->db;
        $status = $this->_checkStatus($openId, $ackey);
        if ($status == '1') {
            // 每周可投一票以上时限制不能投同一个人
            $db->query("SELECT count(id) as num FROM record WHERE user_key = '$openId' AND activity_key = '$ackey' AND towho = '$cankey' AND votetime > '$this->starttime' AND votetime < '$this->endtime'");
            $res = $db->results;
            if ($res[0]['num'] >= 1) {
                $array = array('code' => -5,
                                'msg'  =>  "每周期不能投同一作品！");
                $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
                die($res_json);
            }
            // 投票操作开始
            $db->query("UPDATE candidate SET votes = votes+1 WHERE uniquekey = '$cankey' AND belong = '$ackey'");
            if (!$db->code) {
                $time = date("Y-m-d H:i:s");
                $db->query("INSERT INTO record (user_key, vote_username, vote_plat, vote_ip, votetime, activity_key, towho, vote_province, vote_area) VALUES ('$openId', '$username', '$plat', '$this->ip', '$time', '$ackey', '$cankey', '$this->province', '$this->city')");
                if (!$db->code) {
                    $array = array('code' => 0,
                                   'msg'  =>  "投票成功！");
                    $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
                    die($res_json);
                }else {
                    $db->query("INSERT INTO record (user_key, vote_username, vote_plat, vote_ip, votetime, activity_key, towho, vote_province, vote_area) VALUES ('$openId', '$username', '$plat', '$this->ip', '$time', '$ackey', '$cankey', '$this->province', '$this->city')");
                    $array = array('code' => 0,
                                   'msg'  =>  "投票成功！");
                    $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
                    die($res_json);
                }
            }else{
                $array = array('code' => -4,
                               'msg'  =>  "投票失败请刷新重试！");
                $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
                die($res_json);
            }
        }elseif($status == '-3'){
            $array = array('code' => -3,
                           'msg'  =>  "投票机会已用完！");
            $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
            die($res_json);
        }elseif($status == '-1'){
            $array = array('code' => -1,
                           'msg'  =>  "投票已结束！");
            $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
            die($res_json);
        }elseif($status == '-2'){
            $array = array('code' => -2,
                           'msg'  =>  "投票尚未开始！");
            $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
            die($res_json);            
        }
    }

    /*匿名投票*/
    function voteForCanAnonymous($openId, $cankey, $ackey, $username, $plat){
        $db = $this->db;
        $status = $this->_checkStatus_an($openId, $ackey);
        if ($status == '1') {
            $db->query("UPDATE candidate SET votes = votes+1 WHERE uniquekey = '$cankey' AND belong = '$ackey'");
            if (!$db->code) {
                $time = date("Y-m-d H:i:s");
                $db->query("INSERT INTO record_anonymous (user_key, vote_username, vote_plat, votetime, activity_key) VALUES ('$openId', '$username', '$plat', '$time', '$ackey')");
                if (!$db->code) {
                    $array = array('code' => 0,
                                   'msg'  =>  "投票成功！");
                    $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
                    die($res_json);
                }else {
                    $db->query("INSERT INTO record_anonymous (user_key, vote_username, vote_plat, votetime, activity_key) VALUES ('$openId', '$username', '$plat', '$time', '$ackey')");
                    $array = array('code' => 0,
                                   'msg'  =>  "投票成功！");
                    $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
                    die($res_json);
                }
            }else{
                $array = array('code' => -4,
                               'msg'  =>  "投票失败请刷新重试！");
                $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
                die($res_json);
            }
        }elseif($status == '-3'){
            $array = array('code' => -3,
                           'msg'  =>  "投票机会已用完！");
            $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
            die($res_json);
        }elseif($status == '-1'){
            $array = array('code' => -1,
                           'msg'  =>  "投票已结束！");
            $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
            die($res_json);
        }elseif($status == '-2'){
            $array = array('code' => -2,
                           'msg'  =>  "投票尚未开始！");
            $res_json = json_encode($array, JSON_UNESCAPED_UNICODE);
            die($res_json);            
        }
    }

    /*获取IP地址*/
    function GetIP(){ 
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
        return($ip); 
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

    /**
     * 拉黑非法操作帐号
     */
    function blacklistAdd($openId, $plat, $username){
        $db = $this->db;
        $addTime = date("Y-m-d H:i:s");
        $db->query("INSERT INTO blacklist (openid, plat, addtime, username) VALUES ('$openId', '$plat', '$addTime', '$username')");
        if (!$db->code) {
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 黑名单查询
     */
    function queryBlack($openId){
        $db = $this->db;
        $db->query("SELECT count(id) FROM blacklist WHERE openid = '$openId'");
        $res = $db->results;
        if ($res[0][0] > 0) {
            return true;
        }else{
            return false;
        }
    }
}

?>