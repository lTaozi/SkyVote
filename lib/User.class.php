<?php
$dir = dirname(__FILE__);
include_once $dir.'/DataBase.class.php';
date_default_timezone_set('PRC');  //时区设置

/**
* 添加一个用户
* 获取候选人信息
*/
class User
{
    private $openId;
    private $nickname;
    private $db;
    private $ackey;
    private $platform;
    private $userInfo;

    function __construct($openId, $nickname, $ackey, $platform, $userInfo)
    {
        $this->openId    = $openId;
        $this->nickname  = $nickname;
        $this->ackey     = $ackey;
        $this->platform  = $platform;
        $this->userInfo  = $userInfo;
        $db = new DataBase("votesystem");
        $this->db = $db;
        if (!$this->checkVoter()) {
            $this->addVoter();
        }
        if (!$this->checkUser()) {
            $this->addUser();
        }
    }

    /*添加新投票者*/
    function addVoter(){
        $db = $this->db;
        $openId   = $this->openId;
        $nickname = $this->nickname;
        $ackey    = $this->ackey;
        $platform = $this->platform;
        $date     = date("Y-m-d H:i:s");
        $db->query("INSERT INTO voter (uniquekey, nickname, platform, belong, addtime) VALUES ('$openId', '$nickname', '$platform', '$ackey', '$date')");
        if ($db->code) return 1;
        else return 0;
    }

    /*检查投票者是否存在*/
    function checkVoter(){
        $db = $this->db;
        $openId   = $this->openId;
        $ackey    = $this->ackey;
        $db->query("SELECT * FROM voter WHERE uniquekey = '$openId' AND belong = '$ackey'");
        $res = $db->results;
        if ($res) return 1;
        else return 0;
    }

    /*检查浏览者是否存在*/
    function checkUser(){
        $db = $this->db;
        $openId   = $this->openId;
        $ackey    = $this->ackey;
        $db->query("SELECT * FROM alluser WHERE uniquekey = '$openId'");
        $res = $db->results;
        if ($res) return 1;
        else return 0;
    }

    /*添加浏览者*/
    function addUser(){
        $db = $this->db;
        $openId   = $this->openId;
        $nickname = $this->nickname;
        $ackey    = $this->ackey;
        $platform = $this->platform;
        $userInfo = $this->userInfo;
        $date     = date("Y-m-d H:i:s");
        $db->query("INSERT INTO alluser (uniquekey, name, platform, addtime, detail) VALUES ('$openId', '$nickname', '$platform', '$date', '$userInfo')");
        if ($db->code) return 1;
        else return 0;        
    }
}