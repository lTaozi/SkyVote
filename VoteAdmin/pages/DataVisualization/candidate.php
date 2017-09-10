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

$towho = isset($_GET['id']) ? $_GET['id'] : "";
$min = isset($_GET['min']) ? $_GET['min'] : "";
$sql = "select * from candidate where uniquekey = '$towho'";
$res = $dbClass->query($sql);
if (mysqli_num_rows($res) != 1) {
    exit('illegal parameter');
}else{
    $rs = $dbClass->getone($res);
}
$sql = "select * from record where towho = '$towho'";
$res = $dbClass->query($sql);
if (mysqli_num_rows($res) <= 0) {

} else {

}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SkyVote | 数据分析</title>
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

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
                    <a href="activity-view.html">
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
                        <i class="fa fa-pie-chart"></i>
                        <span>可视数据</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active"><a href="../DataVisualization"><i class="fa fa-circle-o"></i> 查看</a></li>

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
                候选人详情
                <small>在此分析票的有效性</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">投票可视化系统</a></li>
                <li class="active">候选人详情</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">候选人</h3>


                        </div>
                        <!-- /.box-header -->
                        <!--    PHP  执行         -->
                        <?php
                            if ($rs['type'] == 0) {
                                    $rs['type'] = "图片";
                                    $imgArray   = json_decode($rs['imgurl'], 1);
                                    $img        = $imgArray[0];
                            }
                            if ($rs['type'] == 1) {
                                $rs['type'] = "视频";
                                $videoUrl = $rs['videourl'];
                                preg_match('/(.*)\/(.*?)\.html/', $videoUrl, $match);
                                $videoCode = $match[2];
                            }
                            if ($rs['type'] == 2) {
                                $rs['type'] = "外链";
                                $linkUrl    = $rs['linkurl'];
                            }
                            if ($rs['type'] == 3) {
                                $rs['type'] = "音频";
                                
                            }
                        ?>

                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>姓名</th>
                                    <th><?php echo $rs['name'] ?></th>
                                </tr>
                                <tr>
                                    <th>得票数</th>
                                    <th><?php echo $rs['votes'] ?></th>
                                </tr>
                                <tr>
                                    <th>联系方式</th>
                                    <th><?php echo $rs['contact'] ?></th>
                                </tr>
                                <tr>
                                    <th>简介</th>
                                    <th><?php echo $rs['introduction'] ?></th>
                                </tr>
                                <tr>
                                    <th>资源类型</th>
                                    <th><?php echo $rs['type'] ?></th>
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
                    <!-- interactive chart -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart-o"></i>

                            <h3 class="box-title">实时情况</h3>

                            <div class="box-tools pull-right">
                                Real time
                                <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                                    <button type="button" class="btn btn-default btn-xs active" data-toggle="on">On
                                    </button>
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="interactive" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body-->
                    </div>
                    <!-- /.box -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->


            <div class="row">
                <div class="col-md-12">
                    <!-- AREA CHART -->
                    <div class="box box-primary" style="display: none">
                        <div class="box-header with-border">
                            <h3 class="box-title">Area Chart</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="areaChart" style="height:250px"></canvas>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                    <!-- DONUT CHART -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">地域分布</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <canvas id="pieChart" style="height:250px"></canvas>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>
                <!-- /.col (LEFT) -->
                <div class="col-md-12">
                    <!-- LINE CHART -->
                    <div class="box box-info">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control go">
                            <span class="input-group-btn">
                              <button type="button" id="go" class="btn btn-info btn-flat">Go!</button>
                            </span>
                        </div>
                        <div class="box-header with-border">
                            <h3 class="box-title">增长率</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="lineChart" style="height:250px"></canvas>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                    <!-- BAR CHART -->
                    <div class="box box-success" style="display: none">
                        <div class="box-header with-border">
                            <h3 class="box-title">Bar Chart</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="barChart" style="height:230px"></canvas>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>

                <!-- /.col (RIGHT) -->
            </div>

            <div class="row">
                <div class="col-xs-12">

                    <!-- /.box-header -->
                    <!--    PHP  执行         -->
                    <?php
                        $str = "";
                        $sql = "SELECT * FROM `record` WHERE towho = '$towho'";
                        $res = $dbClass->query($sql);
                        if (mysqli_num_rows($res) <= 0) {
                            $str .= "<tr><td colspan='7'>票数详情</td></tr>";
                        } else {
                            $i = 0;
                            while ($rs = $dbClass->getone($res)){
                                if ($rs['vote_plat'] == 1) {
                                    $rs['vote_plat'] = "微信";
                                }
                                if ($rs['vote_plat'] == 2) {
                                    $rs['vote_plat'] = "微博";
                                }
                                if ($rs['vote_plat'] == 3) {
                                    $rs['vote_plat'] = "QQ";
                                }
                                if ($rs['vote_plat'] == 4) {
                                    $rs['vote_plat'] = "管理员导入";
                                }
                                $i ++;
                                $str .= "<tr>
                                        <th>{$i}</th>
                                        <th>{$rs['vote_username']}</th>
                                        <th>{$rs['vote_plat']}</th>
                                        <th>{$rs['vote_area']}</th>
                                        <th>{$rs['vote_ip']}</th>
                                        <th>{$rs['votetime']}</th>
                                        </tr>";
                            }
                        }
                    ?>

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">票数详情</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>姓名</th>
                                    <th>来源平台</th>
                                    <th>地区</th>
                                    <th>IP</th>
                                    <th>时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php echo $str?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>姓名</th>
                                    <th>来源平台</th>
                                    <th>地区</th>
                                    <th>IP</th>
                                    <th>时间</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box-body -->
                </div>
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

