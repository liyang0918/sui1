<?php

require_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
require_once("func.php");
if(!empty($_POST["login"])||$_POST["logintype"]=="reg") {
    if (!empty($_POST["id"]))
        $id = $_POST["id"];
    if (!empty($_POST["passwd"]))
        $passwd = $_POST["passwd"];
    $kick_multi = 1;
    if ($is_china_flag == 1) {
        if (!in_array($id, $super_users)) {
            wap_error_quit("抱歉，中国站暂时不支持登录!");
        }
    }
    if (strcasecmp($id, "guest") == 0)
        wap_error_quit("用户名不可以为guest");
    if (!empty($id)) {
        if (preg_match("/^[0-9]$/", $id{0})) {
            $con = db_connect_web_wap();
            $id = get_real_user_id($id);
            if (empty($id) == true) {
                $loginok = 666;
            } else {
                $loginok = users_login_web($id, $passwd, $kick_multi);
            }
        } else {
            $loginok = users_login_web($id, $passwd, $kick_multi);
        }
    } else {
        wap_error_quit("用户名不能为空!");
    }
    if($loginok==0){
    ?>
    <SCRIPT language="javascript">
        <?php
        if(!empty($_COOKIE["before_login"]))
            echo 'window.location = "'.$_COOKIE["before_login"].'";';
        else
            echo 'window.location = "jiaye.html";';
    }?>
    </SCRIPT>
<?php
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">    <!--手机屏幕自适应设置-->
    <meta name="format-detection" content="telephone=no">    <!--手机屏幕自适应设置-->
    <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">    <!--手机屏幕自适应设置-->
    <title>未名空间</title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
<div class="conter">
    <div class="forget">
        <nav class="logoIn_nav bg">
            <h3 class="font_white" >登录未名空间</h3>
        </nav>
        <div class="forget_box bg">
            <form action="login.php" method="post">
            <p class="forget_p">
                <span>账号：</span><input id="id" name="id" type="text" placeholder="用户名/手机号码"/>
            </p>
            <p class="forget_p">
                <span>密码：</span><input id="passwd" name="passwd" type="password" placeholder="密码"/>
            </p>
            <p class="forget_code"><a href="logIn_forget.html">忘记密码</a></p>
            <input class="forget_inp forget_lg"  name="login" id="login" type="submit" value="登录"/>
	    <p class="nousername"><a href="wap_register.php">还没有账号？点此注册</a></p>
            </form>
        </div>
    </div><!--End forget-->
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
</body>
</html>
