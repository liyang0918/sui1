<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

$link = db_connect_web();
$type = substr($_GET["type"], 5);
$groupid = $club_class_list[$type]["id"];
$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

// carouselfigure start
$str_img = '<div class="carouselfigure">';
$str_img .= '<p class="club_title"><strong>���ž��ֲ�</strong><a href="club_classify.php"><span>ȫ�����ֲ��б�</span></a></p>';
$str_img .= '<div class="club_list_wrap"><div class="club_div">';
$str_img_dot = '';
$t_data = array();
$t_data = getHostClub($link, $page, $groupid);
$each = array();
$in_list_flag = 0;
foreach ($t_data as $i=>$each) {
    if (($i & (4-1)) == 0) // ($i % 4)
        if ($in_list_flag) {
            $str_img_dot .= '<span></span>';
            $str_img .= '</li><li class="club_list_li">';
        } else {
            $str_img_dot .= '<div class="club_dot"><span class="act"></span>';
            $str_img .= '<ul><li class="club_list_li">';
        }

    $in_list_flag = 1;
    $str_img .= '<div class="club_item">';
    $str_img .= '<a href="'.$each["href"].'" onclick="add_read_num(this)"><img class="club_img" src="'.$each["clubimg"].'" alt="club_img" /></a>';
    $str_img .= '<p>'.$each["cnName"].'</p>';
    $str_img .= '</div>';
}

if ($in_list_flag) {
    $str_img .= '</li></ul>';
    $str_img_dot .= '</div>';
}
$str_img .= $str_img_dot;

$str_img .= '</div></div></div>';

// carouselfigure end

list($article_list, $end_flag) = getRecommendClubArticle($link, $page, $groupid);
if (count($article_list) == 0) {
    $article_list[0] = array(
        "href" => "one_group_club.php?type=index&club=Prepaid&group=138",
        "BoardsCnName" => "��������: ϰ��ƽ�����̷�������ͼ",
        "content" => "���ܸ����Ե�������ˣ�������������£�������ҪҲ��Ӧ��ΪΨһ�ģ������䲻��Ҫ�κ� WHERE �Ӿ䣬��Ϊ����ϣ���������е��С�",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "9988",
        "reply_num" => "998",
        "author" => "С���޵�",
        "postTime" => "09-18"
    );

    $article_list[1] = array(
        "href" => "one_group_club.php?type=index&club=Prepaid&group=183",
        "BoardsCnName" => "��������: ����89���Ӿ�ɱ���򣬹��ݺ��ͳ�2-1��ת���ݸ���",
        "content" => "2015��9��21��19:35���ݸ�������Խ��ɽ������ӭս���õĹ��ݺ�󣬺�����ȶ�һ������������ɸ����ء������Ľ�����תȡʤ����������...",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "10086",
        "reply_num" => "10010",
        "author" => "���ű༭��",
        "postTime" => "09-22"
    );
}

setcookie("end_flag", (string)$end_flag, 0, "/");
$str_article = '<ul class="club_cont">';
$each = array();
foreach ($article_list as $each) {
    $str_article .= '<li>';
    $str_article .= '<a href="'.$each["href"].'" onclick="add_read_num(this)">';
    $str_article .= '<h3 class="club_cont_top">'.$each["BoardsCnName"].'</h3>';
    $str_article .= '<div class="club_cont_middle">';
    $str_article .= '<p>'.$each["content"].'</p>';
    if (isset($each["img"]))
        $str_article .= '<div><img src="'.$each["img"].'" alt="club_img"/ ></div>';
    $str_article .= '</div>';

    $str_article .= '<div class="club_cont_bottom">';
    $str_article .= '<p class="cont_b_l">';
    $str_article .= '<span class="margin_r"><img src="img/redeye.png" alt="redeye.png" />'.$each["read_num"].'</span>';
    $str_article .= '<span class="club_l"><img class="club_email" src="img/redemail.png" alt="redemail.png" />'.$each["reply_num"].'</span>';
    $str_article .= '</p>';
    $str_article .= '<p class="cont_b_r">';
    $str_article .= '<span class="margin_r">'.$each["author"].'</span>';
    $str_article .= '<span class="club_r">'.$each["postTime"].'</span>';
    $str_article .= '</p></div></a></li>';
}
$str_article .= '</ul>';

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
