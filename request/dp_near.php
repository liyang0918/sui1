<?php
session_start();
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$city = "all";
$city = $_GET["city"];

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

if (isset($method_array["kws"])) {
    $cond["search"]["name"] = iconv("UTF-8", "GBK//IGNORE", $method_array["kws"]);
    // 模糊查询默认开启
    if (isset($method_array["fuzzy"]) and $method_array["fuzzy"] == 0)
        $cond["search"]["fuzzy"] = $method_array["fuzzy"] = false;
    else
        $cond["search"]["fuzzy"] = true;
}


//log2file($cond["near_type"].",".$cond["food_class_type"].",".$cond["order_type"].",");

$end_flag = 1;

$pos = array("lat"=>$_SESSION["lat"], "lon"=>$_SESSION["lon"], "locate_flag"=>$_SESSION["locate_flag"]);
list($end_flag, $t_data) = getShopByCondition($link, $city, $cond, $pos, $page, $num);

setcookie("end_flag", (string)$end_flag, 0, "/");

$str_content = '<ul class="dn_conter2">';
foreach ($t_data as $each) {
    $str_content .= '<li class="conter2_list border_bottom">';
    $str_content .= '<a class="conter2_list_conter" href="'.$each["href"].'">';
    $str_content .= '<img class="shop_topimg" src="'.$each["img"].'" alt="shopimg"/>';
    $str_content .= '<div><h4>'.$each["cnName"].'</h4><p class="conter2_pt pd_5">';
    for ($i = 0; $i < 5; $i++) {
        if ($i <= $each["avg_score"]-1)
            $str_content .= '<img src="img/redstar.png" alt="redstar.png"/>';
        else
            $str_content .= '<img src="img/graystar.png" alt="redstar.png"/>';
    }
    $str_content .= '<span>人均：$'.round($each["avg_pay"], 1).'</span></p>';
    $str_content .= '<p class="conter2_pd"><span>'.foodType2String($each["type_set"]).'</span><em>'.$each["distance_str"].'</em></p>';
    $str_content .= '</div></a></li>';
}
$str_content .= '</ul>';


$str_content = mb_convert_encoding($str_content, "UTF-8", "GBK");
$all_arr["detail"] = $str_content;

echo json_encode($all_arr);
mysql_close($link);
?>
