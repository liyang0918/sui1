<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();
$type = substr($_GET["type"], 5);
$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

// carouselfigure start
$str_carouselfigure = "";
$ret = getTopNewsByType($link, $type);
$str_carouselfigure = '<div id="carouselfigure" class="newsIndex_showBox" style="display: none">';
if ($ret) {
    $str_carouselfigure = '<div id="carouselfigure" class="newsIndex_showBox">';
    $str_carouselfigure .= '<a href="'.$ret["href"].'"><img src="'.$ret["imgURL"].'" /></a>';
    $str_carouselfigure .= '<p class="newsIndex_info">'.$ret["title"].'</p>';
}
$str_carouselfigure .= '</div>';
// carouselfigure end

list($head_line_news, $end_flag) = getNews($link, $page, $type);
setcookie("end_flag", (string)$end_flag, 0, "/");
$str_article = '<div class="news_list_content" id="detail">';
$str_article .= '<ul class="new_list_content_listbox">';
foreach ($head_line_news as $each) {
    if ($each["imgNum"] <= 1) {
        if ($each["imgNum"] == 1) {
            $str_article .= '<li class="news_ltems news_list_lione">';
            $str_article .= '<img src="'.$each["imgList"][0].'" alt="img">';
        } else {
            $str_article .= '<li class="news_ltems news_list_lione news_list_lione_nopic">';
        }

        $str_article .= '<div class="lione_r_box">';
        $str_article .= '<h3><a href="'.$each["href"].'" onclick="add_read_num(this)">'.$each["title"].'</a></h3>';
        $str_article .= '<a href="'.$each["href"].'" onclick="add_read_num(this)"></a>';
        $str_article .= '</div>';
        $str_article .= '<span class="critize right_b">'.$each["total_reply"].'����</span>';
    } else {
        $str_article .= '<li class="news_ltems news_list_litwo">';
        $str_article .= '<a href="'.$each["href"].'" onclick="add_read_num(this)"><h3>'.$each["title"].'</h3></a>';
        $str_article .= '<ul class="litwo_box">';
        for ($i = 0; $i < $each["imgNum"]-1; $i++)
            $str_article .= '<li><a href="'.$each["href"].'" onclick="add_read_num(this)"><img src="'.$each["imgList"][$i].'" alt="img"></a></li>';

        $str_article .= '<li class="margin_right"><a href="'.$each["href"].'" onclick="add_read_num(this)"><img src="'.$each["imgList"][$i].'" alt=""></a></li>';
        $str_article .= '</ul>';
        $str_article .= '<span class="critize right_t">'.$each["total_reply"].'����</span>';
    }

    $str_article .= '</li>';
}


$str_article .= "</ul></div>";
//detail end
$str_carouselfigure = mb_convert_encoding($str_carouselfigure, "UTF-8", "GBK");
$str_article = mb_convert_encoding($str_article, "UTF-8", "GBK");
$all_arr["carouselfigure"] = $str_carouselfigure;
$all_arr["detail"] = $str_article;
if ($page == 1)
    echo json_encode($all_arr);
else
    echo json_encode(array("article"=>$str_article));
mysql_close($link);
?>
