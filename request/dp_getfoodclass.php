<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");


$str_food_class = '
<div class="ds_box border_bottom">
    <a onclick="go_back_dp_search();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    按分类查找
</div><!--<End ds_box-->';
$str_food_class .= '<ul class="fenlei_group">';
// $food_class_list: func.php
for ($i = 1; $i < count($food_class_list); $i++) {
    $str_food_class .= '<li><a href="shop_list.php?food_class='.$food_class_list[$i]["type"].'">'.$food_class_list[$i]["name"].'</a></li>';
}
$str_food_class .= '</ul>';

//detail end

$str_food_class = mb_convert_encoding($str_food_class, "UTF-8", "GBK");
$all_arr["detail"] = $str_food_class;
echo json_encode($all_arr);
?>
