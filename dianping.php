<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
if(empty($_COOKIE["app_type"]))
    setcookie("app_type", "dianping");
if(empty($_COOKIE["app_show"]))
    setcookie("app_show", iconv("GBK","UTF-8//IGNORE", "点评"));
if(empty($_COOKIE["sec_category"]))
    setcookie("sec_category", "dp_recommend");

if(!is_own_label($_COOKIE["sec_category"], "dianping")) {
    setcookie("app_type", "dianping");
    setcookie("app_show", iconv("GBK", "UTF-8//IGNORE", "点评"));
    setcookie("sec_category", "dp_recommend");
    setcookie("extra", "0|all");
    // 切换栏目需要重新加载
    echo '<script language="javascript">location.href=location.href</script>';
}

list($mode, $city) = getExtraValue($_GET["extra"]);
if ($mode != "0" and $mode != "1") {
    $mode = "0";
    $city = "all";
}

if (empty($city))
    $city = "all";

$cityCname = getDpCityCname($city);

include_once("head.php");
?>

    <ul class="dp_group1 border_bottom">
        <li class="dp_list_l">
<?php
            if ($mode == "0" or $city == "all")
                echo "请选择城市";
            else
                echo $cityCname;
?>
    <a id="1" href="" onclick="sec_select(this, 'dp_setcity')"><img src="img/btn_down.png" alt="btn_down.png"/></a>
    </li>
    <li class="dp_list_r">
        <a href="" id="dp_recommend" onclick="sec_category(this)">推荐</a>
        <a href="" id="dp_near" onclick="sec_category(this)">附近</a>
        <a href="" id="dp_search" onclick="sec_category(this)">搜索</a>
        <a href="" id="dp_rank" onclick="sec_category(this)">排行</a>
    </li>
    </ul>


    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/funs.js"></script>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" />
    <script type="text/javascript">
        $(document).ready(document.cookie="current_page=1");
        $(document).ready(function () {
            var sec_category=getCookie_wap("sec_category");
            $("#"+sec_category).addClass("active");
            sec_category_auto();
        });

    </script>

<div id="linklist">
</div>
<div id="carouselfigure">
</div>
<div id="detail">
</div>
<div id="pagebox">
</div>
<?php
include_once("foot.php");
?>