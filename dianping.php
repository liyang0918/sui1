<?php
session_start();
/* session 记录定位信息
 *  lon 经度,lat 纬度
 *  locate_flag: true 已定位 false 未定位
 */

include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
if(empty($_COOKIE["app_type"]))
    setcookie("app_type", "dianping");
if(empty($_COOKIE["app_show"]))
    setcookie("app_show", iconv("GBK","UTF-8//IGNORE", "点评"));
if(empty($_COOKIE["sec_category"]))
    setcookie("sec_category", "dp_recommend");

if (!is_own_label($_COOKIE["sec_category"], "dianping")) {
    // 重新进入点评时重新定位
    $_SESSION = array();
    setcookie("app_type", "dianping");
    setcookie("app_show", iconv("GBK", "UTF-8//IGNORE", "点评"));
    setcookie("sec_category", "dp_recommend");
    setcookie("extra", "1|all");
    // 切换栏目需要重新加载
    echo '<script language="javascript">location.href=location.href</script>';
}

$auto_location = "0";
if (!isset($_SESSION["locate_flag"]) or $_SESSION["locate_flag"] == false) {
    $auto_location = "1";
}

list($mode, $city) = getExtraValue($_COOKIE["extra"]);

if ($mode != "0" and $mode != "1") {
    $mode = "1";
    $city = "all";
}

if (empty($city))
    $city = "all";

$cityCname = getDpCityCname($city);

include_once("head.php");
?>

<div class="navtwo">
    <ul class="dp_group1 border_bottom">
        <li class="dp_list_l">
            <span id="city_name">
<?php
            if ($city == "all")
                echo "请选择城市";
            else
                echo $cityCname;
?>
            </span>
            <a onclick="return select_city();"><img src="img/btn_down.png" alt="btn_down.png"/></a>
        </li>
        <li class="dp_list_r">
        <a href="" id="dp_recommend" onclick="sec_category(this);">推荐</a>
        <a href="" id="dp_near" onclick="sec_category(this);">附近</a>
        <a href="" id="dp_search" onclick="sec_category(this);">搜索</a>
        <a href="" id="dp_rank" onclick="sec_category(this);">排行</a>
    </li>
    </ul>
</div>


<div id="near" style="display: none">
    <div class="nav">
        <div class="nav_list"><a class="open_01" href="javascript:void(0)">附近 <img src="img/btn_down.png" alt="btn_down.png"/></a></div>
        <div class="nav_list"><a class="open_02" href="javascript:void(0)">分类<img src="img/btn_down.png" alt="btn_down.png"/></a></div>
        <div class="nav_list"><a class="open_03" href="javascript:void(0)">排序<img src="img/btn_down.png" alt="btn_down.png"/></a></div>
    </div><!-- End nav-->
    <div class="box_mask" style="display: none"></div><!--------End box_mask-->
    <div class="dn_box box_01" style="display: none">
        <div class="nav_open">
            <div class="nav_list"><a class="close_01" href="javascript:void(0)">附近 <img src="img/btn_down.png" alt="btn_down.png"/></a></div>
            <div class="nav_list"><a class="open_02" href="javascript:void(0)">分类<img src="img/btn_down.png" alt="btn_down.png"/></a></div>
            <div class="nav_list"><a class="open_03" href="javascript:void(0)">排序<img src="img/btn_down.png" alt="btn_down.png"/></a></div>
        </div>
        <ul>
            <li class="first"><a href="javascript:;">附近</a></li>
            <?php foreach ($near_list as $each) { ?>
                <li><a href="" id="near_<?php echo $each["type"]; ?>" onclick="return dp_set_shop_type(this, 'near_type');"><?php echo $each["name"]; ?></a></li>
            <?php } ?>
        </ul>
    </div><!--------End dn_box-->
    <div class="dn_box box_02" style="display: none">
        <div class="nav_open">
            <div class="nav_list"><a class="open_01" href="javascript:void(0)">附近 <img src="img/btn_down.png" alt="btn_down.png"/></a></div>
            <div class="nav_list"><a class="close_02" href="javascript:void(0)">分类<img src="img/btn_down.png" alt="btn_down.png"/></a></div>
            <div class="nav_list"><a class="open_03" href="javascript:void(0)">排序<img src="img/btn_down.png" alt="btn_down.png"/></a></div>
        </div>
        <ul>
            <li class="first"><a id="food_class_<?php echo $food_class_list[0]["type"]; ?>" href="" onclick="return dp_set_shop_type(this, 'food_class_type');"><?php echo $food_class_list[0]["name"]; ?></a></li>
            <?php for ($i = 1; $i < count($food_class_list); $i++) { ?>
                <?php $each = $food_class_list[$i];?>
                <li><a href="" id="food_class_<?php echo $each["type"]; ?>" onclick="return dp_set_shop_type(this, 'food_class_type');"><?php echo $each["name"]; ?></a></li>
            <?php } ?>
        </ul>
    </div><!--------End dn_box-->
    <div class="dn_box box_03" style="display: none">
        <div class="nav_open">
            <div class="nav_list"><a class="open_01" href="javascript:void(0)">附近 <img src="img/btn_down.png" alt="btn_down.png"/></a></div>
            <div class="nav_list"><a class="open_02" href="javascript:void(0)">分类<img src="img/btn_down.png" alt="btn_down.png"/></a></div>
            <div class="nav_list"><a class="close_03" href="javascript:void(0)">排序<img src="img/btn_down.png" alt="btn_down.png"/></a></div>
        </div>
        <ul>

            <li class="first"><a id="order_<?php echo $order_list[0]["type"]; ?>" href="" onclick="return dp_set_shop_type(this, 'order_type');"><?php echo $order_list[0]["name"]; ?></a></li>
            <?php for ($i = 1; $i < count($order_list); $i++) { ?>
                <?php $each = $order_list[$i]; ?>
                <li><a id="order_<?php echo $each["type"]; ?>" href="" onclick="return dp_set_shop_type(this, 'order_type');"><?php echo $each["name"]; ?></a></li>
            <?php } ?>
        </ul>
    </div><!--------End dn_box-->
