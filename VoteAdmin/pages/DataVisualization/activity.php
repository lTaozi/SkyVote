<?php
/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/4/29 0029
 * Time: 16:33
 */

// 登录检查
include_once '../login/rootConfig.php';
session_start();
if (!isset($_SESSION['rootId']) || !isset($_SESSION['username']) || $_SESSION['rootId'] != RootId || $_SESSION['username'] != Username) {
  header("Location: ../login/login.php");
  exit;
}

include_once 'lib/config.php';
include_once 'lib/db.class.php';
include_once '../config.php';

$activity_key = isset($_GET['id']) ? $_GET['id'] : "";

$sql = "select * from activitys where activity_key = '$activity_key'";
$res = $dbClass->query($sql);
if (mysqli_num_rows($res) != 1) {
    exit('illegal parameter');
} else {
    $rs = $dbClass->getone($res);
}

// 剩余时间计算
$startTime      = date("Y年m月d日H:i:s", strtotime($rs['starttime']));
$startTimeUnix  = strtotime($rs['starttime']);
$endTime        = date("Y年m月d日H:i:s", strtotime($rs['endtime']));
$endTimeUnix    = strtotime($rs['endtime']);
if (time() > $endTimeUnix) {
  $remainTime = "已结束";
  $status = 'danger';
}else if(time() < $endTimeUnix && time() > $startTimeUnix){
  $remainTimeUnix = $endTimeUnix - time();
  $day    = floor($remainTimeUnix/(60*60*24));  //天数
  $hour   = floor(($remainTimeUnix - $day*(60*60*24))/3600);  //小时
  $minute = floor(($remainTimeUnix - $day*(60*60*24) - $hour*(3600))/60); //分钟
  $remainTime = $day."天".$hour."小时".$minute."分钟";
  $status = 'success';
}else{
  $remainTime = "未开始";
  $status = 'info';
}

