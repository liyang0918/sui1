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
            wap_error_quit("��Ǹ���й�վ��ʱ��֧�ֵ�¼!");
        }
    }
    if (strcasecmp($id, "guest") == 0)
        wap_error_quit("�û���������Ϊguest");
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
        wap_error_quit("�û�������Ϊ��!");
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
    <meta name="apple-mobile-web-app-status-bar-style" content="black">    <!--�ֻ���Ļ����Ӧ����-->
    <meta name="format-detection" content="telephone=no">    <!--�ֻ���Ļ����Ӧ����-->
    <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">    <!--�ֻ���Ļ����Ӧ����-->
    <title>δ���ռ�</title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
<div class="conter">
    <div class="forget">
        <nav class="logoIn_nav bg">
            <h3 class="font_white" >��¼δ���ռ�</h3>
        </nav>
        <div class="forget_box bg">
            <form action="login.php" method="post">
            <p class="forget_p">
                <span>�˺ţ�</span><input id="id" name="id" type="text" placeholder="�û���/�ֻ�����"/>
            </p>
            <p class="forget_p">
                <span>���룺</span><input id="passwd" name="passwd" type="password" placeholder="����"/>
            </p>
            <p class="forget_code"><a href="logIn_forget.html">��������</a></p>
            <input class="forget_inp forget_lg"  name="login" id="login" type="submit" value="��¼"/>
	    <p class="nousername"><a href="wap_register.php">��û���˺ţ����ע��</a></p>
            </form>
        </div>
    </div><!--End forget-->
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
</body>
</html>
