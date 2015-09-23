<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

$link = db_connect_web();
$article_num = 20;
$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

// detail start
$article_list = getImmigrationVisa($link);
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

$pagepos = ($page-1)*$article_num;
$pagetotal = count($article_list);
if (($pagepos+$article_num) > $pagetotal){
    $end_flag = 1;
} else {
    $end_flag = 0;
}
setcookie("end_flag", (string)$end_flag, 0, "/");

$str_article = '<ul class="hot_recommend">';
$each = array();
for ($i=$pagepos; $i < ($pagepos+$pagetotal) and $i < $pagetotal; $i++) {
    $each = $article_list[$i];
    if (empty($each["newType"]))
        $each["newType"] = "δ֪";
    $str_article .= '<li class="hot_li hot_list_wrap im_conter_box border_bottom">';
    $str_article .= '<div class="content_list nopic padding10 ">';
    $str_article .= '<h4 class="singleline"><a href="'.$each["href"].'">'.$each["title"].'</a></h4>';
    $str_article .= '<p class="commen_p">';
    $str_article .= '<span class="im_l noborder pl">'.$each["author"].'</span>';
    $str_article .= '<span class="im_l noborder pl">'.$each["BoardsName"].'</span>';
    $str_article .= '<span class="fr">'.strftime("%G-%m-%d",$each["posttime"]).'</span>';
//    $str_article .= '<span class="fr">'.$each["posttime"].'</span>';
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
