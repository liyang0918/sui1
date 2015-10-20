<?php
session_start();
/* session 记录定位信息
 *  lon 经度,lat 纬度
 *  locate_flag: true 已定位 false 未定位
 */

$auto_location = "0";
if (!isset($_SESSION["locate_flag"]) or $_SESSION["locate_flag"] == false) {
    $auto_location = "1";
}

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
    setcookie("extra", "1|all");
    // 切换栏目需要重新加载
    echo '<script language="javascript">location.href=location.href</script>';
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


<div id="linklist">
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
             Alert("Geolocation is not supported by this browser.");
        }
    }

    function select_city() {
        var url = "/mobile/forum/request/dp_getcity.php";
        var myAjax = new Ajax.Request(url,
            {
                method: "post",
                parameters: null,
                asynchronous: false,
                onSuccess: function (ret) {
                    var ret_json = eval("(" + ret.responseText + ")");
                    var detail = document.getElementById("detail");
                    if (ret_json.detail != undefined) {
                        if (detail != undefined) {
                            detail.innerHTML = ret_json.detail;
                        }
                    } else {
                        if (detail != undefined) {
                            var new_tag = document.createElement("h2");
                            new_tag.innerHTML = "目前没有城市信息!";
                            detail.appendChild(new_tag);
                        }
                    }
                },
                onFailure: function (x) {
                    Alert("请求失败", 1);
                }
            }
        );

        return false;
    }

    function set_city(obj) {
        var val = obj.id.split("|");
        document.cookie = "extra=0|" + val[1];

        if (val[1] != "all")
            document.getElementById("city_name").innerHTML = obj.innerHTML;

        document.getElementById("detail").innerHTML = "";
        document.getElementById(val[0]).click();
        return true;
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
<?php
include_once("foot.php");
?>