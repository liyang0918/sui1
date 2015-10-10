<?php
require_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
require_once("func.php");

if(empty($_COOKIE["app_type"]))
    setcookie("app_type", "search");
if(empty($_COOKIE["app_show"]))
    setcookie("app_show", iconv("GBK","UTF-8//IGNORE","����"));
if(empty($_COOKIE["sec_category"]))
    setcookie("sec_category", "all");

$page_title = "����";
?>
<html lang="zh">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>δ���ռ�</title>
        <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/silder.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/reg.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/footer.css" type="text/css" media="screen"/>
    </head>
<body>
<div class="conter">
    <nav class="navone">
        <div class="navone_menu">
            <h3 class="navone_h3"><img class="navone_forum" src="img/menu.png" alt="menu.png"/><?PHP echo $page_title?></h3>
        </div>
        <img class="navone_space" src="img/space.png" alt="space.png"/>
        <?php if($currentuser["userid"]=="guest")  {?>
            <a class="navone_home" href="login.php">��¼</a>
        <?php }else{ ?>
            <a class="navone_home" href="jiaye.php"><img src="img/home.png" alt="home.png"/></a>
        <?php } ?>
    </nav>
    <ul class="navone_ul">
        <li class="navone_li"><a href="index.php"><img src="img/item.png" alt="item.png"/>��̳</a></li>
        <li class="navone_li"><a href="news.php"><img src="img/item.png" alt="item.png"/>����</a></li>
        <li class="navone_li"><a href="club.php"><img src="img/item.png" alt="item.png"/>���ֲ�</a></li>
        <li class="navone_li"><a href="immigration.php"><img src="img/item.png" alt="item.png"/>����ר��</a></li>
        <li class="navone_li"><a href="dianping.php"><img src="img/item.png" alt="item.png"/>����</a></li>
        <li class="navone_li noborder"><a href="search.php"><img src="img/item.png" alt="item.png"/>����</a></li>
    </ul>
	<div class="search_box">
	    <p class="search_name">��������</p>
	    <p class="search_box">
	        <input id="search_board" class="search_txt" type="text" placeholder="�������������"/>
            <input id="search1" class="search_btn" type="button" value="����" onclick="go_to_search(this)"/>
	    </p>
	</div>
	<div class="search_box">
	    <p class="search_name">���ֲ�����</p>
	    <p class="search_box">
	        <input id='search_club' class="search_txt" type="text" placeholder="��������ֲ�����"/>
            <input id="search2" class="search_btn" type="button" value="����"  onclick="return go_to_search(this)"/>
	    </p>
	</div>

	<div class="search_box">
	    <p class="search_name">��Ա����</p>
	    <p class="search_box">
	        <input id='search_users' class="search_txt" type="text" placeholder="�������Ա�˺�"/>
            <input id="search3" class="search_btn" type="button" value="����" onclick="return go_to_search(this)"/>
	    </p>
	</div>
</div>
<div class="footer">
    <ul>
        <li>mitbbs.com</li>
        <li><a href="index.php">�ͻ���</a></li>
        <li><a href="http://www.mitbbs.com">���԰�</a></li>
        <ul>
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript">
    function go_to_search(obj) {
        if (obj.id == "search1") {
            var text = document.getElementById("search_board").value;
            var url = "one_search.php?board="+text;
            document.location = url;
        } else if (obj.id == "search2") {
            var text = document.getElementById("search_club").value;
            var url = "one_search.php?club="+text;
            document.location = url;
        } else if (obj.id == "search3") {
            var text = document.getElementById("search_users").value;
            var url = "one_search.php?member="+text;
            document.location = url;
        }

        return false;
    }
</script>
<script type="text/javascript">
    $(document).ready(document.cookie="current_page=1");
    $(document).ready(function () {
        var sec_category=getCookie_wap("sec_category");
        $("#"+sec_category).addClass("active");
        sec_category_auto();
    });

</script>
</body>
</html>
