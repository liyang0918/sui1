<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");


















?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="gb2312">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">    <!--�ֻ���Ļ����Ӧ����-->
    <meta name="format-detection" content="telephone=no">    <!--�ֻ���Ļ����Ӧ����-->
    <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">    <!--�ֻ���Ļ����Ӧ����-->
    <title>δ���ռ�</title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
<div class="conter forget">
    <div id="title">
        <nav class="logoIn_nav bg">
            <a href="" onclick="return layer_goback();"><img src="img/bg01.png" alt="bg01.png"/></a>
            <h3 id="title_text" class="font_white" >��������</h3>
            <span id="page_layer" style="display: none">0</span>
        </nav>
    </div>
    <div id="by_phone">
        <form class="forget_box">
            <p class="forget_p">
                <input id="user_name1" type="text" placeholder="�������˺�"/>
            </p>
            <p class="forget_p">
                <input id="user_phone1" class="get_test_l" type="text" placeholder="�����������ֻ���"/>
                <input class="get_test_r" type="button" value="��ȡ��֤��" onclick="passwd_reset_send_sms(this);" />
            </p>
            <p class="forget_p">
                <input id="confirm_code" type="text" maxlength="4" placeholder="������4λ������֤��"/>
            </p>
            <input class="forget_submit font_white bg" type="button" value="��һ��" onclick="return goto_set_newpasswd();"/>
            <p class="other_method">
                �������������ע����ʺ�,���¼���ǵ�<a href="http://www.mitbbs.com">��վ</a>���������һ�!���������Ĳ��㾴���½⣡
            </p>
        </form>
    </div><!-- End by_phone-->

    <div id="set_newpasswd">
        <form class="forget_box">
            <p class="forget_p">
                <input id="newpasswd" type="password" placeholder="����������"/>
            </p>
            <p class="forget_p">
                <input id="newpasswd_confirm" type="password" placeholder="ȷ��������" onchange="passwd_compare();return true;"/>
            </p>
            <p id="newpasswd_msg" style="display: none">
                ������������ȽϽ������ʾ
            </p>
            <input class="forget_submit font_white bg" type="button" value="�ύ" onclick="passwd_submit();"/>
        </form>
    </div><!--End forget-->

</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/funs.js"></script>
<script type="text/javascript" src="js/send_sms.js"></script>
<script type="text/javascript">
    function passwd_compare() {
        if ($('#newpasswd').val() != $('#newpasswd_confirm').val()) {
            $('#newpasswd_msg').show();
            $('#newpasswd_msg').html("�����������벻һ��");
            return false;
        } else {
            $('#newpasswd_msg').hide();
            return true;
        }
    }

    function goto_set_newpasswd() {
        if (passwd_reset_check_confirm($('#confirm_code').val())) {
            $('#page_layer').html("1");
            $('#title_text').html("����������");
            $('#by_phone').hide();
            $('#by_mail').hide();
            $('#set_newpasswd').show();

            $('#newpasswd').val("");
            $('#newpasswd_confirm').val("");
        } else {
            Alert("��֤�벻��ȷ", 1);
        }
    }

    function passwd_submit() {
        if (!passwd_compare()) {
            return false;
        }

        var newpasswd = $('#newpasswd').val();
        if (newpasswd.length < 6) {
            Alert("���볤��̫��", 1);
            return false;
        }

        user_passwd_reset($('#newpasswd').val());
        return false;
    }

    function layer_goback() {
        var layer = parseInt($('#page_layer').html());

        switch (layer) {
            case 0:
                go_last_page();
                break;
            case 1:
                $('#by_phone').show();
                $('#by_mail').hide();
                $('#set_newpasswd').hide();

                $('#page_layer').html("0");
                $('#title_text').html("�ֻ����һ�����");
                break;
        }

        return false;
    }


    $(document).ready(function () {
        $('#by_phone').show();
        $('#set_newpasswd').hide();

        $('#title_text').html("�һ�����");
    });
</script>
</body>
</html>
