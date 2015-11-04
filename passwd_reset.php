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
    <div id="reset_way">
        <div class="forget_box">
            <p class="forget_method">��ѡ���һ�����ķ�ʽ</p>
            <input class="methods bg" type="button" value="ͨ���ֻ����һ�����" onclick="reset_way('phone');"/>
            <input class="methods bg" type="button" value="ͨ�������һ�����" onclick="reset_way('mail');"/>
            <p class="other_method">
                ���Ϸ�ʽ���޷��һ����룿</br>
                �뷢�ʼ���contactus@mitbbs.com��ͨ���˹������һ����롣
            </p>
        </div>
    </div><!--End reset_way-->

    <div id="by_phone">
        <form class="forget_box">
            <p class="forget_p">
                <input type="text" placeholder="�������˺�"/>
                <input class="get_test_l" type="text" placeholder="�����������ֻ���"/>
                <input class="get_test_r" type="button" value="��ȡ��֤��"/>
            </p>
            <p class="forget_p">
                <input type="text" maxlength="4" placeholder="������4λ������֤��"/>
            </p>
            <input class="forget_submit font_white bg" type="submit" value="��һ��" onclick="window.location.href='logIn_forget_back.html'"/>
        </form>
    </div><!-- End by_phone-->

    <div id="by_mail">
        <form class="forget_box">
            <p class="forget_p">
                <input type="text" placeholder="�������˺�"/>
            </p>
            <p class="forget_p">
                <input type="text" placeholder="������ע������"/>
            </p>
            <p class="forget_info"> ϵͳ����������ע�������з���һ�����10Ϊ������֤����ʼ�����ע����� </p>
            <input class="forget_submit font_white bg" type="submit" value="�ύ"/>
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

                $('#title_text').html("�ֻ����һ�����");
                break;
            case "mail":
                $('#page_layer').html("1");

                $('#reset_way').hide();
                $('#by_phone').hide();
                $('#by_mail').show();

                $('#title_text').html("�����һ�����");
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

                $('#title_text').html("��������");
                break;
            case 2:
                $('#reset_way').hide();
                $('#by_phone').show();
                $('#by_mail').hide();

                $('#title_text').html("�ֻ����һ�����");
                break;
        }

        return false;
    }


    $(document).ready(function () {
        $('#reset_way').show();
        $('#by_phone').hide();
        $('#by_mail').hide();

        $('#title_text').html("��������");
    });
</script>
</body>
</html>
