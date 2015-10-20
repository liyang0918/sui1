<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();
$all_arr = array();

$method_array = $_POST;

$page = intval($method_array["page"]);
if (empty($page))
    $page = 1;

$num = intval($method_array["num"]);
if (empty($num))
    $num = 10;

if (isset($method_array["shop_id"]))
    $shop_id = $method_array["shop_id"];
else {
    $shop_id = -1;
}

$str_content = "";
if ($shop_id > 0) {
    $t_data = getShopComment($link, $shop_id, $page, $num);
    foreach ($t_data as $each) {
        $str_content .= '<li class="member_list border_bottom">';
        $str_content .= '<h4>'.$each["user_name"].'</h4>';
        $str_content .= '<p class="member_pt">';
        for ($i = 0; $i < 5; $i++) {
            if ($i < $each["avg_score"]-1)
                $str_content .= '<img src="img/redstar.png" alt="redstar.png"/>';
            else
                $str_content .= '<img src="img/graystar.png" alt="redstar.png"/>';
        }
        $str_content .= '<span>$'.$each["consume"].'/人</span></p>';
        $str_content .= '<p class="member_info">'.$each["content"].'</p>';
        if (isset($each["img_list"])) {
            $str_content .= '<div class="member_img">';
            foreach ($each["img_list"] as $img) {
                $str_content .= '<img src="'.$img.'" alt="img"/>';
            }
            $str_content .= '</div>';
        }

        $str_content .= '</li>';
    }
}

// json_encode()转换的字符串若含的中文,必须为UTF-8编码
$all_arr["content"] = iconv('GBK', 'UTF-8', $str_content);
//var_dump($all_arr);
echo json_encode($all_arr);
mysql_close($link);
?>
