<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();
$type = "sport";
$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

// carouselfigure start
$str_img = '<div class="carouselfigure">';
$str_img .= '<p class="club_title"><strong>热门俱乐部</strong><a href="club_classify.php"><span>全部俱乐部列表</span></a></p>';
$str_img .= '<div class="club_list_wrap"><div class="club_div">';
$str_img_dot = '';
$t_data = array();
$t_data = getHotClubs($link);
$each = array();
$in_list_flag = 0;
foreach ($t_data as $i=>$each) {
    if ($i & (4-1) == 0) // ($i % 4)
        if ($in_list_flag) {
            $str_img_dot .= '<span></span>';
            $str_img .= '</li><li class="club_list_li">';
        }
        else {
            $str_img_dot .= '<div class="club_dot"><span class="club_dot"></span>';
            $str_img .= '<ul><li class="club_list_li">';
        }

    $in_list_flag = 1;
    $str_img .= '<div class="club_item">';
    $str_img .= '<a href="'.$each["href"].'"><img class="club_img" src="'.$each["img"].'" alt="club_img" /></a>';
    $str_img .= '<p>'.$each["name"].'</p>';
}

if ($in_list_flag) {
    $str_img .= '</li></ul>';
    $str_img_dot .= '</div>';
}
$str_img .= $str_img_dot;

$str_img .= '</div></div></div>';

// carouselfigure end

list($head_line_news, $end_flag) = getNewsDataByType($link, $page, $type);
setcookie("end_flag", (string)$end_flag, 0, "/");
$str_article = '<div class="news_list_content" id="detail">';
$str_article .= '<ul class="new_list_content_listbox">';
$each = array();
foreach ($head_line_news as $each) {
    if ($each["imgNum"] <= 1) {
        $str_article .= '<li class="news_ltems news_list_lione">';
        if ($each["imgNum"] == 1)
            $str_article .= '<img src="'.$each["imgList"][0].'" alt="img">';
        $str_article .= '<div class="lione_r_box">';
        $str_article .= '<a href="'.$each["href"].'"><h3>'.$each["title"].'</h3></a>';
        $str_article .= '<a href="'.$each["href"].'"><p>'.$each["notes"].'</p></a>';
        $str_article .= '</div>';
        $str_article .= '<span class="critize right_b">'.$each["total_reply"].'评论</span>';
    } else {
        $str_article .= '<li class="news_ltems news_list_litwo">';
        $str_article .= '<a href="'.$each["href"].'"><h3>'.$each["title"].'</h3></a>';
        $str_article .= '<ul class="litwo_box">';
        for ($i = 0; $i < $each["imgNum"]-1; $i++)
            $str_article .= '<li><a href="'.$each["href"].'"><img src="'.$each["imgList"][$i].'" alt="img"></a></li>';

        $str_article .= '<li class="margin_right"><a href="'.$each["href"].'"><img src="'.$each["imgList"][$i].'" alt=""></a></li>';
        $str_article .= '</ul>';
        $str_article .= '<span class="critize right_t">'.$each["notes"].'评论</span>';
    }

    $str_article .= '</li>';
}


$str_article .= "</ul></div>";
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
