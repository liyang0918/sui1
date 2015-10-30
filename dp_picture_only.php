<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
$link = db_connect_web();

$shop_id = $_GET["shop_id"];
$pic_num = $_GET["pic_num"];
$type = $_GET["type"];
if ($type != "all" and $type != "dish" and $type != "env") {
    $type = "all";
}

$label_text = "";
switch ($type) {
    case "all":
        $label_text = "全部图片";
        break;
    case "dish":
        $label_text = "菜品图片";
        break;
    case "env":
        $label_text = "环境图片";
        break;
}

$num = 1;
if(empty($page)){
    $page = 1;
}

//page part
$total_row = getShopPictureTotal($link, $shop_id, $type);
//end page
$t_data = getShopPictureList($link, $shop_id, $type, $page, $per_page);
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>未名空间</title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/silder.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/reg.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/footer.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/dp_picList.css" type="text/css">
</head>
<body>
<div class="conter">
    <nav class="navone">
        <div class="navone_menu">
            <h3 class="navone_h3"><img class="navone_forum" src="img/menu.png" alt="menu.png"/>点评</h3>
        </div>
        <img class="navone_space" src="img/space.png" alt="space.png"/>
        <?php if($currentuser["userid"]=="guest")  {?>
            <a class="navone_home" href="login.php">
                <img src="img/home.png" alt="home.png">
            </a>
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
    <div class="ds_box border_bottom">
        <a onclick="go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php echo $label_text; ?>
    </div><!--------End ds_box-->
    <div class="dp_fullImg_box">
        <div class="fullImg_up">
            <img src="images/1.png" alt="1.png"/>
        </div>
        <div class="fullImg_down">
            <p id="tag_name">图片内容超出部分隐藏</p>
            <p class="num">
                <span class="pic_num">1</span>/
                <span class="total_num">10</span>
            </p>
        </div>
        <div class="dp_fullImg_btn">
            <a href="javascript:;">上一张</a>
            <a href="javascript:;">下一张</a>
        </div>
    </div>
</div>
<br><br><br><br>
<div class="footer">
    <ul>
        <li>mitbbs.com</li>
        <li><a href="index.php">客户端</a></li>
        <li><a href="http://www.mitbbs.com">电脑版</a></li>
    </ul>
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/funs.js"></script>
<script type="text/javascript">
</script></body>
</html>
