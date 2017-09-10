<?php
/**
* 作者：Yang
*
*
*/
include_once 'Snoopy.class.php';

/**
* 
*/
class WeiBo
{
	private $client_id;
	private $redirect_uri;
	protected $client_secret;
	protected $access_token;
	protected $snoopy;
	protected $code;
  protected $uid;
	protected $state;
    
    function __construct($client_id, $client_secret, $redirect_uri, $state)
    {
        $this->state = $state; 
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $snoopy = new Snoopy();
        $this->snoopy = $snoopy;
        if(isset($_GET['code'])){
	        $this->code   = $_GET['code'];
	          	
        }
    }

    /**
    * 获取access_token(单值)
    */
    function getAccessToken(){
    	$snoopy = $this->snoopy;
    	$data = array('client_id'     => $this->client_id,
    				        'client_secret' => $this->client_secret,
    				        'redirect_uri'  => $this->redirect_uri,
    				        'code'          => $this->code,
    				        'grant_type'    => 'authorization_code');
    	$url  = 'https://api.weibo.com/oauth2/access_token';
    	$snoopy->submit($url, $data);
      $res = $snoopy->results;
      $result = json_decode($res, true);
      $access_token = $result["access_token"];
      $expires_in   = $result['expires_in']; //有效时间
      $this->uid    = $result['uid']; //用户id
      $this->access_token = $access_token;   //传入类全局变量
      return $access_token;
    }

    /*
    * 获取用户信息
    */
   	function getUserInfo(){
      $this->getAccessToken();      
   		$url = 'https://api.weibo.com/2/users/show.json?access_token='.$this->access_token.'&uid='.$this->uid;
        $res = file_get_contents($url);
        return $res;
   	}

   	/*
   	* 生成授权页面连接
   	*/
   	function makeUrl(){
   		$url = 'https://api.weibo.com/oauth2/authorize?client_id='.$this->client_id.'&redirect_uri='.$this->redirect_uri.'&state='.$this->state;
   		return $url;
   	}
}