<?php
include_once '../lib/Activity.class.php';
include_once '../lib/WeiXin.class.php';
include_once '../lib/WeiBo.class.php';
include_once '../lib/QQ.class.php';
include_once '../VoteAdmin/pages/config.php';

$ac = new Activity();

/*获取搜索的候选人数据*/
$ackey     = $_GET['ackey'];
$keyword   = $_GET['keyword'] ;
$can_json  = $ac->searchCan($ackey, $keyword);
$can_array = json_decode($can_json, 1);

/*获取活动数据*/
$acInfo_json  = $ac->getActivityInfo($ackey);
$acInfo_array = json_decode($acInfo_json, 1);
$acInfo_array = $acInfo_array[0];

/*获取累计投票数*/
$totalVotes_json  = $ac->getTotalVotes($ackey);
$totalVotes_array = json_decode($totalVotes_json, 1);


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
if (isset($_SESSION["openId"]) && isset($_SESSION["nickName"]) && isset($_SESSION["plat"])) {
    $openId   = $_SESSION["openId"];
    $nickName = $_SESSION["nickName"];
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
    <title>搜索结果——<?php echo $acInfo_array['activity_name']; ?></title>
    <!-- mui-0.9.15-css -->
    <link href="./css/mui/mui.min.css" rel="stylesheet" type="text/css" />
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-2.1.1.min.js"></script>
    <!-- lazyload -->
    <script src="js/lazyload.min.js"></script>
    <script>
        $(document).ready(function(){
            /*lazyload*/
            $("img.can-img").lazyload({ threshold : 100 ,effect : "fadeIn" , failurelimit : 10});
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
            var myPhotoSwipe = Code.PhotoSwipe.attach( window.document.querySelectorAll('.img-block a'), { enableMouseWheel: false , enableKeyboard: false } );
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
    </div>
    <!-- 背景 -->
    <div id="background" style="background-image: url(http://<?php echo Location; ?>/VoteAdmin/pages/activity/img/bg/<?php echo $acInfo_array['ac_img']; ?>);">
        <!-- 悬浮块 -->
        <div id="container" class="mui--z3">
            <!-- 活动信息及相关控制器 -->
            <div id="ac-info">
                <div id="userInfo" style="overflow: hidden;padding-bottom:10px;" >
                    <div id="username" class="mui--text-body1 mui--text-right" type="hidden" style="display: inline;float: right;"><?php if(isset($nickName))echo $nickName;else echo '未登录'; ?></div>
                    <img src="<?php if(isset($imgUrl))echo $imgUrl;else echo 'http://yfree.cc/VoteForMe/VotePages/images/nologin.jpg'; ?>" width="20px" height="20px" style="float: right;" />
                </div> 
                <div id="ac-intro"><b><?php echo $acInfo_array['intro']; ?></b></div>
                <div class="line mui-divider"></div>
                <div id="ac-rules">
                <?php 
                    $rules_json  = $acInfo_array['rules'];
                    $rules_array = json_decode($rules_json, 1);
                    $rule_content = '*投票平台：';
                    $i = 1;
                    if ($acInfo_array['platformlimit'] == 0) {
                        $rule_content .= "微信、微博、QQ";
                    }else if ($acInfo_array['platformlimit'] == 1) {
                        $rule_content .= "QQ";
                    }else if ($acInfo_array['platformlimit'] == 2) {
                        $rule_content .= "微信";
                    }else if ($acInfo_array['platformlimit'] == 3) {
                        $rule_content .= "微博";
                    }else if ($acInfo_array['platformlimit'] == 4) {
                        $rule_content .= "QQ、微信";
                    }else if ($acInfo_array['platformlimit'] == 5) {
                        $rule_content .= "QQ、微博";
                    }else if ($acInfo_array['platformlimit'] == 6) {
                        $rule_content .= "微信、微博";
                    }else if ($acInfo_array['platformlimit'] == 7) {
                        $rule_content .= "管理员自定义";
                    }
                    $rule_content .= "<br/>*投票时间：".$acInfo_array['starttime']."——".$acInfo_array['endtime'];
                    $rule_content .= "<br/>*投票规则：系统可以检测刷票，请不要刷票<br/>";
                    if ($rules_array) {
                        foreach ($rules_array as $key => $value) {
                            $rule_content .= $i.".".$value."<br/>";
                            $i++;
                        }
                    }
                    echo $rule_content;
                ?>
                </div>
                <!-- 计数器 -->
                <div id="ac-counter">
                <table>
                    <tbody>
                        <tr>
                            <td style="border-right: 1px dashed #ECF0F1;">搜索结果<div class="number"><?php echo count($can_array); ?></div></td>
                            <td>累计投票<div class="number"><?php echo $totalVotes_array[0]; ?></div></td>
                            <td style="border-left: 1px dashed #ECF0F1;">访问次数<div class="number"><?php echo $acInfo_array['vv']; ?></div></td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <!-- 控制器 -->
                <div id="ac-control">
                    <!-- 搜索 -->
                    <div id="ac-search" style="overflow: hidden;">
                        <form action='index-search.php' method="get" class="mui-form--inline">
                            <input type="hidden" id="ackey" value="<?php echo $ackey; ?>" name="ackey">
                            <div id="search-input" class="mui-textfield">
                                <input type="text" placeholder="输入关键字进行搜索" style="height: 36px;" name="keyword" id="keyword" required>
                            </div>
                            <div id="button"><div id="search-btn" class="mui-btn mui-btn--primary">搜索</div></div>
                            
                        </form>
                    </div>
                    <!-- 筛选 -->
                    <!-- <div id="ac-btn">
                        <button class="fliter mui-btn mui-btn--small mui-btn--primary" style="margin: 0% 1%;">时间排序</button>
                        <button class="fliter mui-btn mui-btn--small mui-btn--primary" style="margin: 0% 1%;">票数排序</button>
                        <button onclick="openNav1()" class="fliter mui-btn mui-btn--small mui-btn--primary" style="margin: 0% 1%;background-color: #673AB7;">查看排名</button>
                        <button onclick="openNav2()" class="fliter mui-btn mui-btn--small mui-btn--primary" style="margin: 0% 1%;background-color: #673AB7;">反馈问题</button>
                    </div> -->
                    <button class="mui-btn mui-btn--small mui-btn--primary" id="return-btn" style="margin-top: 5px;background-color: #009688;">返回首页</button>
                </div>
            </div>
            <!-- 候选人信息流 -->       
            <div id="can-info" style="overflow: hidden;">
                <div id="can-list">
                    <?php
                        foreach ($can_array as $key => $value) {
                            if ($value['type'] == 0) {
                                $imgurl = $value['imgurl'];
                                $imgurl_array = json_decode($imgurl, 1);
                                $html = '<div class="img-block mui--z3">
                                            <a href="../VoteAdmin/pages/candidate/img/img/'.$imgurl_array[0].'"><img class="can-img" data-original="../VoteAdmin/pages/candidate/img/img/'.$imgurl_array[0].'"></a>
                                            <div class="img-info">
                                                <div class="can-title p-limit">'.$value['name'].'</div>
                                                <div class="can-intro p-limit"><span class="votes" cankey='.$value['uniquekey'].'>'.$value['votes'].'</span>票&nbsp;&nbsp;&nbsp;'.$value['introduction'].'</div>
                                            </div>
                                            <div class="img-btn" style="overflow: hidden;">
                                                <div class="btn-href mui--pull-left mui--text-center" cankey='.$value['uniquekey'].'>查看更多</div>
                                                <div class="btn-vote mui--pull-right mui--text-center" cankey='.$value['uniquekey'].'>投票</div>
                                            </div>
                                        </div>';
                                echo $html;
                            }else if ($value['type'] == 1) {
                                $videoUrl = $value['videourl'];
                                preg_match('/(.*)\/(.*?)\.html/', $videoUrl, $match);
                                $videoCode = $match[2];
                                $html = '<div class="img-block mui--z3">
                                            <iframe frameborder="0" width="100%" height="100%" src="https://v.qq.com/iframe/player.html?vid='.$videoCode.'&tiny=1&auto=0" allowfullscreen></iframe>
                                            <div class="img-info">
                                                <div class="can-title p-limit">'.$value['name'].'</div>
                                                <div class="can-intro p-limit"><span class="votes" cankey='.$value['uniquekey'].'>'.$value['votes'].'</span>票&nbsp;&nbsp;&nbsp;'.$value['introduction'].'</div>
                                            </div>
                                            <div class="img-btn" style="overflow: hidden;">
                                                <div class="btn-link mui--pull-left mui--text-center" linkurl='.$value['videourl'].'>查看更多</div>
                                                <div class="btn-vote mui--pull-right mui--text-center" cankey='.$value['uniquekey'].'>投票</div>
                                            </div>
                                        </div>';
                                echo $html;  
                            }else if ($value['type'] == 2) {
                                $linkcover = $value['linkcover'];
                                $html = '<div class="img-block mui--z3">
                                            <a href="'.$value['linkurl'].'"><img class="can-img" data-original="http://'.Location.'/VoteAdmin/pages/candidate/img/cover/'.$linkcover.'"></a>
                                            <div class="img-info">
                                                <div class="can-title p-limit">'.$value['name'].'</div>
                                                <div class="can-intro p-limit"><span class="votes" cankey='.$value['uniquekey'].'>'.$value['votes'].'</span>票&nbsp;&nbsp;&nbsp;'.$value['introduction'].'</div>
                                            </div>
                                            <div class="img-btn" style="overflow: hidden;">
                                                <div class="btn-link mui--pull-left mui--text-center" linkurl='.$value['linkurl'].'>查看更多</div>
                                                <div class="btn-vote mui--pull-right mui--text-center" cankey='.$value['uniquekey'].'>投票</div>
                                            </div>
                                        </div>';
                                echo $html; 
                            }else if($value['type'] == 3){
                                $html = '<div class="img-block mui--z3">
                                            <audio controls="controls" style="width:100%;"><source src="'.'http://'.Location.'/VoteAdmin/pages/candidate/audio/'.$value['audiourl'].'" type="audio/mp3" ></audio>
                                            <div class="img-info">
                                                <div class="can-title p-limit">'.$value['name'].'</div>
                                                <div class="can-intro p-limit"><span class="votes" cankey='.$value['uniquekey'].'>'.$value['votes'].'</span>票&nbsp;&nbsp;&nbsp;'.$value['introduction'].'</div>
                                            </div>
                                            <div class="img-btn" style="overflow: hidden;">
                                                <div style="width:100%;" class="btn-vote mui--pull-right mui--text-center" cankey='.$value['uniquekey'].'>投票</div>
                                            </div>
                                        </div>';
                                echo $html; 
                            }
                        }
                    ?>
                </div>
            </div>
            <div id="footer">Copyright © 2017 Arony & Slight</div>
        </div>  
    </div>
    <!-- The overlay-rank -->
    <div id="myNav-rank" class="overlay">
      <!-- Button to close the overlay navigation -->
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav1()">&times;</a>
      <!-- Overlay content -->
      <div class="overlay-content">
        <a href="#" class="p-limit">About</a>
        <a href="#">Services</a>
        <a href="#">Clients</a>
        <a href="#">Contact</a>
        <a href="#">Contact</a>
        <a href="#">Contact</a>
        <a href="#">Contact</a>
        <a href="#">Contact</a>
        <a href="#">Contact</a>
        <a href="#">Contact</a>
        <a href="#">Contact</a>
      </div>
    </div>
    <!-- The overlay-ques -->
    <div id="myNav-ques" class="overlay">
      <!-- Button to close the overlay navigation -->
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav2()">&times;</a>
      <!-- Overlay content -->
      <div class="overlay-content" style="top: 19%;">
        
          <div class="mui-textfield mui-panel mui--text-center" id="ques">
            <textarea placeholder="请填写反馈内容" style="min-height: 110px;"></textarea>
          </div>
          <a href="#">提交</a>
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