</div>

<div id="search" style="display: none">
    <div class="dp_search_box">
        <div class="dp_search_box_item">
            <p>
                <img src="img/search.png" alt="search.png"/>
                <input onkeypress="return shop_search(event);" id="search_kws" type="search" placeholder="请输入店铺名称"/>
            </p>
        </div>
        <div class="border_bottom border_top">
            <a href="" onclick="return select_food_class();">
                <span>按分类查找</span>
                <img src="img/btn-right.png" alt="btn-right.png"/>
            </a>
        </div>
        <div class="border_bottom">
            <a href="add_shop.php">
                <span>添加新店铺</span>
                <img src="img/btn-right.png" alt="btn-right.png"/>
            </a>
        </div>
    </div><!-- End dp_search_box-->
</div>

<div id="rank" style="display: none">
    <nav class="navtwo">
        <ul class="navtwo_ul immigration_nav">
            <li><a href="" id="rank_<?php echo $rank_list[0]["type"]; ?>" class="redgo" onclick="return dp_show_rank(this);" ><?php echo $rank_list[0]["name"]; ?></a></li>
            <?php for ($i = 1; $i < count($rank_list); $i++) { ?>
                <?php $each = $rank_list[$i]; ?>
                <li><a href="" id="rank_<?php echo $each["type"]; ?>" onclick="return dp_show_rank(this);"><?php echo $each["name"]; ?></a></li>
            <?php } ?>

        </ul>
        <span class="redgo"></span>
    </nav><!---------End navtwo-->
</div>

<div style="display: none">
    <span id="near_type">0</span>
    <span id="food_class_type">all</span>
    <span id="order_type">0</span>
    <span id="rank_type">hot</span>
</div>

<div id="carouselfigure">
</div>
<div id="detail">
</div>
<div id="pagebox">
</div>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funs.js"></script>
<script type="text/javascript">
    var auto_location = parseInt("<?php echo $auto_location; ?>");
    if (auto_location == 1) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(location_callback, location_error);
        } else {
             Alert("Geolocation is not supported by this browser.", 1);
        }
    }

</script>
<script type="text/javascript">
    $(document).ready(document.cookie="current_page=1");
    $(document).ready(document.cookie="distance_null_num=0");
    $(document).ready(function () {
        var queryString;
        var sec_category=getCookie_wap("sec_category");

        var near_type = document.getElementById("near_type").innerHTML;
        var food_class_type = document.getElementById("food_class_type").innerHTML;
        var order_type = document.getElementById("order_type").innerHTML;
        var rank_type = document.getElementById("rank_type").innerHTML;

        switch (sec_category) {
            case "dp_near":
                queryString = "near_type="+near_type+"&food_class_type="+food_class_type+"&order_type="+order_type;
                dp_show_secmenu(sec_category);
                break;
            case "dp_search":
                dp_show_secmenu(sec_category);
                break;
            case "dp_rank":
                queryString = "rank_type="+rank_type;
                dp_show_secmenu(sec_category);
                break;
        }

        $("#"+sec_category).addClass("active");

        sec_category_auto_dp(queryString);
    });

</script>
<?php
include_once("foot.php");
?>