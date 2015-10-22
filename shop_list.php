<?php
session_start();
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."func.php");
include_once("head.php");
$link = db_connect_web();
$shopname_search = 0;
$near_type = 0;
$food_class_type = "all";
$order_type = 0;
$kws = "";

if (isset($_GET["food_class"])) {
    $food_class_type = $_GET["food_class"];
    $title = foodType2String($food_class_type);
}

if (isset($_GET["kws"])) {
    $kws = $_GET["kws"];
    $title = $kws;
    $shopname_search = 1;
}


?>
<div class="ds_box border_bottom">
    <a onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    <?php echo $title; ?>
</div><!--<End ds_box-->
<div id="near">
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
                <li><a href="" id="near_<?php echo $each["type"]; ?>" onclick="return dp_set_shop_type(this, 'near_type', 'dp_shoplist');"><?php echo $each["name"]; ?></a></li>
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
            <li class="first"><a id="food_class_<?php echo $food_class_list[0]["type"]; ?>" href="" onclick="return dp_set_shop_type(this, 'food_class_type', 'dp_shoplist');"><?php echo $food_class_list[0]["name"]; ?></a></li>
            <?php for ($i = 1; $i < count($food_class_list); $i++) { ?>
                <?php $each = $food_class_list[$i];?>
                <li><a href="" id="food_class_<?php echo $each["type"]; ?>" onclick="return dp_set_shop_type(this, 'food_class_type', 'dp_shoplist');"><?php echo $each["name"]; ?></a></li>
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

            <li class="first"><a id="order_<?php echo $order_list[0]["type"]; ?>" href="" onclick="return dp_set_shop_type(this, 'order_type', 'dp_shoplist');"><?php echo $order_list[0]["name"]; ?></a></li>
            <?php for ($i = 1; $i < count($order_list); $i++) { ?>
                <?php $each = $order_list[$i]; ?>
                <li><a id="order_<?php echo $each["type"]; ?>" href="" onclick="return dp_set_shop_type(this, 'order_type', 'dp_shoplist');"><?php echo $each["name"]; ?></a></li>
            <?php } ?>
        </ul>
    </div><!--------End dn_box-->
</div>
<div style="display: none">
    <span id="kws"><?php echo $kws; ?></span>
    <span id="near_type"><?php echo $near_type; ?></span>
    <span id="food_class_type"><?php echo $food_class_type; ?></span>
    <span id="order_type"><?php echo $order_type; ?></span>
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
    $(document).ready(document.cookie="current_page=1");
    $(document).ready(document.cookie="distance_null_num=0");
    $(document).ready(function () {
        var shopname_search = parseInt("<?php echo $shopname_search; ?>");
        if (shopname_search == 1) {
            var kws = "<?php echo $kws; ?>";
        }

        var queryString;

        var near_type = document.getElementById("near_type").innerHTML;
        var food_class_type = document.getElementById("food_class_type").innerHTML;
        var order_type = document.getElementById("order_type").innerHTML;

        if (shopname_search == 1)
            queryString = "near_type="+near_type+"&food_class_type="+food_class_type+"&order_type="+order_type+"&kws="+kws+"&fuzzy=1";
        else
            queryString = "near_type="+near_type+"&food_class_type="+food_class_type+"&order_type="+order_type;

        sec_category_auto_dp_search(queryString);
    });

</script>
<?php
include_once("foot.php");
mysql_close($link);
?>
