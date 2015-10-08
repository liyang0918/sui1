<?php
require_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
require_once("func.php");

if(!empty($_COOKIE["app_show"]))
    $page_title= iconv("UTF-8","GBK",$_COOKIE["app_show"]);
else
    $page_title = iconv("UTF-8", "GBK", "论坛");
?>
<html lang="zh">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">    
        <meta name="format-detection" content="telephone=no">    
        <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>未名空间</title>
        <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/silder.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
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
                <a class="navone_home" href="login.php">登录</a>
                    <?php }else{ ?>
                <a class="navone_home" href="jiaye.php"><img src="img/home.png" alt="home.png"/></a>
                <?php } ?>
            </nav>
            <ul class="navone_ul">
                <li class="navone_li"><a href="index.php"><img src="img/item.png" alt="item.png"/>论坛</a></li>
                <li class="navone_li"><a href="news.php"><img src="img/item.png" alt="item.png"/>新闻</a></li>
                <li class="navone_li"><a href="club.php"><img src="img/item.png" alt="item.png"/>俱乐部</a></li>
                <li class="navone_li"><a href="immigration.php"><img src="img/item.png" alt="item.png"/>移民专栏</a></li>
                <li class="navone_li"><a href="dianping.php"><img src="img/item.png" alt="item.png"/>点评</a></li>
                <li class="navone_li noborder"><a href="search.php"><img src="img/item.png" alt="item.png"/>搜索</a></li>
            </ul>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jquery.nivo.slider.js" charset="utf-8"></script>
        <script type="text/javascript" src="js/js.js"></script>
        <script type="text/javascript" src="js/slide.js"></script>
        <script type="text/javascript" src="js/funs.js"></script>
        <script type="text/javascript" src="../../js/prototype.js"></script>
