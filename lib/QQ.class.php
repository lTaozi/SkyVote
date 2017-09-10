<?php

/**
*  
*/
class QQ
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $access_token;
    private $openId;
    
    function __construct($client_id, $client_secret, $redirect_uri)
    {
        $this->client_id     = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri  = $redirect_uri;
    }

    /*
    * 获取Authorization Code
    */
    function makeUrl(){
        // session_start();
        //state参数用于防止CSRF攻击，成功授权后回调时会原样带回
        $_SESSION['state'] = md5(uniqid(rand(), TRUE));
        //拼接URL     
        $url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=" . $this->client_id . "&redirect_uri=" . urlencode($this->redirect_uri) . "&state=" . $_SESSION['state']; 
        return $url;
    }

    /*
    * 获取access_token
    */
    function _getAccessToken(){
        $code = $_GET['code'];
        $url = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id='. $this->client_id .'&client_secret='. $this->client_secret .'&code=' . $code . '&redirect_uri=' . urlencode($this->redirect_uri);
        $res = file_get_contents($url);
        preg_match_all('/access_token=(.*?)&/', $res, $matches);
        $access_token = $matches[1][0];
        $this->access_token = $access_token;
        return $access_token;
    }

    /*
    * 获取openid
    */
    function _getOpenId(){
        $url = 'https://graph.qq.com/oauth2.0/me?access_token='.$this->access_token;
        $res_json = file_get_contents($url);
        if (strpos($res_json, "callback") !== false)
        {
            $lpos = strpos($res_json, "(");
            $rpos = strrpos($res_json, ")");
            $res_json  = substr($res_json, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($res_json);
            if (isset($msg->error))
            {
               echo "<h3>error:</h3>" . $msg->error;
               echo "<h3>msg  :</h3>" . $msg->error_description;
               exit;
            }
            $openId = $msg->openid;
            $this->openId = $openId;
            return $openId;
        }
    }

    /*
    * 获取用户信息
    */
    function getUserInfo(){
        $this->_getAccessToken();
        $this->_getOpenId();
        $url = 'https://graph.qq.com/user/get_user_info?access_token='.$this->access_token.'&oauth_consumer_key='.$this->client_id.'&openid='.$this->openId;
        $res_json    = file_get_contents($url);
        $res_array   = json_decode($res_json, 1);
        $res_array['openid'] = $this->openId;
        $res         = json_encode($res_array, JSON_UNESCAPED_UNICODE);
        return $res;
    }
}
?>



