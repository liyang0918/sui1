<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$city = "all";
list(,$city) = getExtraValue($_GET["extra"]);
$t_data = getDpRecommendShops($link, $city);

// detail start
$str_content = '<ul class="dp_group2">';
foreach ($t_data as $each) {
    $str_content .= '<li class="dp_list2 margin_r">';
    $str_content .= '<div class="dp_list2_up"><a href="'.$each["href"].'"><img src="'.$each["img"].'" alt="1.jpg"/></a></div>';
    $str_content .= '<div class="dp_list2_middle border_bottom">';
    $str_content .= '<p class="list2_p_up"><a href="'.$each["href"].'"><strong class="margin_r">'.$each["shop_name"].'</strong></a><em>ÈË¾ù: $'.round($each["avg_pay"],2).'/ÈË</em></p>';
    $str_content .= '<p class="list2_p_down"><span class="margin_r address">'.$each["addr"].'</span><span class="phone">'.$each["telephone"].'</span></p></div>';
    $str_content .= '<p class="dp-list2_down">';
    $str_content .= $each["add_reason"];
    $str_content .= '</p></li>';
}
$str_content .= '</ul>';
// detail end


$str_content = mb_convert_encoding($str_content, "UTF-8", "GBK");
$all_arr["detail"] = $str_content;

echo json_encode($all_arr);
mysql_close($link);
?>
