<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

$link = db_connect_web();

function getSpecialColumnArticle($link) {
    $xmlfile = BBS_HOME.'/xml/lawyer_1.xml';
    $result = read_xmlfile_content_web($xmlfile, 3);
    $ret = array();
    $i = 0;
    foreach ($result as $i=>$each) {
        if ($i == 20)
            break;

        $title = urldecode($each["title"]);
        $postTime = str_replace("/", "-", $each["date"]);
        $href = url_generate(4, array(
                "action"=>"/mobile/forum/i_article.php",
                "args"=>array("reqtype"=>"column", "board"=>$each["board"], "groupid"=>$each["groupid"])
            ));
        $boardname = $each["board"];
        $author = getLawyerName($link, $each["author"]);
        $author_tmp = iconv("UTF-8", "GBK//IGNORE", $author);
        if ($author_tmp)
            $author = $author_tmp;

        $ret[] = array(
            "title" => $title,
            "postTime" => $postTime,
            "href" => $href,
            "boardname" => $boardname,
            "author" => $author
        );
    }

    return $ret;
}

// carouselfigure start
$str_img = '<div class="carouselfigure">';
$str_img .= '<div class="club_list_wrap"><div class="club_div">';
$str_img_dot = '';
$t_data = array();
$t_data = getMainPageLawyers($link);
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
    $str_img .= '<a href="'.$each["href"].'"><img class="club_img" src="'.$each["img"].'" alt="club_img" /></a>';
    $str_img .= '<p>'.$each["name"].'</p>';
    $str_img .= '</div>';
}

if ($in_list_flag) {
    $str_img .= '</li></ul>';
    $str_img_dot .= '</div>';
}
$str_img .= $str_img_dot;

$str_img .= '</div></div></div>';

// carouselfigure end

// detail start
$article_list = getSpecialColumnArticle($link);
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
        "content" => "2015��9��21��19:35�г���26�����һ������,���ݸ�������Խ��ɽ������ӭս���õĹ��ݺ�󣬺�����ȶ�һ������������ɸ����ء������Ľ�����תȡʤ����������...",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "10086",
        "reply_num" => "10010",
        "author" => "���ű༭��",
        "postTime" => "09-22"
    );
}

$str_article = '<ul class="hot_recommend">';
$each = array();
foreach ($article_list as $each) {
    $str_article .= '<li class="hot_li hot_list_wrap im_conter_box border_top">';
    $str_article .= '<div class="content_list nopic padding10 ">';
    $str_article .= '<h4><a href="'.$each["href"].'">'.$each["title"].'</a></h4>';
    $str_article .= '<p class="commen_p padding-bottom">';
    $str_article .= '<span class="commen_margin im_l">'.$each["author"].'��ʦ</span>';
    $str_article .= '<span class="commen_right ">'.$each["postTime"].'</span>';
    $str_article .= '</p></div></li>';
}
$str_article .= '</ul>';

//detail end

$str_img = mb_convert_encoding($str_img, "UTF-8", "GBK");
$str_article = mb_convert_encoding($str_article, "UTF-8", "GBK");
$all_arr["carouselfigure"] = $str_img;
$all_arr["detail"] = $str_article;

echo json_encode($all_arr);

mysql_close($link);
?>
