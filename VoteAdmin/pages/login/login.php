<?php
include_once 'rootConfig.php';

// 接受数据
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'] ;
  $password = $_POST['password'] ;

  if ($username === Username && $password === Password) {
    // 设置session
    session_start();
    $_SESSION['rootId']   = RootId;
    $_SESSION['username'] = Username;
    $_SESSION['identity'] = Identity;
    // 设置cookies
    if (isset($_POST['remember']) && $_POST['remember'] == true) {
      setcookie("username", $username, time()+3600*24*7);
      setcookie("password", $password, time()+3600*24*7);
      setcookie("remember", true, time()+3600*24*7);
    }else{
      setcookie("username", "", time()-3600);
      setcookie("password", "", time()-3600);
      setcookie("remember", "", time()-3600);
    }
    // 跳转
    header("Location: ../activity/activity-view.php");
  }else{
    header("Location: login.php");
  }
}


?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SkyVote | 登录</title>
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
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page" style="background-image: url(img/bg.jpg);background-size: cover;background-repeat: no-repeat;">
<div ></div>
<div class="login-box">
  <div class="login-logo">
    <a href="./login.php" style="color: white;">Sky<b>Vote</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">登录开始投票管理</p>

    <form action="login.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="用户名" name="username" value="<?php if(isset($_COOKIE['username'])) echo $_COOKIE['username']; ?>">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="密码" name="password" value="<?php if(isset($_COOKIE['password'])) echo $_COOKIE['password']; ?>">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <span class="hint" style="color: red;position: relative;top: 10px;"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember" <?php if(isset($_COOKIE['remember'])){if($_COOKIE['remember']) echo "checked='checked'";}?> > 记住密码
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4" id="button">
          <div id="submit" type="submit" class="btn btn-primary btn-block btn-flat" style="border-radius: 3px;">登录</div>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <div class="social-auth-links text-center">
      <p>- OR -</p>
      <div style="overflow: hidden;padding: 0 30%">
        <a class="qq" href="#" style="background-color: #498ad5;width: 50px;height: 50px;border-radius: 50%;line-height: 57px;display: block;float: left;"><i class="fa fa-qq" style="color: white;font-size: 24px;"></i></a>
        <a class="weibo" href="#" style="background-color: #e05244;width: 50px;height: 50px;border-radius: 50%;line-height: 57px;display: block;float: right;"><i class="fa fa-weibo" style="color: white;font-size: 24px;"></i></a>        
      </div>

    </div>
    <!-- /.social-auth-links -->

    <a href="register.html" class="text-center">合作申请</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="../../plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<!-- login -->
<script src="js/login.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
