<?php
/**
* 功能：初始化一个投票活动
*   1.创建一个新的活动，可记录活动名，主办方，创建时间，开始和结束时间，创建者，票数更新周期（n天，0表示不更新）和每次更新的票数（同时也是初始票数），规则和说明（可选）、平台限制
*   2.Excel表导入投票人信息表
*   3.导入和手动输入候选人信息
*/
$dir = dirname(__FILE__);
include_once $dir.'/DataBase.class.php';
include_once $dir.'/phpexcel/PHPExcel/IOFactory.php';

class Activity
{
    private $db;

    function __construct()
    {
        $db = new DataBase("votesystem");
        $this->db = $db;
    }

/*----------------------activity-start----------------------------*/

    /*新建一个投票活动*/
    public function createActivity($name, $host, $startTime, $endTime, $intro, $voteNum, $cycle, $platformLimit, $anonymous){
        $createTime = date("Y-m-d H:i:s");
        $activity_key = md5(time());

        //从session中获取当前管理员账户
        session_start();
        $creater    = $_SESSION['username'];
        //插入数据
        $db = $this->db;
        //数据过滤
        $name          = addslashes(sprintf("%s", $name));
        $host          = addslashes(sprintf("%s", $host));
        $startTime     = addslashes(sprintf("%s", $startTime));
        $endTime       = addslashes(sprintf("%s", $endTime));
        $intro         = addslashes(sprintf("%s", $intro));
        $voteNum       = intval($voteNum);
        $cycle         = intval($cycle);
        $platformLimit = intval($platformLimit);
        $anonymous     = intval($anonymous);
        $db->query("INSERT INTO activitys (activity_key, activity_name, host, createtime, starttime, endtime, creater, intro, refreshcycle, refreshballot, platformlimit, anonymous) VALUES ('$activity_key', '$name', '$host', '$createTime', '$startTime', '$endTime', '$creater', '$intro', '$cycle', '$voteNum', '$platformLimit', '$anonymous')");
        if (!$db->code) {
            return 1;
        }else{
            return 0;
        }
    }

    /*删除一个活动--暂时不写*/