// 活动平台
if ($rs['platformlimit'] == 0) {
    $plat = "微信、微博、QQ、自导入";
}elseif($rs['platformlimit'] == 1){
    $plat = "QQ";
}elseif($rs['platformlimit'] == 2){
    $plat = "微信";
}elseif($rs['platformlimit'] == 3){
    $plat = "微博";
}elseif($rs['platformlimit'] == 4){
    $plat = "QQ、微信";
}elseif($rs['platformlimit'] == 5){
    $plat = "QQ、微博";
}elseif($rs['platformlimit'] == 6){
    $plat = "微信、微博";
}elseif($rs['platformlimit'] == 7){
    $plat = "自导入";
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SkyVote | 活动详情</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
    <!-- jQuery 2.2.0 -->
    <script src="../../plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <!-- lazyload -->
    <script src="../../../VotePages/js/lazyload.min.js"></script>
    <script>
        $(document).ready(function(){
            /*lazyload*/
            $("img.can-img").lazyload({ threshold : 100 ,effect : "fadeIn" , failurelimit : 10});
        });
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">Sk<b>V</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">Sky<b>Vote</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['username']; ?> - <?php echo $_SESSION['identity']; ?>
                  <small><?php echo date("Y年m月d日") ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <?php
                if ($_SESSION['rootId'] == RootId) {
                  $html = '<li class="user-body">
                            <div class="row">
                              <div class="col-xs-4 text-center">
                                <a href="#">用户管理</a>
                              </div>
                              <div class="col-xs-4 text-center">
                                <a href="#">活动管理</a>
                              </div>
                              <div class="col-xs-4 text-center">
                                <a href="#">系统设置</a>
                              </div>
                            </div>
                            <!-- /.row -->
                          </li>';
                  echo $html;
                }
              ?>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">修改密码</a>
                </div>
                <div class="pull-right">
                  <a href="../login/loginOut.php" class="btn btn-default btn-flat">注销</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?php echo $_SESSION['username']; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> <?php echo $_SESSION['identity']; ?></a>
                </div>
            </div>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
              <li class="header">MAIN NAVIGATION</li>
              <li class="treeview">
                  <a href="../activity/activity-view.php">
                      <i class="fa fa-tasks"></i> <span>活动</span>
                      <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li><a href="../activity/activity-view.php">查看</a></li>
                      <li><a href="../activity/activity-add.php">新建</a></li>
                      <li><a href="../activity/activity-change.php"><del>修改</del></a></li>
                  </ul>
              </li>
              <li class="treeview active">
                  <a href="#">
                      <i class="fa fa-pie-chart"></i><span>可视数据</span>
                      <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                      <li  class="active"><a href="../DataVisualization"><i class="fa fa-circle-o"></i> 查看</a></li>
                  </ul>
              </li>
                <li class="header">LABELS</li>
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
                <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
            </ul>
        </section>

        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                活动详情
                <small>在此查看活动信息和候选人数据</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">投票可视化系统</li>
                <li class="active">活动详情</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-black"
                             style="background: url('http://<?php echo Location; ?>/VoteAdmin/pages/activity/img/bg/<?php echo $rs['ac_img'] ?>') center center;">
                            <h3 class="widget-user-username"><?php echo $rs['activity_name'] ?></h3>
                            <h5 class="widget-user-desc"><?php echo $rs['host'] ?></h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle"
                                 src="http://<?php echo Location; ?>/VoteAdmin/pages/activity/img/logo/<?php echo $rs['ac_logo'] ?>"
                                 alt="User Avatar">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">3,200</h5>
                                        <span class="description-text">SALES</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">13,000</h5>
                                        <span class="description-text">FOLLOWERS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">35</h5>
                                        <span class="description-text">PRODUCTS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">活动详情</h3>


                        </div>
                        <!-- /.box-header -->
                        <!--    PHP  执行         -->

                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>活动介绍</th>
                                    <th><?php echo $rs['intro'] ?></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>创建者</th>
                                    <th><?php echo $rs['creater'] ?></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>开始时间</th>
                                    <th><span class="label label-success"><?php echo $rs['starttime'] ?></span></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>结束时间</th>
                                    <th><span class="label label-info"><?php echo $rs['endtime'] ?></span></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>剩余时间</th>
                                    <th><span class="label label-<?php echo $status; ?>"><?php echo $remainTime; ?></span></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>创建时间</th>
                                    <th><?php echo $rs['createtime'] ?></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>更新周期</th>
                                    <th><?php echo $rs['refreshcycle'] ?></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>周期票数</th>
                                    <th><?php echo $rs['refreshballot'] ?></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>活动平台</th>
                                    <th><?php echo $plat ?></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>规则</th>
                                    <th><?php echo $rs['rules'] ?></th>
                                    <th></th>
                                </tr>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12">

                        <!-- /.box-header -->
                        <!--    PHP  执行         -->
                        <?php
                        $str = "";
                        $sql = "select * from candidate where belong = '$activity_key'";
                        $res = $dbClass->query($sql);
                        if (mysqli_num_rows($res) <= 0) {
                            $str .= "<tr><td colspan='12'>暂无候选人</td></tr>";
                        } else {
                            $i = 0;
                            while ($rs = $dbClass->getone($res)){
                                if ($rs['type'] == 0) {
                                    $rs['type'] = "0图片";
                                    $imgArray   = json_decode($rs['imgurl'], 1);
                                    $img        = $imgArray[0];
                                    $preview    = "<img class='can-img' style='width:160px; height: 90px;' data-original='http://".Location."/VoteAdmin/pages/candidate/img/img/{$img}' >";
                                }
                                if ($rs['type'] == 1) {
                                    $rs['type'] = "1视频";
                                    $videoUrl = $rs['videourl'];
                                    preg_match('/(.*)\/(.*?)\.html/', $videoUrl, $match);
                                    $videoCode = $match[2];
                                    $preview    = '<embed width="160px" height="90px" name="plugin" id="plugin" src="http://imgcache.qq.com/tencentvideo_v1/player/TPout.swf?vid='.$videoCode.'&amp;auto=0" type="application/x-shockwave-flash">';
                                }
                                if ($rs['type'] == 2) {
                                    $rs['type'] = "2外链";
                                    $linkUrl    = $rs['linkurl'];
                                    $preview    = "<img class='can-img' style='width:160px; height: 90px;' data-original='http://".Location."/VoteAdmin/pages/candidate/img/cover/{$rs['linkcover']}' ><br/><a href='$linkUrl'>外链</a>";
                                }
                                if ($rs['type'] == 3) {
                                    $rs['type'] = "3音频";
                                    $preview = '<audio controls="controls"><source src="'.'http://'.Location.'/VoteAdmin/pages/candidate/audio/'.$rs['audiourl'].'" type="audio/mp3" ></audio>';
                                }
                                $i ++;
                                $str .= "<tr>
                                    <th>{$i}</th>
                                    <th>{$preview}</th>
                                    <th>{$rs['name']}</th>
                                    <th>{$rs['votes']}</th>
                                    <th>{$rs['contact']}</th>
                                    <th>{$rs['introduction']}</th>
                                    <th>{$rs['type']}</th>
                                    <th><a href='candidate.php?id={$rs['uniquekey']}' target='_blank'>查看数据</a></th>
                                </tr>";
                            }
                        }

                        ?>

                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">候选人列表</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th></th>
                                        <th>姓名</th>
                                        <th>得票数</th>
                                        <th>联系方式</th>
                                        <th>简介</th>
                                        <th>资源类型</th>
                                        <!--
                                        <th>图片</th>
                                        <th>视频</th>
                                        <th>音频</th>
                                        <th>链接</th>
                                        -->
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $str?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th></th>
                                        <th>姓名</th>
                                        <th>得票数</th>
                                        <th>联系方式</th>
                                        <th>简介</th>
                                        <th>资源类型</th>
                                        <!--
                                        <th>图片</th>
                                        <th>视频</th>
                                        <th>音频</th>
                                        <th>链接</th>
                                        -->
                                        <th>操作</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.3.3
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
        reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-user bg-yellow"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                <p>New phone +1(800)555-1234</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                <p>nora@example.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-file-code-o bg-green"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                <p>Execution time 5 seconds</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Update Resume
                                <span class="label label-success pull-right">95%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Laravel Integration
                                <span class="label label-warning pull-right">50%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Back End Framework
                                <span class="label label-primary pull-right">68%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Allow mail redirect
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Other sets of options are available
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Expose author name in posts
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Allow the user to show his name in blog posts
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <h3 class="control-sidebar-heading">Chat Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Show me as online
                            <input type="checkbox" class="pull-right" checked>
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Turn off notifications
                            <input type="checkbox" class="pull-right">
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Delete chat history
                            <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                        </label>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<script>
    $(function () {
        $("#example1").DataTable();
    });
</script>
</body>
</html>
