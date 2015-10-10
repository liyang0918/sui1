<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
include_once(dirname(__FILE__)."/../../../yimin/mitbbs_lawyer_funcs.php");

$link = db_connect_web();
// extra 格式为 value1|value2|value3...
$arr = getExtraValue($_GET["extra"]);

// mode为0表示按姓名选择，mode为1表示按城市选择
$mode = $arr["mode"];
$city = $arr["city"];

if ($mode != "0" and $mode != "1") {
    $mode = "0";
    $city = "all";
}

if (empty($city))
    $city = "all";

// detail start
if ($mode == "0") {
    $t_data = getLawyerGroupByName($link, $city);

    $str_lawyer = '
    <div class="chose-area">
        <a href="" id="1" onclick="sec_select(this)">
            按地区选择
            <img src="img/btn_down.png" alt="btn_down.png"/>
        </a>
    </div>';

    $str_lawyer .= '<div class="chose_list_box nomargin_top">';
    foreach ($t_data as $group=>$layers) {
        if (empty($layers))
            continue;

        $str_lawyer .= '<dl><dt>'.$group.'</dt>';
        $each = array();
        foreach ($layers as $each) {
            $str_lawyer .= '<dd class="border_bottom">';
            $str_lawyer .= '<a href="'.$each["href"].'">';
            $str_lawyer .= '<img src="'.$each["img"].'" alt="lawyer"/>';
            $str_lawyer .= '<span>'.$each["lawyer_name"].'律师</span>';
            if ($each["identity_flag"] == "S")
                $str_lawyer .= '<em>认证律师</em>';
            $str_lawyer .= '</a></dd>';
        }
        $str_lawyer .= '</dl>';
    }
    $str_lawyer .= '</div>';
} else {
    $t_data = getLawyerGroupByArea($link);
    $str_lawyer = '
    <div class="chose-area">
        <a href="" id="0|'.$city.'" onclick="sec_select(this)">
            <img src="img/btn_left.png" alt="bth_left.png"/>
        &nbsp;&nbsp;选择城市
        </a>
    </div>';

    $str_lawyer .= '<div class="chose_list_box nomargin_top">';
    $str_lawyer .= '
<dl class="chose_dl">
    <dt>#</dt>
    <dd class="border_bottom area_dd"><a id="0|all" href="" onclick="sec_select(this);">全部</a></dd>
</dl>';

    foreach ($t_data as $group=>$lawyers) {
        if (empty($lawyers))
            continue;

        $str_lawyer .= '<dl class="chose_dl">';
        $str_lawyer .= '<dt>'.$group.'</dt>';
        $each = array();
        foreach ($lawyers as $each) {
            $str_lawyer .= '<dd class="border_bottom area_dd"><a id="0|'.$each["city"].'" href="" onclick="sec_select(this);">'.$each["city"].'</a></dd>';
        }
        $str_lawyer .= '</dl>';
    }
    $str_lawyer .= '</div>';
}
//detail end

$str_lawyer = mb_convert_encoding($str_lawyer, "UTF-8", "GBK");
$all_arr["detail"] = $str_lawyer;
echo json_encode($all_arr);
mysql_close($link);
?>