    /*更新活动信息*/
    public function changeActivity($name, $host, $startTime, $endTime, $intro, $refreshBallot, $refreshCycle, $platformLimit, $rules, $key, $anonymous){
        $db = $this->db;
        //数据过滤
        $name          = addslashes(sprintf("%s", $name));
        $host          = addslashes(sprintf("%s", $host));
        $startTime     = addslashes(sprintf("%s", $startTime));
        $endTime       = addslashes(sprintf("%s", $endTime));
        $intro         = addslashes(sprintf("%s", $intro));
        $rules         = addslashes(sprintf("%s", $rules));
        $key           = addslashes(sprintf("%s", $key));
        $voteNum       = intval($refreshBallot);
        $cycle         = intval($refreshCycle);
        $platformlimit = intval($platformLimit);
        $anonymous     = intval($anonymous);
        $db->query("UPDATE activitys SET 
            activity_name = '$name', 
            host          = '$host', 
            starttime     = '$startTime', 
            endtime       = '$endTime', 
            intro         = '$intro', 
            refreshcycle  = '$refreshCycle',
            refreshballot = '$refreshBallot',
            platformlimit = '$platformLimit',
            rules         = '$rules',
            anonymous     = '$anonymous'
            WHERE activity_key = '$key'");
        if (!$db->code) {
            return 1;
        }else{
            return 0;
        }
    }

    /*更新活动背景图*/
    public function changeAcImg($fileName, $key){
        $db = $this->db;
        // 数据过滤
        $key      = addslashes(sprintf("%s", $key));
        $fileName = addslashes(sprintf("%s", $fileName));
        $db->query("UPDATE activitys SET ac_img = '$fileName' WHERE activity_key = '$key'");
        if (!$db->code) {
            return 1;
        }else{
            return 0;
        }        
    }

    /*更新活动logo*/
    public function changeAcLogo($fileName, $key){
        $db = $this->db;
        // 数据过滤
        $key      = addslashes(sprintf("%s", $key));
        $fileName = addslashes(sprintf("%s", $fileName));
        $db->query("UPDATE activitys SET ac_logo = '$fileName' WHERE activity_key = '$key'");
        if (!$db->code) {
            return 1;
        }else{
            return 0;
        }        
    }

    /*获取活动基本信息*/
    public function getActivityInfo($key){
        $db  = $this->db;
        $key = addslashes(sprintf("%s", $key));
        $db->query("SELECT * FROM activitys WHERE activity_key = '$key'");
        $res_json = json_encode($db->results, JSON_UNESCAPED_UNICODE);
        return $res_json;     
    }

    /*获取所有活动基本信息*/
    public function getAllActivity(){
        $db = $this->db;
        $db->query("SELECT activity_name,host,starttime,endtime,activity_key FROM activitys");
        $res_json = json_encode($db->results, JSON_UNESCAPED_UNICODE);
        return $res_json;     
    }    

    /*导入投票人信息-返回导入成功数目*/
    public function importVoter($filePath, $belong){
        $db = $this->db;
        $belong = addslashes(sprintf("%s", $belong));
        $objPHPExcel = PHPExcel_IOFactory::load($filePath);
        $dataArray = $objPHPExcel->getActiveSheet()->toArray();
        array_splice($dataArray, 0, 1);
        $numSuccess = 0;//成功计数器
        $numFail = 0;//失败计数器
        foreach ($dataArray as $key => $value) {
            $uniquekey = ($value[0]) ? $value[0] : md5(time()+rand(1,100000));
            if(isset($value[3])){
                $code = 0;      
                $db->query("INSERT INTO voter_import (uniquekey, nickname, account, password, belong) VALUES ('$uniquekey', '$value[1]', '$value[2]', '$value[3]', '$belong')");
                if (!$db->code) {
                    $numSuccess++;
                }else if($db->code == 1062){
                    $numFail++;
                    $reason = "存在已导入的用户信息";
                }else{
                    $numFail++;
                }   
            }else{
                $numSuccess = 0;
                $numFail    = 0;
                $reason     = "表格文件格式不对，请按指定格式进行编辑";
                $code       = -1;      
            }
            
        }
        $info = array('code'    => $code,
                      'success' => $numSuccess,
                      'fail'    => $numFail,
                      'reason'  => $data = (isset($reason)) ? $reason : '未发生错误');
        $info_json = json_encode($info, JSON_UNESCAPED_UNICODE);
        return $info_json;
    }

    /*导入候选人信息-返回导入成功数目*/
    public function importCandidate($filePath, $belong){
        $db = $this->db;
        $belong = addslashes(sprintf("%s", $belong));
        $objPHPExcel = PHPExcel_IOFactory::load($filePath);
        $dataArray = $objPHPExcel->getActiveSheet()->toArray();
        array_splice($dataArray, 0, 1);
        $numSuccess = 0;//成功计数器
        $numFail = 0;//失败计数器
        foreach ($dataArray as $key => $value) {
            $uniquekey = ($value[0]) ? $value[0] : md5(time()+rand(1,100000));
            $contact   = ($value[3]) ? $value[3] : "";
            $type      = ($value[4]) ? $value[4] : "1";
            if (isset($value[4])) {
                $code = 0;      
                $db->query("INSERT INTO candidate (uniquekey, name, introduction, belong, contact, type) VALUES ('$uniquekey', '$value[1]', '$value[2]', '$belong', '$contact', '$type')");
                if (!$db->code) {
                    $numSuccess++;
                }else if($db->code == 1062){
                    $numFail++;
                    $reason = "存在已导入的候选人信息";
                }else{
                    $numFail++;
                }
            }else{
                $numSuccess = 0;
                $numFail    = 0;
                $reason     = "表格文件格式不对(或type未设置)，请按指定格式进行编辑";
                $code       = -1;                    
            }

        }
        $info = array('code'    => $code,
                      'success' => $numSuccess,
                      'fail'    => $numFail,
                      'reason'  => $data = (isset($reason)) ? $reason : '未发生错误');
        $info_json = json_encode($info, JSON_UNESCAPED_UNICODE);
        return $info_json;
    }

    /*获取活动的累计票数*/
    function getTotalVotes($key){
        $db  = $this->db;
        $key = addslashes(sprintf("%s", $key));
        $db->query("SELECT count(id) AS totalVotes FROM record WHERE activity_key = '$key'");
        $results  = $db->results[0];
        $res_json = json_encode($results, JSON_UNESCAPED_UNICODE);
        return $res_json;
    }

    /*增加访问次数VV*/
    function addVV($key){
        $db  = $this->db;
        $key = addslashes(sprintf("%s", $key));
        $db->query("UPDATE activitys SET vv=vv+1 WHERE activity_key = '$key'");
        if (!$db->code) {
            return 1;
        }else{
            return 0;
        }
    }

/*----------------------activity-end----------------------------*/
/*----------------------candidate-start----------------------------*/

    /*获取候选人人数*/
    function getCandidateNum($key){
        $db  = $this->db;
        $key = addslashes(sprintf("%s", $key));
        $db->query("SELECT count(uniquekey) AS candidateNum FROM candidate WHERE belong = '$key'");
        $res_json = json_encode($db->results, JSON_UNESCAPED_UNICODE);
        return $res_json;    
    }

    /*获取全部候选人信息*/
    function getAllCandidateInfo($key){
        $db  = $this->db;
        $key = addslashes(sprintf("%s", $key));
        $db->query("SELECT uniquekey,name,votes,introduction,type,imgurl,linkurl,linkcover,videourl,audiourl FROM candidate WHERE belong = '$key' ORDER BY votes DESC");
        $res_array = $db->results;
        $i = 0;
        while (isset($res_array[$i])) {
            $res_array[$i]['rank'] = $i+1;
            $i++;
        }
        $res_json = json_encode($res_array, JSON_UNESCAPED_UNICODE);
        return $res_json;     
    }

    /*获取单个候选人信息*/
    function getCandidateInfo($ackey, $cankey){
        $db  = $this->db;
        $ackey  = addslashes(sprintf("%s", $ackey));
        $cankey = addslashes(sprintf("%s", $cankey));
        $db->query("SELECT * FROM candidate WHERE belong = '$ackey' AND uniquekey = '$cankey'");
        $res_json = json_encode($db->results, JSON_UNESCAPED_UNICODE);
        return $res_json;   
    }

    /*手动输入候选人信息*/
    public function addCandidate($candidate_name, $candidate_contact, $candidate_key, $candidate_intro, $candidate_type, $activity_key, $candidate_media){
        $db = $this->db;
        //数据过滤
        $candidate_name    = addslashes(sprintf("%s", $candidate_name));
        $candidate_contact = addslashes(sprintf("%s", $candidate_contact));
        $candidate_key     = addslashes(sprintf("%s", $candidate_key));
        $candidate_intro   = addslashes(sprintf("%s", $candidate_intro));
        $activity_key      = addslashes(sprintf("%s", $activity_key));
        $candidate_media   = addslashes(sprintf("%s", $candidate_media));
        $candidate_type    = intval($candidate_type);
        if ($candidate_type == 0 || $candidate_type == 3) {
            $db->query("INSERT INTO candidate (name, contact, uniquekey, introduction, belong, type) VALUES ('$candidate_name', '$candidate_contact', '$candidate_key', '$candidate_intro', '$activity_key', '$candidate_type')");
        }else if ($candidate_type == 1) {
            $db->query("INSERT INTO candidate (name, contact, uniquekey, introduction, belong, videourl, type) VALUES ('$candidate_name', '$candidate_contact', '$candidate_key', '$candidate_intro', '$activity_key', '$candidate_media', '$candidate_type')");
        }else if ($candidate_type == 2) {
            $db->query("INSERT INTO candidate (name, contact, uniquekey, introduction, belong, linkurl, type) VALUES ('$candidate_name', '$candidate_contact', '$candidate_key', '$candidate_intro', '$activity_key', '$candidate_media', '$candidate_type')");
        }
        if (!$db->code) {
            return $candidate_key;
        }else{
            return 0;
        }
    }

    /*修改候选人信息*/
    public function changeCandidate($candidate_name, $candidate_contact, $candidate_key, $candidate_intro, $candidate_type, $activity_key, $candidate_media, $imgJson){
        $db = $this->db;
        //数据过滤
        $candidate_name    = addslashes(sprintf("%s", $candidate_name));
        $candidate_contact = addslashes(sprintf("%s", $candidate_contact));
        $candidate_key     = addslashes(sprintf("%s", $candidate_key));
        $candidate_intro   = addslashes(sprintf("%s", $candidate_intro));
        $activity_key      = addslashes(sprintf("%s", $activity_key));
        $candidate_media   = addslashes(sprintf("%s", $candidate_media));
        // $imgJson           = addslashes(sprintf("%s", $imgJson));
        $candidate_type    = intval($candidate_type);
        if ($candidate_type == 0 || $candidate_type == 3) {
            $db->query("SELECT imgurl FROM candidate WHERE belong = '$activity_key' AND uniquekey = '$candidate_key'");
            $res = $db->results;
            $res = $res[0]['imgurl'];
            $res_array = json_decode($res, 1);
            $img_array = json_decode($imgJson, 1);
            foreach ($img_array as $key => $value) {
                $key = array_search($value, $res_array);
                if ($key !== false) array_splice($res_array, $key, 1);
            }
            $res_json = json_encode($res_array, JSON_UNESCAPED_UNICODE);
            $db->query("UPDATE candidate SET name = '$candidate_name', contact = '$candidate_contact', introduction = '$candidate_intro', type = '$candidate_type', imgurl = '$res_json' WHERE belong = '$activity_key' AND uniquekey = '$candidate_key'");
        }else if ($candidate_type == 1) {
            $db->query("UPDATE candidate SET name = '$candidate_name', contact = '$candidate_contact', introduction = '$candidate_intro', type = '$candidate_type', videourl = '$candidate_media' WHERE belong = '$activity_key' AND uniquekey = '$candidate_key'");
        }else if ($candidate_type == 2) {
            $db->query("UPDATE candidate SET name = '$candidate_name', contact = '$candidate_contact', introduction = '$candidate_intro', type = '$candidate_type', linkurl = '$candidate_media' WHERE belong = '$activity_key' AND uniquekey = '$candidate_key'");
        }
        if (!$db->code) {
            return $candidate_key;
        }else{
            return 0;
        }
    }

    // 设置候选人图片
    function setCandidateImg($fileName, $candidate_key){
        $db = $this->db;
        $fileName      = addslashes(sprintf("%s", $fileName));
        $candidate_key = addslashes(sprintf("%s", $candidate_key));
        $db->query("SELECT imgurl FROM candidate WHERE uniquekey = '$candidate_key'");
        $res = $db->results[0][0];
        if (!$res) {
            $res_array = array();
            $res_array[] = $fileName;
            $res_json    = json_encode($res_array, JSON_UNESCAPED_UNICODE);
            $db->query("UPDATE candidate SET imgurl = '$res_json' WHERE uniquekey = '$candidate_key'");
        }else{
            $res_array   = json_decode($res, 1);
            $res_array[] = $fileName;
            $res_json    = json_encode($res_array, JSON_UNESCAPED_UNICODE);
            $db->query("UPDATE candidate SET imgurl = '$res_json' WHERE uniquekey = '$candidate_key'"); 
        }
        if (!$db->code) {
            return 1;
        }else{
            return 0;
        }
    }

    // 设置外链封面
    function setCandidateLinkCover($fileName, $candidate_key){
        $db = $this->db;
        $fileName      = addslashes(sprintf("%s", $fileName));
        $candidate_key = addslashes(sprintf("%s", $candidate_key));
        $db->query("UPDATE candidate SET linkcover = '$fileName' WHERE uniquekey = '$candidate_key'");
        if (!$db->code) {
            return 1;
        }else{
            return 0;
        }
    }

    // 设置音频
    function setCandidateAudio($fileName, $candidate_key){
        $db = $this->db;
        $fileName      = addslashes(sprintf("%s", $fileName));
        $candidate_key = addslashes(sprintf("%s", $candidate_key));
        $db->query("UPDATE candidate SET audiourl = '$fileName' WHERE uniquekey = '$candidate_key'");
        if (!$db->code) {
            return 1;
        }else{
            return 0;
        }
    }

    /*搜索候选人*/
    function searchCan($key, $keyword){
        $db = $this->db;
        $key           = addslashes(sprintf("%s", $key));
        $candidate_key = addslashes(sprintf("%s", $keyword));
        $db->query("SELECT * FROM candidate WHERE name like '%$keyword%' AND belong = '$key'");
        $res_json = json_encode($db->results, JSON_UNESCAPED_UNICODE);
        return $res_json;       
    }

/*----------------------candidate-end----------------------------*/



    /*删除候选人*/

    /*删除投票人*/

    /*修改候选人信息*/

    /*修改投票人信息*/
}


?>