<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");


















?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="gb2312">
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
<div class="conter forget">
    <div id="title">
        <nav class="logoIn_nav bg">
            <a href="" onclick="return layer_goback();"><img src="img/bg01.png" alt="bg01.png"/></a>
            <h3 id="title_text" class="font_white" >忘记密码</h3>
            <span id="page_layer" style="display: none">0</span>
        </nav>
    </div>
    <div id="reset_way">
        <div class="forget_box">
            <p class="forget_method">请选择找回密码的方式</p>
            <input class="methods bg" type="button" value="通过手机号找回密码" onclick="reset_way('phone');"/>
            <input class="methods bg" type="button" value="通过邮箱找回密码" onclick="reset_way('mail');"/>
            <p class="other_method">
                以上方式都无法找回密码？</br>
                请发邮件至contactus@mitbbs.com，通过人工申诉找回密码。
            </p>
        </div>
    </div><!--End reset_way-->

    <div id="by_phone">
        <form class="forget_box">
            <p class="forget_p">
                <input type="text" placeholder="请输入账号"/>
                <input class="get_test_l" type="text" placeholder="请输入您的手机号"/>
                <input class="get_test_r" type="button" value="获取验证码"/>
            </p>
            <p class="forget_p">
                <input type="text" maxlength="4" placeholder="请输入4位短信验证码"/>
            </p>
            <input class="forget_submit font_white bg" type="submit" value="下一步" onclick="window.location.href='logIn_forget_back.html'"/>
        </form>
    </div><!-- End by_phone-->

    <div id="by_mail">
        <form class="forget_box">
            <p class="forget_p">
                <input type="text" placeholder="请输入账号"/>
            </p>
            <p class="forget_p">
                <input type="text" placeholder="请输入注册邮箱"/>
            </p>
            <p class="forget_info"> 系统将会向您的注册邮箱中发送一封带有10为数字验证码的邮件，请注意查收 </p>
            <input class="forget_submit font_white bg" type="submit" value="提交"/>
        </form>
    </div><!--End by_mail-->

</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript">
    function reset_way(action) {
        switch (action) {
            case "phone":
                $('#page_layer').html("1");

                $('#reset_way').hide();
                $('#by_phone').show();
                $('#by_mail').hide();

                $('#title_text').html("手机号找回密码");
                break;
            case "mail":
                $('#page_layer').html("1");

                $('#reset_way').hide();
                $('#by_phone').hide();
                $('#by_mail').show();

                $('#title_text').html("邮箱找回密码");
                break;
        }

        return false;
    }

    function layer_goback() {
        var layer = parseInt($('#page_layer').html());

        switch (layer) {
            case 0:
                go_last_page();
                break;
            case 1:
                $('#reset_way').show();
                $('#by_phone').hide();
                $('#by_mail').hide();

                $('#title_text').html("忘记密码");
                break;
            case 2:
                $('#reset_way').hide();
                $('#by_phone').show();
                $('#by_mail').hide();

                $('#title_text').html("手机号找回密码");
                break;
        }

        return false;
    }


    $(document).ready(function () {
        $('#reset_way').show();
        $('#by_phone').hide();
        $('#by_mail').hide();

        $('#title_text').html("忘记密码");
    });
</script>
</body>
</html>
