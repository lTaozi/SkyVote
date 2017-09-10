<?php
include_once '../login/rootConfig.php';
include_once '../../../lib/Activity.class.php';
include_once '../config.php';

session_start();
if (!isset($_SESSION['rootId']) || !isset($_SESSION['username']) || $_SESSION['rootId'] != RootId || $_SESSION['username'] != Username) {
  header("Location: ../login/login.php");
  exit;
}

// 获取数据
$ackey  = $_GET['ackey'];
$activity = new Activity();
$acInfo_json  = $activity->getActivityInfo("$ackey");
$acInfo_array = json_decode($acInfo_json, 1)[0];
// 解析数据
$ac_name   = $acInfo_array['activity_name'];
$ac_host   = $acInfo_array['host'];
$ac_intro  = $acInfo_array['intro'];
$startTime = date("Y-m-d", strtotime($acInfo_array['starttime']))."T".date("H:i:s", strtotime($acInfo_array['starttime']));
$endTime   = date("Y-m-d", strtotime($acInfo_array['endtime']))."T".date("H:i:s", strtotime($acInfo_array['endtime']));
$ac_plat   = $acInfo_array['platformlimit'];
$ac_vote   = $acInfo_array['refreshballot'];
$ac_cycle  = $acInfo_array['refreshcycle'];
$ac_img    = $acInfo_array['ac_img'];
$ac_logo   = $acInfo_array['ac_logo'];
$ac_anonymous  = $acInfo_array['anonymous'];