<!-- jQuery 2.2.0 -->
<script src="../../plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>

<!-- FLOT CHARTS -->
<script src="../../plugins/flot/jquery.flot.min.js"></script>

<!-- ChartJS 1.0.1 -->
<script src="../../plugins/chartjs/Chart.min.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="../../plugins/flot/jquery.flot.resize.min.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<!--<script src="../../plugins/flot/jquery.flot.pie.min.js"></script>-->
FLOT CATEGORIES PLUGIN - Used to draw bar charts
<script src="../../plugins/flot/jquery.flot.categories.min.js"></script>

<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- Page script -->
<script>


    function initChart(pieData,areaData) {
        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        //--------------
        //- AREA CHART -
        //--------------

        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = areaData;

        var areaChartOptions = {
            //Boolean - If we should show the scale at all
            showScale: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: false,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - Whether the line is curved between points
            bezierCurve: true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension: 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot: false,
            //Number - Radius of each point dot in pixels
            pointDotRadius: 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth: 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius: 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke: true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth: 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill: true,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true
        };

        //Create the line chart
        areaChart.Line(areaChartData, areaChartOptions);

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas);
        var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        lineChart.Line(areaChartData, lineChartOptions);

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = pieData;
        var pieOptions = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 2,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        barChartData.datasets[1].fillColor = "#00a65a";
        barChartData.datasets[1].strokeColor = "#00a65a";
        barChartData.datasets[1].pointColor = "#00a65a";
        var barChartOptions = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - If there is a stroke on each bar
            barShowStroke: true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth: 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing: 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing: 1,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to make the chart responsive
            responsive: true,
            maintainAspectRatio: true
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);


    }
    function initInteractive() {
        /*
         * Flot Interactive Chart
         * -----------------------
         */
        // We use an inline data source in the example, usually data would
        // be fetched from a server
        var data = [], totalPoints = 100;

        function getRandomData() {

            if (data.length > 0)
                data = data.slice(1);

            // Do a random walk
            while (data.length < totalPoints) {

                var prev = data.length > 0 ? data[data.length - 1] : 50,
                    y = prev + Math.random() * 10 - 5;

                if (y < 0) {
                    y = 0;
                } else if (y > 100) {
                    y = 100;
                }

                data.push(y);
            }

            // Zip the generated y values with the x values
            var res = [];
            for (var i = 0; i < data.length; ++i) {
                res.push([i, data[i]]);
            }

            return res;
        }

        var interactive_plot = $.plot("#interactive", [getRandomData()], {
            grid: {
                borderColor: "#f3f3f3",
                borderWidth: 1,
                tickColor: "#f3f3f3"
            },
            series: {
                shadowSize: 0, // Drawing is faster without shadows
                color: "#3c8dbc"
            },
            lines: {
                fill: true, //Converts the line chart to area chart
                color: "#3c8dbc"
            },
            yaxis: {
                min: 0,
                max: 100,
                show: true
            },
            xaxis: {
                show: true
            }
        });

        var updateInterval = 500; //Fetch data ever x milliseconds
        var realtime = "off"; //If == to on then fetch data every x seconds. else stop fetching
        function update() {

            interactive_plot.setData([getRandomData()]);

            // Since the axes don't change, we don't need to call plot.setupGrid()
            interactive_plot.draw();
            if (realtime === "on")
                setTimeout(update, updateInterval);
        }

        //INITIALIZE REALTIME DATA FETCHING
        if (realtime === "on") {
            update();
        }
        //REALTIME TOGGLE
        $("#realtime .btn").click(function () {
            if ($(this).data("toggle") === "on") {
                realtime = "on";
            }
            else {
                realtime = "off";
            }
            update();
        });
        /*
         * END INTERACTIVE CHART
         */


        /*
         * LINE CHART
         * ----------
         */
        //LINE randomly generated data

        var sin = [], cos = [];
        for (var i = 0; i < 14; i += 0.5) {
            sin.push([i, Math.sin(i)]);
            cos.push([i, Math.cos(i)]);
        }
        var line_data1 = {
            data: sin,
            color: "#3c8dbc"
        };
        var line_data2 = {
            data: cos,
            color: "#00c0ef"
        };
        $.plot("#line-chart", [line_data1, line_data2], {
            grid: {
                hoverable: true,
                borderColor: "#f3f3f3",
                borderWidth: 1,
                tickColor: "#f3f3f3"
            },
            series: {
                shadowSize: 0,
                lines: {
                    show: true
                },
                points: {
                    show: true
                }
            },
            lines: {
                fill: false,
                color: ["#3c8dbc", "#f56954"]
            },
            yaxis: {
                show: true,
            },
            xaxis: {
                show: true
            }
        });
        //Initialize tooltip on hover
        $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
            position: "absolute",
            display: "none",
            opacity: 0.8
        }).appendTo("body");
        $("#line-chart").bind("plothover", function (event, pos, item) {

            if (item) {
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);

                $("#line-chart-tooltip").html(item.series.label + " of " + x + " = " + y)
                    .css({top: item.pageY + 5, left: item.pageX + 5})
                    .fadeIn(200);
            } else {
                $("#line-chart-tooltip").hide();
            }

        });
        /* END LINE CHART */

        /*
         * FULL WIDTH STATIC AREA CHART
         * -----------------
         */
        var areaData = [[2, 88.0], [3, 93.3], [4, 102.0], [5, 108.5], [6, 115.7], [7, 115.6],
            [8, 124.6], [9, 130.3], [10, 134.3], [11, 141.4], [12, 146.5], [13, 151.7], [14, 159.9],
            [15, 165.4], [16, 167.8], [17, 168.7], [18, 169.5], [19, 168.0]];
        $.plot("#area-chart", [areaData], {
            grid: {
                borderWidth: 0
            },
            series: {
                shadowSize: 0, // Drawing is faster without shadows
                color: "#00c0ef"
            },
            lines: {
                fill: true //Converts the line chart to area chart
            },
            yaxis: {
                show: false
            },
            xaxis: {
                show: false
            }
        });

        /* END AREA CHART */

        /*
         * BAR CHART
         * ---------
         */

        var bar_data = {
            data: [["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9]],
            color: "#3c8dbc"
        };
        $.plot("#bar-chart", [bar_data], {
            grid: {
                borderWidth: 1,
                borderColor: "#f3f3f3",
                tickColor: "#f3f3f3"
            },
            series: {
                bars: {
                    show: true,
                    barWidth: 0.5,
                    align: "center"
                }
            },
            xaxis: {
                mode: "categories",
                tickLength: 0
            }
        });
        /* END BAR CHART */

        /*
         * DONUT CHART
         * -----------
         */

        var donutData = [
            {label: "Series2", data: 30, color: "#3c8dbc"},
            {label: "Series3", data: 20, color: "#0073b7"},
            {label: "Series4", data: 50, color: "#00c0ef"}
        ];
        $.plot("#donut-chart", donutData, {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    innerRadius: 0.5,
                    label: {
                        show: true,
                        radius: 2 / 3,
                        formatter: labelFormatter,
                        threshold: 0.1
                    }

                }
            },
            legend: {
                show: false
            }
        });
        /*
         * END DONUT CHART
         */
    }
    function initTables() {
        $("#example1").DataTable();
    }
    var areaData;
    var pieData;
    var min = "";
    $(function () {
        initTables();

        $.get("VotingMap.php?id=<?php echo $towho ?>",
            function (data, status) {
                data = eval('(' + data + ')');
                pieData = data;
                $.get("VotingFrequency.php?id=<?php echo $towho ?>&min=<?php echo $min ?>",
                    function (data, status) {
                        console.log(data);
                        data = eval('(' + data + ')');
                        areaData = data;
                        initChart(pieData,areaData);
                    });
            });


        initInteractive();

    });

    $("#go").click(function () {
        min = $(".go").val();
        location.href = "candidate.php?id=<?php echo $towho ?>&min="+min;
    });

    /*
     * Custom Label formatter
     * ----------------------
     */
    function labelFormatter(label, series) {
        return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
            + label
            + "<br>"
            + Math.round(series.percent) + "%</div>";
    }
</script>
</body>
</html>
