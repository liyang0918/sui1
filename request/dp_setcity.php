<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

$link = db_connect_web();

// detail start
$t_data = getDpCityList($link);
$t_data = getLawyerGroupByArea($link);
$str_city = '
    <div class="chose-area">
        <a href="" onclick="go_last_page()">
            <img src="img/btn_left.png" alt="bth_left.png"/>
        &nbsp;&nbsp;选择城市
        </a>
    </div>';

$str_city .= '<div class="chose_list_box nomargin_top">';
$str_city .= '
<dl class="chose_dl">
    <dt>#</dt>
    <dd class="border_bottom area_dd"><a id="0|all" href="" onclick="sec_select(this);">全部</a></dd>
</dl>';

foreach ($t_data as $group=>$lawyers) {
    if (empty($lawyers))
        continue;
    if ($group == "#")
        $group = "其他";

    $str_city .= '<dl class="chose_dl">';
    $str_city .= '<dt>'.$group.'</dt>';
    $each = array();
    foreach ($lawyers as $each) {
        $str_city .= '<dd class="border_bottom area_dd"><a id="0|'.$each["city_concise"].'" href="" onclick="sec_select(this);">'.$each["city"].'</a></dd>';
    }
    $str_city .= '</dl>';
}
$str_city .= '</div>';
//detail end

$str_img = mb_convert_encoding($str_img, "UTF-8", "GBK");
$str_article = mb_convert_encoding($str_article, "UTF-8", "GBK");
$all_arr["carouselfigure"] = $str_img;
$all_arr["detail"] = $str_article;
if ($page == 1)
    echo json_encode($all_arr);
else
    echo json_encode(array("article"=>$str_article));
mysql_close($link);
?>
