<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
$link = db_connect_web();

$curr_url = $_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];
if ($currentuser["userid"] == "guest") {
    setcookie("before_login", $curr_url);
?>
<script type="text/javascript">
    window.location.href = "login.php";
</script>
<?php
}

$user_id = $currentuser["userid"];
$user_num_id = $currentuser["num_id"];
$total_shop = getMyDpCollectShopTotal($link, $user_num_id);
$total_dp = getMyDpCommentTotal($link, $user_num_id);
$total_pic = getMyDpAlbumTotal($link, $user_num_id);
// 每页显示的条目数
$num = 20;


// all:"unread","total","delete","send"
$arr = getMailNumByType($user_id);

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
        <a href="" onclick="return go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        我的点评
    </div><!--<End ds_box-->
    <nav class="navtwo">
        <ul class="navtwo_ul">
            <li><a id="label_shop" href="" onclick="return get_my_shop();">我收藏的店铺</a></li>
            <li><a id="label_dp" href="" onclick="return get_my_dp();">我的点评</a></li>
            <li><a id="label_pic" href="" onclick="return get_my_pic();">我的图片</a></li>
        </ul>
    </nav><!---------End navtwo-->
    <!-- 收藏的店铺 -->
    <span id="curr_active" hidden="hidden"></span>

    <div id="my_shop">
        <ul id="shop_content" class="dn_conter2">
        </ul>
    </div>

    <!-- 我的点评 -->
    <div id="my_dp" class="ds_group nomargin_top">
        <ul id="dp_content" class="member_group">
        </ul>
    </div>

    <!-- 我的相册 -->
    <div id="my_pic">
        <ul id="pic_content" class="jy_dp_pic_box">
        </ul>
    </div>

    <div id="page_part" class="pages_box margin-bottom">
        <a href="" id="pre_page" onclick="return get_pre_next(-1);">上一页</a>
        <span id='sep_left'>...</span>
        <a id="now_page"></a>
        <span id='sep_right'>...</span>
        <a href="" id="sub_page" onclick="return get_pre_next(1);">下一页</a>
    </div>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.nivo.slider.js" charset="utf-8"></script>
    <script type="text/javascript" src="js/js.js"></script>
    <script type="text/javascript" src="js/slide.js"></script>
    <script type="text/javascript">
        function get_my_shop() {
            $('#curr_active').html("shop");
            $('#label_shop').attr("class", "active");
            $('#label_dp').removeClass("active");
            $('#label_pic').removeClass("active");

            $('#my_shop').show();
            $('#my_dp').hide();
            $('#my_pic').hide();

            $('#now_page').html("1");

            get_pre_next(0);
            return false;
        }

        function get_my_dp() {
            $('#curr_active').html("dp");
            $('#label_dp').attr("class", "active");
            $('#label_shop').removeClass("active");
            $('#label_pic').removeClass("active");

            $('#my_dp').show();
            $('#my_shop').hide();
            $('#my_pic').hide();

            $('#now_page').html("1");

            get_pre_next(0);
            return false;
        }

        function get_my_pic() {
            $('#curr_active').html("pic");
            $('#label_pic').attr("class", "active");
            $('#label_shop').removeClass("active");
            $('#label_dp').removeClass("active");

            $('#my_pic').show();
            $('#my_shop').hide();
            $('#my_dp').hide();

            $('#now_page').html("1");

            get_pre_next(0);
            return false;
        }

        function get_pre_next(direction) {
            var curr_active = $('#curr_active').html();
            var user_num_id = "<?php echo $user_num_id; ?>";
            var total_row = 0;
            switch (curr_active) {
                case "shop":
                    total_row = parseInt("<?php echo $total_shop; ?>");
                    break;
                case "dp":
                    total_row = parseInt("<?php echo $total_dp; ?>");
                    break;
                case "pic":
                    total_row = parseInt("<?php echo $total_pic; ?>");
                    break;
            }

            var num = parseInt("<?php echo $num;?>");
            var total_page = parseInt(total_row/num)+1;
            if (total_page <= 1)
                $('#page_part').hide();
            else
                $('#page_part').show();

            var page = $('#now_page').html();

            switch (direction) {
                case -1:
                    if (page > 1)
                        page--;
                    else
                        page = 1;
                    break;
                case 0:
                    if (page < 1)
                        page = 1;

                    if (page > total_page)
                        page = total_page;

                    break;
                case 1:
                    if (page < total_page)
                        page++;
                    else
                        page = total_page;
                    break;
                default :
                    if (page < 1)
                        page = 1;

                    if (page > total_page)
                        page = total_page;

                    break;
            }

            var url = "/mobile/forum/request/mydianping.php";
            var para = {
                "user_num_id":user_num_id,
                "type":curr_active,
                "page":page,
                "num":num
            };

            $.ajax({
                type: "POST",
                url: url,
                async: true,
                data: para,
                success: function (ret) {
                    var ret_json = eval("(" + ret + ")");
                    if (ret_json.content != undefined) {
                        $('#'+curr_active+'_content').html(ret_json.content);
                        show_page(page, total_page);
                    } else {
                    }
                },
                error: function (ret) {
                    Alert("请求失败", 1);
                }
            });

            return false;
        }

        function show_page (page, total_page) {
            $('#now_page').html(page);

            // 上一页
            if (page == 1)
                $('#pre_page').hide();
            else
                $('#pre_page').show();

            // 左省略号
            if (page < 3)
                $('#sep_left').hide();
            else
                $('#sep_left').show();

            // 右省略号
            if (total_page-page < 2)
                $('#sep_right').hide();
            else
                $('#sep_right').show();

            // 下一页
            if (total_page-page < 1)
                $('#sub_page').hide();
            else
                $('#sub_page').show();
        }

        $(document).ready(function () {
            get_my_shop();
        });
    </script>

<?php
include_once("foot.php");
?>