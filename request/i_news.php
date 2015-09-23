<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

$link = db_connect_web();
$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

// detail start
list($article_list, $end_flag) = getNews($link, $page, "immigration");
setcookie("end_flag", (string)$end_flag, 0, "/");
$_COOKIE["end_flag"] = "0";
if (count($article_list) == 0) {
    $article_list[0] = array(
        "href" => "one_group_club.php?type=index&club=Prepaid&group=138",
        "title" => "��������: ϰ��ƽ�����̷�������ͼ",
        "content" => "���ܸ����Ե�������ˣ�������������£�������ҪҲ��Ӧ��ΪΨһ�ģ������䲻��Ҫ�κ� WHERE �Ӿ䣬��Ϊ����ϣ���������е��С�",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "9988",
        "reply_num" => "998",
        "boardname" => "С���޵�",
        "postTime" => "09-18"
    );

    $article_list[1] = array(
        "href" => "one_group_club.php?type=index&club=Prepaid&group=183",
        "title" => "��������: ����89���Ӿ�ɱ���򣬹��ݺ��ͳ�2-1��ת���ݸ���",
        "content" => "2015��9��21��19:35�г���26�����һ������,���ݸ�������Խ��ɽ������ӭս���õĹ��ݺ�󣬺�����ȶ�һ������������ɸ����ء������Ľ�����תȡʤ����������...",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "10086",
        "reply_num" => "10010",
        "boardname" => "���ű༭��",
        "postTime" => "09-22"
    );
}

$str_article = '<ul class="hot_recommend">';
$each = array();
foreach ($article_list as $each) {
    if (empty($each["newType"]))
        $each["newType"] = "δ֪";
    $str_article .= '<li class="hot_li hot_list_wrap im_conter_box border_bottom">';
    $str_article .= '<div class="content_list nopic padding10 ">';
    $str_article .= '<h4 class="singleline"><a href="'.$each["href"].'">'.$each["title"].'</a></h4>';
    $str_article .= '<p class="commen_p">';
    $str_article .= '<span class="im_l noborder pl">'.$each["newType"].'����</span>';
    $str_article .= '<span class="fr">'.strftime("%G-%m-%d",$each["postTime"]).'</span>';
    $str_article .= '</p></div></li>';
}
$str_article .= '</ul>';

//detail end

$str_article = mb_convert_encoding($str_article, "UTF-8", "GBK");
$all_arr["detail"] = $str_article;
if ($page == 1)
    echo json_encode($all_arr);
else
    echo json_encode(array("article"=>$str_article));
mysql_close($link);
?>
