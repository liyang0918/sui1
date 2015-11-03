<?php
session_start();
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$city = $_GET["city"];

$reason = $_POST["reason"];
if (empty($reason)) {
    $reason = "hot";
}

$pos["lat"] = $_SESSION["lat"];
$pos["lng"] = $_SESSION["lon"];
$pos["locate_flag"] = $_SESSION["locate_flag"];

$t_data = getShopTop10($link, $reason, $city, $pos);

$str_content = '<ul class="dn_conter2">';
foreach ($t_data as $each) {
    $str_content .= '<li class="conter2_list border_bottom">';
    $str_content .= '<a class="conter2_list_conter" href="'.$each["href"].'">';
    $str_content .= '<img class="shop_topimg" src="'.$each["img"].'" alt="shopimg"/>';
    $str_content .= '<div><h4>'.$each["cnName"].'</h4><p class="conter2_pt" style="padding:5px 0;">';
    for ($i = 0; $i < 5; $i++) {
        if ($i < $each["avg_score"]-1)
            $str_content .= '<img src="img/redstar.png" alt="redstar.png"/>';
        else
            $str_content .= '<img src="img/graystar.png" alt="redstar.png"/>';
    }
    $str_content .= '<span>ÈË¾ù£º$'.round($each["avg_pay"], 1).'</span></p>';
    $str_content .= '<p class="conter2_pd"><span>'.foodType2String($each["type_set"]).'</span><em>'.$each["distance_str"].'</em></p>';
    $str_content .= '</div></a></li>';
}
$str_content .= '</ul>';


$str_content = mb_convert_encoding($str_content, "UTF-8", "GBK");
$all_arr["detail"] = $str_content;

echo json_encode($all_arr);

mysql_close($link);
?>