// rule解析
$ac_rules_json  = $acInfo_array['rules'];
if ($ac_rules_json) {
  $ac_rules_array = json_decode($ac_rules_json, 1);
  $rules = array();
  foreach ($ac_rules_array as $key => $value) {
    $rules[] = $value;
  }
}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SkyVote | 修改活动</title>
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
              <a href="activity-view.html">
                  <i class="fa fa-tasks"></i> <span>活动</span>
                  <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li><a href="activity-view.php">查看</a></li>
                  <li><a href="activity-add.php">新建</a></li>
                  <li class="active"><a href="#">修改</a></li>
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
        <?php echo $ac_name?>
        <small>在此修改活动信息</small>
        <input type="hidden" id="activity-key" value="<?php echo $ackey; ?>"></input>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Activity</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="#" class="btn btn-primary btn-block margin-bottom" id="change-ac">确认修改</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">导入信息表</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li>
                  <a href="#">
                    <label class="btn btn-default" for="leadVoter">选择文件</label>
                    <button type="button" class="btn btn-info pull-right" id="leadVoter-up">确认导入</button>
                    <br/>
                    &nbsp;&nbsp;<b style="color:#666" id="leadVoter-txt">导入投票人</b><input type="file" id="leadVoter" style="position:absolute;clip:rect(0 0 0 0);" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                  </a>
                </li>
                <li>
                  <a href="#">
                    <label class="btn btn-default" for="leadCandidate">选择文件</label>
                    <button type="button" class="btn btn-info pull-right" id="leadCandidate-up">确认导入</button>
                    <br/>
                    &nbsp;&nbsp;<b style="color:#666" id="leadCandidate-txt">导入候选人</b><input type="file" id="leadCandidate" style="position:absolute;clip:rect(0 0 0 0);" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                  </a>
                </li>
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
                <li><a href="../candidate/candidate-view.php?ackey=<?php echo $ackey; ?>"><i class="fa fa-circle-o text-red"></i> 查看候选人</a></li>
                <li><a href="../candidate/candidate-add.php?ackey=<?php echo $ackey; ?>"><i class="fa fa-circle-o text-yellow"></i>添加候选人</a></li>
                <li class="disabled"><a href="#"><i class="fa fa-circle-o text-light-blue"></i>修改候选人</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- alert -->
        <div class="col-md-9 alert-block" id="alert-warning" style="display: none;">
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> 警告!</h4>
            <p id="warning-value"></p>
          </div>  
        </div>
        <div class="col-md-9 alert-block" id="alert-success" style="display: none;">
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> 成功!</h4>
            <p id="success-value"></p>
          </div>  
        </div>
        <div class="col-md-9 alert-block" id="alert-danger" style="display: none;">
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-ban"></i> 错误!</h4>
            <p id="danger-value"></p>
          </div>  
        </div>
        <!-- /alert -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">活动信息修改</h3>
            </div>
            <div class="box-body">
              <form role="form">
                <!-- text input -->
                <div class="form-group" id="ac-name">
                  <label>活动名</label>
                  <input type="text" class="form-control" id="activity-name" placeholder="请输入活动名(30字以内)" max="30" maxlength="30" value="<?php echo $ac_name?>">
                  <span class="help-block" id="activity-name-error"></span>
                </div>
                <div class="form-group" id="ac-host">
                  <label>主办方</label>
                  <input type="text" class="form-control" id="activity-host" placeholder="请输入主办方(30字以内)" max="30" maxlength="30" value="<?php echo $ac_host?>">
                  <span class="help-block" id="activity-host-error"></span>
                </div>
                
                <!-- textarea -->
                <div class="form-group" id="ac-intro">
                  <label>活动简介</label>
                  <textarea class="form-control" rows="3" id="activity-intro" placeholder="请在此输入活动简介（500字以内）"><?php echo $ac_intro?></textarea>
                  <span class="help-block" id="activity-intro-error"></span>
                </div>
                <div class="input-group col-lg-5 pull-left ac-time">
                  <label>活动开始时间</label>
                  <input type="datetime-local" class="form-control" id="activity-start-time" value="<?php echo $startTime?>" disabled>
                  <span class="help-block activity-time-error"></span>
                </div>
                <div class="input-group col-lg-5 pull-right ac-time">
                  <label>活动结束时间</label>
                  <input type="datetime-local" class="form-control" id="activity-end-time" value="<?php echo $endTime?>">
                  <span class="help-block activity-time-error"></span>
                </div>
                <div class="input-group col-lg-5 pull-left">
                  <span class="input-group-addon">周期票数</span>
                  <input type="number" min="1" max="20" class="form-control" id="activity-vote" placeholder="请设置每周期可投票数" value="<?php echo $ac_vote?>">
                </div>
                <div class="col-lg-2"></div>
                <div class="input-group col-lg-5">
                  <span class="input-group-addon">更新周期</span>
                  <input type="number" min="1" max="30" class="form-control" id="activity-cycle" placeholder="请设置票数更新周期" value="<?php echo $ac_cycle?>">
                </div>
                <br/>
                <!-- checkbox -->
                <div class="form-group">
                  <label>是否匿名</label>
                  <input id="ac_anonymous" type="hidden" value="<?php echo $ac_anonymous ?>">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="anonymous">
                      匿名
                    </label>
                  </div>
                  <label>用户来源</label>
                  <input id="ac_plat" type="hidden" value="<?php echo $ac_plat ?>">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="plat-wx">
                      微信
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="plat-wb">
                      微博
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="plat-qq">
                      QQ
                    </label>
                  </div>

                  <div class="checkbox">
                    <label>
                      <input type="checkbox" id="plat-self">
                      自导入
                    </label>
                  </div>
                </div>
                
                
                <!-- 规则 -->
                <div class="form-group">
                  <label>规则和活动说明</label>
                  <div class="input-group" id="ac-rule1">
                    <span class="input-group-addon">1</span>
                    <input type="text" class="form-control" id="rule1" placeholder="请输入说明1" value="<?php echo $rules[0] = (isset($rules[0])) ? $rules[0] : null ; ?>" max="60" maxlength="60">
                    <span class="help-block" id="rule1-error"></span>
                  </div><br/>
                  <div class="input-group" id="ac-rule2">
                    <span class="input-group-addon">2</span>
                    <input type="text" class="form-control" id="rule2" placeholder="请输入说明2" value="<?php echo $rules[1] = (isset($rules[1])) ? $rules[1] : null ; ?>" max="60" maxlength="60">
                    <span class="help-block" id="rule2-error"></span>
                  </div><br/>
                  <div class="input-group" id="ac-rule3">
                    <span class="input-group-addon">3</span>
                    <input type="text" class="form-control" id="rule3" placeholder="请输入说明3" value="<?php echo $rules[2] = (isset($rules[2])) ? $rules[2] : null ; ?>" max="60" maxlength="60">
                    <span class="help-block" id="rule3-error"></span>
                  </div><br/>
                  <div class="input-group" id="ac-rule4">
                    <span class="input-group-addon">4</span>
                    <input type="text" class="form-control" id="rule4" placeholder="请输入说明4" value="<?php echo $rules[3] = (isset($rules[3])) ? $rules[3] : null ; ?>" max="60" maxlength="60">
                    <span class="help-block" id="rule4-error"></span>
                  </div><br/>
                  <div class="input-group" id="ac-rule5">
                    <span class="input-group-addon">5</span>
                    <input type="text" class="form-control" id="rule5" placeholder="请输入说明5" value="<?php echo $rules[4] = (isset($rules[4])) ? $rules[4] : null ; ?>" max="60" maxlength="60">
                    <span class="help-block" id="rule5-error"></span>
                  </div><br/>
                </div>

                <!-- 上传封面和logo -->
                <div class="box-footer">
                  <ul class="mailbox-attachments clearfix pull-left">
                    <div class="form-group">
                      <label>活动页面背景图<small>（1920*1080）</small></label>
                    </div>                  
                    <li style="overflow:hidden;">
                      <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="display: none;" id="rate-img">10%</div>
                      <div id="ac-img-img"><span class="mailbox-attachment-icon has-img"><img src="http://<?php echo Location; ?>/VoteAdmin/pages/activity/img/bg/<?php echo $ac_img; ?>" alt="Attachment"></span></div>
                      <div class="mailbox-attachment-info">
                        <b style="color:#666" id="ac-img-txt">活动背景</b><br/><br/>
                        <label class="btn btn-default" for="ac-img-input">选择图片</label>
                        <input type="file" id="ac-img-input" name="fileselect[]" style="position:absolute;clip:rect(0 0 0 0);" accept="image/*">
                        <button type="button" class="btn btn-info pull-right" id="ac-img-up">确认上传</button>
                      </div>
                    </li>
                  </ul>
                  <ul class="mailbox-attachments clearfix pull-left">
                    <div class="form-group">
                      <label>主办方logo<small>（160*160）</small></label>
                    </div>                  
                    <li style="overflow:hidden;">
                      <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="display: none;" id="rate-logo">10%</div>
                      <div id="ac-logo-img"><span class="mailbox-attachment-icon has-img" id="ac-logo-img"><img src="http://<?php echo Location; ?>/VoteAdmin/pages/activity/img/logo/<?php echo $ac_logo; ?>" alt="Attachment"></span></div>
                      <div class="mailbox-attachment-info">
                        <b style="color:#666" id="ac-logo-txt">logo</b><br/><br/>
                        <label class="btn btn-default" for="ac-logo-input">选择图片</label>
                        <input type="file" id="ac-logo-input" style="position:absolute;clip:rect(0 0 0 0);" accept="image/*">
                        <button type="button" class="btn btn-info pull-right" id="ac-logo-up">确认上传</button>
                      </div>
                    </li>
                  </ul>
                </div>
                <!-- alert-img -->
                <div class="col-md-9 alert-block" id="alert-warning-img" style="display: none;">
                  <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> 警告!</h4>
                    <p id="warning-value-img"></p>
                  </div>  
                </div>
                <div class="col-md-9 alert-block" id="alert-success-img" style="display: none;">
                  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> 成功!</h4>
                    <p id="success-value-img"></p>
                  </div>  
                </div>
                <div class="col-md-9 alert-block" id="alert-danger-img" style="display: none;">
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> 错误!</h4>
                    <p id="danger-value-img"></p>
                  </div>  
                </div>
                <!-- /alert -->

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

<!-- jQuery 2.2.0 -->
<script src="../../plugins/jQuery/jQuery-2.2.0.min.js"></script>
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
<!-- MyJs -->
<script src="../../bootstrap/js/myjs.js"></script>
<script src="../../bootstrap/js/zxxFile.js"></script>
<script src="../../bootstrap/js/uploadFile.js"></script>
</body>
</html>
