<?php
include_once '../lib/Activity.class.php';
include_once '../lib/WeiXin.class.php';
include_once '../lib/WeiBo.class.php';
include_once '../lib/QQ.class.php';
include_once '../VoteAdmin/pages/config.php';

$ac = new Activity();

/*获取单个候选人信息*/
$ackey     = $_GET['ackey'];
$cankey    = $_GET['cankey'];
$can_json  = $ac->getCandidateInfo($ackey, $cankey);
$can_array = json_decode($can_json, 1) ;
$can_array = $can_array[0];

/*获取活动数据*/
$acInfo_json = $ac->getActivityInfo($ackey);
$acInfo_array = json_decode($acInfo_json, 1);
$acInfo_array = $acInfo_array[0];

/*生成第三方登录链接*/
//微信
$wx    = new WeiXin();
$wxUrl = $wx->makeUrl("http://new.weixin.sky31.com/SkyVote/VotePages/index.php?ackey=$ackey&login=wx");
//微博
$wb    = new WeiBo('2939687442','2371fcb02c03238732f591f839f6b4da', 'http://new.weixin.sky31.com/SkyVote/VotePages/index.php', $ackey.'wb');
$wbUrl = $wb->makeUrl();
//QQ
$qq    = new QQ('101401064', '01615d475f415bb09e4b6638a1a6b415', "http://new.weixin.sky31.com/SkyVote/VotePages/index.php?ackey=$ackey&login=qq");
$qqUrl = $qq->makeUrl();

/*获取用户信息*/
session_start();
if (isset($_SESSION["openId"]) && isset($_SESSION["nickName"]) && isset($_SESSION["plat"]) && isset($_SESSION['imgUrl'])) {
    $openId   = $_SESSION["openId"];
    $nickName = $_SESSION["nickName"];
    $imgUrl   = $_SESSION["imgUrl"];
    $plat     = $_SESSION["plat"];
}


?>


