<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

$link = db_connect_web();

$sec_category = $_COOKIE["sec_category"];
// detail start
$t_data = getDpCityList($link);
$str_city = '<div class="chose_list_box nomargin_top">';
$str_city .= '
<dl class="chose_dl">
    <dt>#</dt>
    <dd class="border_bottom area_dd"><a id="'.$sec_category.'|all" href="" onclick="return set_city(this);">全部</a></dd>
</dl>';

foreach ($t_data as $group=>$citys) {
    if (empty($citys))
        continue;
    if ($group == "#")
        $group = "其他";

    $str_city .= '<dl class="chose_dl">';
    $str_city .= '<dt>'.$group.'</dt>';
    $each = array();
    foreach ($citys as $each) {
        $str_city .= '<dd class="border_bottom area_dd"><a id="'.$sec_category.'|'.$each["city_concise"].'" href="" onclick="return set_city(this);">'.$each["city_name"].'</a></dd>';
    }
    $str_city .= '</dl>';
}
$str_city .= '</div>';
//detail end

$str_img = mb_convert_encoding($str_img, "UTF-8", "GBK");
$str_city = mb_convert_encoding($str_city, "UTF-8", "GBK");
$all_arr["detail"] = $str_city;
echo json_encode($all_arr);
mysql_close($link);
?>
