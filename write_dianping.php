<?php
session_start();
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");

$curr_url = $_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];

if (empty($currentuser) or $currentuser["userid"] == "guest") {
    setcookie("before_login", $curr_url);
    header("Location:login.php");
}

$shop_id = $_GET["shop_id"];
$father_page = "one_shopinfo.php?shop_id=$shop_id";

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
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/footer.css" type="text/css" media="screen"/>
</head>
<body>
<div class="conter">
    <nav class="navone">
        <div class="navone_menu">
            <h3 class="navone_h3"><img class="navone_forum" src="img/menu.png" alt="menu.png"/>点评</h3>
        </div>
        <img class="navone_space" src="img/space.png" alt="space.png"/>
        <?php
        if($currentuser["userid"]=="guest")  {
            setcookie("before_login", $curr_url);
        ?>
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
    写点评
</div><!--<End ds_box-->
<section class="shop-mycomment">
    <form id="write_dp_form" action="" method="">
        <div class="mycomm-frame">
            <ul>
                <li id="all_comment" class="all-comment">
                    <span class="name">总体评价：</span>
                    <a href="javascript:;"  class="comm-star "></a>
                    <a href="javascript:;"  class="comm-star "></a>
                    <a href="javascript:;"  class="comm-star "></a>
                    <a href="javascript:;"  class="comm-star "></a>
                    <a href="javascript:;"  class="comm-star "></a>
                </li>
                <li>
                    <span class="name">口味</span>
                    <select id="taste_score" name="score1" title="口味">
                        <option value='-1'>--请选择--</option>
                        <option value="5" >非常好(5)</option>
                        <option value="4" >很好(4)</option>
                        <option value="3" >好(3)</option>
                        <option value="2" >一般(2)</option>
                        <option value="1" >差(1)</option>
                    </select>
                    <div class="select-btn">
                        <i class="arrow-down" style="top:12px;right:12px;"></i>
                    </div>
                </li>
                <li >
                    <span class="name">环境</span>
                    <select id="env_score" name="score2" title="环境">
                        <option value='-1'>--请选择--</option>
                        <option value="5" >非常好(5)</option>
                        <option value="4" >很好(4)</option>
                        <option value="3" >好(3)</option>
                        <option value="2" >一般(2)</option>
                        <option value="1" >差(1)</option>
                    </select>
                    <div class="select-btn">
                        <i class="arrow-down" style="top:12px;right:12px;"></i>
                    </div>
                </li>
                <li >
                    <span class="name">服务</span>
                    <select id="sev_score" name="score3" title="服务">
                        <option value='-1'>--请选择--</option>
                        <option value="5" >非常好(5)</option>
                        <option value="4" >很好(4)</option>
                        <option value="3" >好(3)</option>
                        <option value="2" >一般(2)</option>
                        <option value="1" >差(1)</option>
                    </select>
                    <div class="select-btn">
                        <i class="arrow-down" style="top:12px;right:12px;"></i>
                    </div>
                </li>
                <li >
                    <span class="name">人均</span>
                    <input id="J_average" value="" name="avgPrice" placeholder="（选填）" class="inp-t"/>
                </li>
                <li >
                    <span class="name">评价</span>
                    <textarea id="J_describe" placeholder="最少15字" name="body" cols="" rows="8" class="tarea"></textarea>
                </li>
            </ul>
        </div>
        <p><a id="J_submit" href="javascript:;" title="提交" class="icon-btn icon-btn-orange" onclick="return dp_check_comment();">提交</a></p>
    </form>
</section>
<!--内容 end-->

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
    function dp_check_comment() {
        var all_comment = document.getElementById("all_comment");
        var tags_a = all_comment.getElementsByTagName("a");
        for(var i = 0; i < tags_a.length; i++) {
            if (tags_a[i].className.match(/star-cur/) == null)
                break;
        }
        if (i == 0) {
            Alert("总体评分不能为空", 1);
            return false;
        }

        var comment_score = i;
        var taste_score = document.getElementById("taste_score").value;
        if (taste_score == "-1") {
            Alert("请填写口味分值", 1);
            return false;
        }

        var env_score = document.getElementById("env_score").value;
        if (env_score == "-1") {
            Alert("请填写环境分值", 1);
            return false;
        }

        var sev_score = document.getElementById("sev_score").value;
        if (sev_score == "-1") {
            Alert("请填写服务分支", 1);
            return false;
        }

        var avg_pay = 0;
        var J_average = document.getElementById("J_average");
        if (J_average.value != undefined && J_average.value.match(/^\d+(\.\d+)?$/)) {
            avg_pay = J_average.value;
        }

        var des = document.getElementById("J_describe").value;
        if (des.replace(/(^\s*)|(\s*$)/g, "").length < 15) {
            Alert("点评字数不少于15字", 1);
            return false;
        }

        var url = "/mobile/forum/request/dp_writecomment.php?shop_id="+"<?php echo $shop_id; ?>";
        var para = {
            "shop_id":"<?php echo $shop_id; ?>",
            "comment_score":comment_score,
            "taste_score":taste_score,
            "env_score":env_score,
            "sev_score":sev_score,
            "avg_pay":avg_pay,
            "des":des
        };

        dp_send_comment(url, para, "<?php echo $father_page; ?>");
        return false;
    }
</script>
</body>
</html>

