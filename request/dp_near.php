<?php
session_start();
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$city = "all";
list(,$city) = getExtraValue($_GET["extra"]);

$method_array = $_POST;

$page = 1;
if (isset($method_array["page"]))
    $page = intval($method_array["page"]);
$num = 20;
if (isset($method_array["num"]))
    $num = intval($method_array["num"]);

$cond = array();
// 距离选择
$cond["near_type"] = 0;
// 店铺类型
$cond["food_class_type"] = "all";
// 排序类型
$cond["order_type"] = 0;

if (isset($method_array["near_type"]))
    $cond["near_type"] = $method_array["near_type"];

if (isset($method_array["food_class_type"]))
    $cond["food_class_type"] = $method_array["food_class_type"];

if (isset($method_array["order_type"]))
    $cond["order_type"] = $method_array["order_type"];

log2file($_SESSION["lon"].",".$_SESSION["lat"]);

$end_flag = 1;
if ($_SESSION["locate_flag"] == false) {
    $t_data = array();
} else {
    $pos = array("lat"=>$_SESSION["lat"], "lon"=>$_SESSION["lon"]);
    list($end_flag, $t_data) = getShopByCondition($link, $city, $cond, $pos, $page, $num);
}

// detail end


$str_content = mb_convert_encoding($str_content, "UTF-8", "GBK");
$all_arr["detail"] = $str_content;

echo json_encode($all_arr);
mysql_close($link);
?>