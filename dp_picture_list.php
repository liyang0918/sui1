<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
$link = db_connect_web();

$shop_id = $_GET["shop_id"];
$type = $_GET["type"];
if ($type != "all" and $type != "dish" and $type != "env") {
    $type = "all";
}

$user_num_id = -1;
if (isset($_GET["user_num_id"])) {
    $user_num_id = $_GET["user_num_id"];
}
//data part
$url_page = url_generate(4, array(
        "action" => "dp_picture_list.php",
        "args" => array("shop_id"=>$shop_id, "type"=>$type)
    ))."&page=";
$per_page = 24;
$page = intval($_GET["page"]);
if(empty($page)){
    $page = 1;
}

//page part
$total_row = getShopPictureTotal($link, $shop_id, $type);
//end page
$t_data = getShopPictureList($link, $shop_id, $type, $page, $per_page, true, $user_num_id);
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="gb2312">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>未名空间</title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
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
        店铺图片列表
    </div><!--------End ds_box-->
    <ul class="dp_piclist_nav">
        <li><a id="all" href="" onclick="return jump_to_this(this);">全部</a></li>
        <li><a id="dish" href="" onclick="return jump_to_this(this);">菜品</a></li>
        <li><a id="env" href="" onclick="return jump_to_this(this);">环境</a></li>
    </ul>
    <ul class="shop_picList">
<!--        <li>-->
<!--            <div>-->
<!--                <a href="#"><img src="img/sky.png" alt="sky.pn"/></a>-->
<!--                <p><a href="#">店铺名称</a></p>-->
<!--            </div>-->
<!--        </li>-->
    <?php foreach ($t_data as $i=>$each) { ?>
        <li>
            <div>
                <a href="dp_picture_single.php?shop_id=<?php echo $shop_id; ?>&pic_num=<?php echo $i+1; ?>&type=<?php echo $type; ?>&user_num_id=<?php echo $user_num_id; ?>"><img src="<?php echo $each["img"]; ?>" alt="picture"/></a>
                <p><?php echo $each["tag_name"]; ?></p>
            </div>
        </li>
    <?php } ?>
    </ul>

<?php
    // 分页显示
    echo page_partition($total_row, $page, $per_page);
?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.nivo.slider.js" charset="utf-8"></script>
    <script type="text/javascript" src="js/js.js"></script>
    <script type="text/javascript" src="js/slide.js"></script>
    <script type="text/javascript">
        function jump_to_this(obj) {
            var jumpto = "dp_picture_list.php?shop_id=<?php echo $shop_id; ?>&type="+obj.id+"&user_num_id="+"<?php echo $user_num_id; ?>";
            window.location.href = jumpto;

            return false;
        }

        $(document).ready(function () {
            var page = <?php echo $page;?>;

                $("#page_now").css("background-color", "blue");
                $("#page_now").removeAttr("href");

            //alert($("#page_part a").size());
            $("#page_part a").click(function(){
                var url_page = "<?php echo $url_page;?>";
                if(this.id == "pre_page")
                    url_page = url_page+(page-1);
                else if(this.id == "sub_page")
                    url_page = url_page+(page+1);
                else{
                    url_page = url_page+$(this).text();
                }
                this.href = url_page;
            });
        });
        $(document).ready(function () {
            var active = "<?php echo $type; ?>";
            $('.dp_piclist_nav>li>a').removeClass("active");
            switch (active) {
                case "all":
                    $('#all').addClass("active");
                    break;
                case "dish":
                    $('#dish').addClass("active");
                    break;
                case "env":
                    $('#env').addClass("active");
                    break;
            }
        });
    </script>

<?php
include_once("foot.php");
?>
