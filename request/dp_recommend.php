<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$city = "all";
list(,$city) = getExtraValue($_GET["extra"]);
$t_data = getDpRecommendShop($link, $city);

// detail start
/*
 <ul class="dp_group2">
            <li class="dp_list2 margin_r">
                <div class="dp_list2_up"><img src="img/1.jpg" alt="1.jpg"/></div>
                <div class="dp_list2_middle border_bottom">
                    <p class="list2_p_up"><strong class="margin_r">�����ǵ�������</strong><em>�˾�$992/��</em></p>
                    <p class="list2_p_down"><span class="margin_r">�����ǵ�ַ</span><span>33001133</span></p>
                </div>
                <p class="dp-list2_down">
                    �������Ƽ����ɶ������������ʾ���г����������ز���ʾ
                    �������Ƽ����ɶ������������ʾ���г����������ز���ʾ
                    �������Ƽ����ɶ������������ʾ���г����������ز���ʾ
                </p>
            </li>


 */

$str_content = '<ul class="dp_group2">';
foreach ($t_data as $each) {
    $str_content .= '<li class="dp_list2 margin_r">';
    $str_content .= '<div class="dp_list2_up"><img src="'.$each["img"].'" alt="1.jpg"/></div>';
    $str_content .= '<div class="dp_list2_middle border_bottom">';
    $str_content .= '<p class="list2_p_up"><strong class="margin_r">'.$each["shop_name"].'</strong><em>�˾�: $'.round($each["avg_pay"],2).'/��</em></p>';
    $str_content .= '<p class="list2_p_down"><span class="margin_r address">'.$each["addr"].'</span><span>'.$each["telephone"].'</span></p></div>';
    $str_content .= '<p class="dp-list2_down">';
    $str_content .= $each["add_reason"]."---{$each["shop_id"]}";
    $str_content .= '</p></li>';
}
$str_content .= '</ul>';
// detail end


$str_content = mb_convert_encoding($str_content, "UTF-8", "GBK");
$all_arr["detail"] = $str_content;

echo json_encode($all_arr);
mysql_close($link);
?>
