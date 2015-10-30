<?php
session_start();
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();
$pos = array("lat"=>$_SESSION["lat"], "lon"=>$_SESSION["lon"], "locate_flag"=>$_SESSION["locate_flag"]);

$user_num_id = $_POST["user_num_id"];
$type = $_POST["type"];
$page = $_POST["page"];
$num = $_POST["num"];
$str_content = "";

log2file($type);
if ($type == "shop") {
    $t_data = getMyDpCollectShop($link, $user_num_id, $pos, $page, $num);
    log2file($t_data);
    foreach ($t_data as $each) {
        $str_content .= '<li class="conter2_list border_bottom">';
        $str_content .= '<a class="conter2_list_conter" href="'.$each["href"].'">';
        $str_content .= '<img class="shop_topimg" src="'.$each["img"].'" alt="shopimg"/>';
        $str_content .= '<div><h4>'.$each["cnName"].'</h4><p class="conter2_pt">';
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

} elseif ($type == "dp") {
    $t_data = getMyDpComment($link, $user_num_id, $page, $num);
    foreach ($t_data as $each) {
        $str_content .= '<li class="member_list border_bottom">';
        $str_content .= '<h4>'.$each["user_name"].'</h4>';
        $str_content .= '<p class="member_pt">';
        for ($i = 0; $i < 5; $i++) {
            if ($i < $each["avg_score"])
                $str_content .= '<img src="img/redstar.png" alt="redstar.png"/>';
            else
                $str_content .= '<img src="img/graystar.png" alt="redstar.png"/>';
        }
        $str_content .= '<span>$'.$each["consume"].'/ÈË</span></p>';
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
} elseif ($type == "pic") {
    $t_data = getMyDpAlbum($link, $user_num_id, $page, $num);
    foreach ($t_data as $each) {
        $str_content .= '<li><a href="'.$each["href"].'" ><img src="'.$each["img"].'"/></a>';
        $str_content .= '<div><p>'.$each["cnName"].'</p>';
        $str_content .= '<p>'.$each["count"].'</p>';
        $str_content .= '</div></li>';
    }
}

$content = mb_convert_encoding($str_content, "UTF-8", "GBK");
$all_arr["result"] = 0;
$all_arr["content"] = $content;

echo json_encode($all_arr);

mysql_close($link);
?>