<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."func.php");
include_once("head.php");
$link = db_connect_web();

// add_flag: 0 添加店铺，1添加成功，2添加失败
$add_flag = 0;
if (empty($_POST))
    $add_flag = 0;
else
    $add_flag = 1;

if ($add_flag == 0) {
    $city_list = getDpCityList($link);
    $current_page = $_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];
} else {
//    show_result($_POST);
    $shopinfo["cnName"] = $_POST["cnName"];
    $shopinfo["city_type"] = $_POST["city_list"];
    $shopinfo["type_set"] = $_POST["food_class"];
    $shopinfo["enName"] = $_POST["enName"];
    $shopinfo["location"] = $_POST["address"];
    $shopinfo["contact"] = $_POST["phone"];
    $shopinfo["business_time"] = $_POST["business_time"];
    $shopinfo["descrption"] = "该店铺尚未添加描述信息";
    if (isset($_POST["pos_lat"]) and isset($_POST["pos_lng"])) {
        $shopinfo["lat"] = $_POST["pos_lat"];
        $shopinfo["lng"] = $_POST["pos_lng"];
    } else {
        $shopinfo["lat"] = 0;
        $shopinfo["lng"] = 0;
    }

    $shopinfo["add_user"] = $currentuser["userid"];
    if (insertShop($link, $shopinfo)) {
        $add_flag = 1;
    } else {
        $add_flag = 2;
    }
}
?>
    <div class="ds_box border_bottom">
        <a onclick="go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        添加店铺
    </div><!--<End ds_box-->
<?php if ($add_flag == 0) { ?>
    <form id="addShopForm" class="border_bottom" action="add_shop.php" method="post">
        <div><span><i>*</i>商铺名：</span><input id="cnName" name="cnName" class="addshop_txt" type="text" onblur="dp_check_cnName(this);"/><span id="prompt1" style="display:none">请填写商铺名称</span></div>
        <div><span>英文名：</span><input id="enName" name="enName" class="addshop_txt" type="text"/></div>
        <div>
            <span><i>*</i>分类：</span>
            <select id="food_class" name="food_class" onchange="dp_check_food_class(this)">
                <option  selected="selected" value="10">按菜系</option>
                <?php for ($i = 1; $i < count($food_class_list); $i++) { ?>
                <option value="<?php echo $food_class_list[$i]["type"]; ?>"><?php echo $food_class_list[$i]["name"]; ?></option>
                <?php } ?>
            </select>
            <span id="prompt2" style="display:none">请选择菜系</span>
        </div>
        <div>
            <span><i>*</i>地址：</span>
            <select id="city_list" name="city_list" onchange="dp_check_city(this)">
                <option selected="selected" value="10">按城市</option>
                <?php for ($i = 0; $i < count($city_list); $i++) { ?>
                    <option value="<?php echo $city_list[$i]["city_concise"]; ?>"><?php echo $city_list[$i]["city_name"]; ?></option>
                <?php } ?>
            </select>
            <span id="prompt3" style="display:none">请选择城市</span>
            <input id="address" name="address" class="addshop_txt addshop_p" type="text" placeholder="填写详细地址,包含城市名,国家名" onchange="return dp_getLocation(this);"/>
            <span id="prompt4"></span>
            <input id="pos_lat" name="pos_lat" type="hidden" value=""/>
            <input id="pos_lng" name="pos_lng" type="hidden" value=""/>
        </div>
        <div><span>电话：</span><input id="phone" name="phone" class="addshop_txt" type="text"/></div>
        <div><span>营业时间：</span><input id="business_time" name="business_time" class="addshop_txt" type="text"/></div>
        <input type="button" value="提交" onclick="return dp_addshop();"/>
    </form><!--End board_wrap-->
    <script type="text/javascript">
        function dp_addshop() {
            var user = "<?php echo $currentuser["userid"]; ?>";
            var curr_url = "<?php echo $current_page; ?>";
            if (user == "" || user == "guest") {
                document.cookie = "before_login="+curr_url;
                window.location = "login.php";
                return false;
            }

            return dp_check_addshop_form();
        }
    </script>

<?php } elseif ($add_flag == 1) { ?>
    <h2>店铺添加成功</h2>
<?php } elseif ($add_flag == 2) { ?>
    <h2>店铺添加失败</h2>
<?php
}
include_once("foot.php");
mysql_close($link);
?>