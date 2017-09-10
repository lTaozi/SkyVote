<?php
include_once '../login/rootConfig.php';
include_once '../../../lib/Activity.class.php';
include_once '../config.php';

// 登录检查
session_start();
if (!isset($_SESSION['rootId']) || !isset($_SESSION['username']) || $_SESSION['rootId'] != RootId || $_SESSION['username'] != Username) {
  header("Location: ../login/login.php");
  exit;
}

$activity = new Activity();

// 获取数据
$ackey  = $_GET['ackey'];
$acInfo_json  = $activity->getActivityInfo($ackey);
$acInfo_array = json_decode($acInfo_json, 1)[0];
// 解析数据
$ac_name   = $acInfo_array['activity_name'];
$startTime = date("Y-m-d H:i:s", strtotime($acInfo_array['starttime']));
$endTime   = date("Y-m-d H:i:s", strtotime($acInfo_array['endtime']));
// 候选人数
$candidateNum_json  = $activity->getCandidateNum($ackey);
$candidateNum_array = json_decode($candidateNum_json, 1);
$candidateNum = $candidateNum_array[0]['candidateNum'];

// 获取所有候选人信息
$can_json  = $activity->getAllCandidateInfo($ackey);
$can_array = json_decode($can_json, 1) ;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SkyVote | 查看候选人</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
  <!-- Ionicons -->
  <link href="https://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.css" rel="stylesheet">
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="../../plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="../../plugins/fullcalendar/fullcalendar.print.css" media="print">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/iCheck/flat/blue.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- Mycss -->
  <link rel="stylesheet" href="../../bootstrap/css/mycss.css">
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="../../../VotePages/js/jquery-2.1.1.min.js"></script>
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
        <li class="treeview active">
          <a href="mailbox.html">
            <i class="fa fa-tasks"></i> <span>活动</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="../activity/activity-view.php">查看</a></li>
            <li><a href="../activity/activity-add.php">新建</a></li>
            <li><a href="#">修改</a></li>
          </ul>
        </li>
          <li class="treeview">
              <a href="#">
                  <i class="fa fa-pie-chart"></i>
                  <span>可视数据</span>
                  <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li><a href="../DataVisualization"><i class="fa fa-circle-o"></i> 查看</a></li>

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
        <?php echo $ac_name; ?>
        <small>在此查看候选人信息</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Candidate</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="../activity/activity-view.php" class="btn btn-primary btn-block margin-bottom">所有活动</a>
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">活动信息</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-green"></i> <?php echo $ac_name; ?></a></li>
                <li><a href="#"><i class="fa fa-circle-o text-blue"></i> <?php echo $startTime; ?>至<?php echo $endTime; ?></a></li>
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> 候选人数<span class="label label-primary pull-right"><?php echo $candidateNum; ?></span></a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">候选人</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><i class="fa fa-circle-o text-red"></i> 查看候选人</a></li>
                <li><a href="candidate-add.php?ackey=<?php echo $ackey; ?>"><i class="fa fa-circle-o text-yellow"></i> 添加候选人</a></li>
                <li class="disabled"><a href="#"><i class="fa fa-circle-o text-light-blue"></i> 修改候选人</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">候选人信息</h3>
            </div>
            <div class="box-footer" style="display: flex;flex-wrap: wrap;">
            <?php
              foreach ($can_array as $key => $value) {
                if ($value['type'] == 0) {
                  $imgurl_array = json_decode($value['imgurl'], 1);
                  $imgurl = $imgurl_array[0];
                  $html = '<ul class="mailbox-attachments clearfix pull-left">
                            <li>
                              <span class="mailbox-attachment-icon has-img"><img class="can-img" data-original="'.'http://'.Location.'/VoteAdmin/pages/candidate/img/img/'.$imgurl.'" ></span>
                              <div class="mailbox-attachment-info">
                                <a href="../../../VotePages/index-view.php?cankey='.$value['uniquekey'].'&ackey='.$ackey.'" target="_blank" class="mailbox-attachment-name pull-left p-limit-1">'.$value['name'].'</a>
                                <p class="pull-right">'.$value['votes'].'票</p>
                                <br/>
                                <a href="candidate-change.php?cankey='.$value['uniquekey'].'&ackey='.$ackey.'" >修改</a>
                                <br/>
                                <p class="p-limit-2">'.$value['introduction'].'</p>
                              </div>
                            </li>
                          </ul>';
                  echo $html;
                }else if ($value['type'] == 1) {
                  $videoUrl = $value['videourl'];
                  preg_match('/(.*)\/(.*?)\.html/', $videoUrl, $match);
                  $videoCode = $match[2];
                  $html = '<ul class="mailbox-attachments clearfix pull-left">
                              <li style="list-style: none;height: auto;float: none;" id="video-content">
                                <div class="mailbox-attachment-info">
                                <embed width="100%" height="100%" name="plugin" id="plugin" src="http://imgcache.qq.com/tencentvideo_v1/player/TPout.swf?vid='.$videoCode.'&amp;auto=0" type="application/x-shockwave-flash">
                                <a href="../../../VotePages/index-view.php?cankey='.$value['uniquekey'].'&ackey='.$ackey.'" target="_blank" class="mailbox-attachment-name pull-left p-limit-1">'.$value['name'].'</a><br/>
                                <p class="pull-right">'.$value['votes'].'票</p>
                                <br/>
                                <a href="candidate-change.php?cankey='.$value['uniquekey'].'&ackey='.$ackey.'" >修改</a>
                                <br/>
                                <p class="p-limit-2">'.$value['introduction'].'</p>
                                </div>
                              </li>
                          </ul>';
                  echo $html;
                }else if ($value['type'] == 2) {
                  $html = '<ul class="mailbox-attachments clearfix pull-left">
                            <li>
                              <span class="mailbox-attachment-icon has-img"><img class="can-img" data-original="'.'http://'.Location.'/VoteAdmin/pages/candidate/img/cover/'.$value['linkcover'].'"></span>
                              <div class="mailbox-attachment-info">
                                <a href="../../../VotePages/index-view.php?cankey='.$value['uniquekey'].'&ackey='.$ackey.'" target="_blank" class="mailbox-attachment-name pull-left p-limit-1">'.$value['name'].'</a>
                                <p class="pull-right">'.$value['votes'].'票</p>
                                <br/>
                                <a href="candidate-change.php?cankey='.$value['uniquekey'].'&ackey='.$ackey.'" >修改</a>&nbsp;&nbsp;&nbsp;<a href="'.$value['linkurl'].'" target="_blank">外链</a>
                                <br/>
                                <p class="p-limit-2">'.$value['introduction'].'</p>
                              </div>
                            </li>
                          </ul>';
                  echo $html;
                }else if ($value['type'] == 3) {
                  $audioUrl = $value['audiourl'];
                  $html = '<ul class="mailbox-attachments clearfix pull-left">
                            <li  style="width: auto;">
                              <div class="mailbox-attachment-info" style="width:320px;">
                                <div id="candidate-audio"><audio controls="controls"><source src="'.'http://'.Location.'/VoteAdmin/pages/candidate/audio/'.$value['audiourl'].'" type="audio/mp3" ></audio></div>
                                <a href="../../../VotePages/index-view.php?cankey='.$value['uniquekey'].'&ackey='.$ackey.'" target="_blank" class="mailbox-attachment-name pull-left p-limit-1">'.$value['name'].'</a>
                                <p class="pull-right">'.$value['votes'].'票</p>
                                <br/>
                                <a href="candidate-change.php?cankey='.$value['uniquekey'].'&ackey='.$ackey.'" >修改</a>
                                <br/>
                                <p class="p-limit-2">'.$value['introduction'].'</p>
                              </div>
                            </li>
                          </ul>';
                  echo $html;
                }
              }
            ?>
            
            </div>

            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
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
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
</body>
</html>