<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><!-- IE浏览器的兼容解决 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"><!-- 移动端放大缩小控制 -->
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>个人详情——<?php echo $acInfo_array['activity_name']; ?></title>
    <!-- mui-0.9.15-css -->
    <link href="./css/mui/mui.min.css" rel="stylesheet" type="text/css" />
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-2.1.1.min.js"></script>
    <!-- lazyload -->
    <script src="js/lazyload.min.js"></script>
    <script>
        $(document).ready(function(){
            /*lazyload*/
            $("img.view-img").lazyload({ threshold : 100 ,effect : "fadeIn", failurelimit : 10 });
        });
    </script>
    <!-- Font Awesome 4.7.0 -->
    <link href="http://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/font-awesome/4.7.0/fonts/fontawesome-webfont.svg">
    <!-- mycss -->
    <link href="./css/mycss.css" rel="stylesheet" type="text/css" />
    <!-- photoswipe -->
    <link href="js/photoswipe/photoswipe.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="js/photoswipe/klass.min.js"></script>
    <script type="text/javascript" src="js/photoswipe/code.photoswipe-3.0.5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            var myPhotoSwipe = Code.PhotoSwipe.attach( window.document.querySelectorAll('#img a'), { enableMouseWheel: false , enableKeyboard: false } );
        }, false);
    </script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div style="display: none;">
        <input type="hidden" id="openId" value="<?php if(isset($openId))echo $openId;else echo ''; ?>">
        <input type="hidden" id="plat" value="<?php if(isset($plat))echo $plat;else echo ''; ?>">
        <input type="hidden" id="username" value="<?php if(isset($nickName))echo $nickName;else echo ''; ?>">
        <input type="hidden" id="ip" value="">
        <!-- ..................................................... -->
        <input type="hidden" id="ackey" value="<?php echo $ackey; ?>">
        <input type="hidden" id="cankey" value="<?php echo $cankey; ?>">
    </div>
    <!-- 背景 -->
    <div id="background" style="background-image: url(http://<?php echo Location; ?>/VoteAdmin/pages/activity/img/bg/<?php echo $acInfo_array['ac_img']; ?>);">
        <!-- 悬浮块 -->
        <div id="container" class="mui--z3">
            <!-- 详情页概览信息 -->      
            <div id="userInfo" style="overflow: hidden;padding-top: 10px;">
                <div id="username" class="mui--text-body1 mui--text-right" type="hidden" style="display: inline;float: right;"><?php if(isset($nickName))echo $nickName;else echo '未登录'; ?></div>
                <img src="<?php if(isset($imgUrl))echo $imgUrl;else echo 'http://yfree.cc/VoteForMe/VotePages/images/nologin.jpg'; ?>" width="20px" height="20px" style="float: right;" />
            </div>  
            <div id="can-topinfo" style="overflow: hidden;">
                <div id="info-left">
                    <div id="can-name" class="p-limit" style="-webkit-line-clamp: 2;"><?php echo $can_array['name']; ?></div>
                    <div id="can-btninfo" style="overflow: hidden;">
                        <div id="btninfo-votes">票数：<span class="view-votes"><?php echo $can_array['votes'] ?></span></div>
                        <!-- <div id="btninfo-rank">排名：<span>114</span></div> -->
                    </div>
                </div>
                <div id="info-right" class="mui--pull-right">
                    <button id="btn-vote" class="mui-btn mui-btn--primary mui-btn--fab">投票</button>
                </div>
            </div>
            <!-- 详情页图片 -->
            <?php
            $imgurl = $can_array['imgurl'];
            $imgurl_array = json_decode($imgurl, 1);
            foreach ($imgurl_array as $key => $value) {
                $html = '<div id="img"><a href="../VoteAdmin/pages/candidate/img/img/'.$value.'"><img class="view-img mui--z3" data-original="../VoteAdmin/pages/candidate/img/img/'.$value.'"></a></div>';
                echo $html;
            }
            ?>  
            <!-- 候选人简介 -->       
            <div id="can-declar">
                <div id="declar-title">作品简介</div>
                <div id="declar-content" class="mui--text-body2"><?php echo $can_array['introduction'] ?></div><br>
            </div>
            <!-- 返回 -->
            <button class="mui-btn mui-btn--primary" id="return-btn">返回首页</button>
            <div id="footer">Copyright © 2017 Arony & Slight</div>
        </div>  
    </div>
    <!-- the overlay-login -->
    <div id="myNav-login" class="overlay">
      <!-- Button to close the overlay navigation -->
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav3()">&times;</a>
      <!-- Overlay content -->
      <div class="overlay-content">
        <p><i class="fa fa-warning"></i>&nbsp;&nbsp;请先登录</p>
        <br/><br/><br/><br/>
        <?php
            if ($acInfo_array['platformlimit'] == 0) {
                $html = "<a href='{$wxUrl}'><i class='fa fa-wechat' style='display: none;'>&nbsp;微信登录</a>
                         <a href='{$wbUrl}'><i class='fa fa-weibo' style='display: none;'>&nbsp;微博登录</a>
                         <a href='{$qqUrl}'><i class='fa fa-qq' style='display: none;'>&nbsp;ＱＱ登录</a>
                         <a href='{$qqUrl}'><i class='fa fa-user' style='display: none;'>&nbsp;帐号登录</a>";
                echo $html;
            }elseif ($acInfo_array['platformlimit'] == 1) {
                $html = "<a href='{$qqUrl}'><i class='fa fa-qq' style='display: none;'>&nbsp;ＱＱ登录</a>";
                echo $html;
            }elseif ($acInfo_array['platformlimit'] == 2) {
                 $html = "<a href='{$wxUrl}'><i class='fa fa-wechat' style='display: none;'>&nbsp;微信登录</a>";
                echo $html;
            }elseif ($acInfo_array['platformlimit'] == 3) {
                 $html = "<a href='{$wbUrl}'><i class='fa fa-weibo' style='display: none;'>&nbsp;微博登录</a>";
                echo $html;
            }elseif ($acInfo_array['platformlimit'] == 4) {
                 $html = "<a href='{$wxUrl}'><i class='fa fa-wechat' style='display: none;'>&nbsp;微信登录</a>
                          <a href='{$qqUrl}'><i class='fa fa-qq' style='display: none;'>&nbsp;ＱＱ登录</a>";
                echo $html;
            }elseif ($acInfo_array['platformlimit'] == 5) {
                 $html = "<a href='{$wbUrl}'><i class='fa fa-weibo' style='display: none;'>&nbsp;微博登录</a>
                          <a href='{$qqUrl}'><i class='fa fa-qq' style='display: none;'>&nbsp;ＱＱ登录</a>";
                echo $html;
            }elseif ($acInfo_array['platformlimit'] == 6) {
                 $html = "<a href='{$wxUrl}'><i class='fa fa-wechat' style='display: none;'>&nbsp;微信登录</a>
                        <a href='{$wbUrl}'><i class='fa fa-weibo' style='display: none;'>&nbsp;微博登录</a>";
                echo $html;
            }elseif ($acInfo_array['platformlimit'] == 7) {
                 $html = "<a href='{$qqUrl}'><i class='fa fa-user' style='display: none;'>&nbsp;帐号登录</a>";
                echo $html;
            }
        ?>
      </div>
    </div>

    <!-- mui-0.9.15-js -->
    <script src="./js/mui/mui.min.js"></script>
    <!-- myjs -->
    <script src="./js/myjs.js"></script>
  </body>
</html>










