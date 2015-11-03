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
        "BoardsCnName" => "测试用例: 习近平今启程访问西雅图",
        "content" => "尽管该属性的名称如此，但在这种情况下，它不需要也不应该为唯一的，这个语句不需要任何 WHERE 子句，因为我们希望检索所有的行。",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "9988",
        "reply_num" => "998",
        "author" => "小白无敌",
        "postTime" => "09-18"
    );

    $article_list[1] = array(
        "href" => "one_group_club.php?type=index&club=Prepaid&group=183",
        "BoardsCnName" => "测试用例: 邹正89分钟绝杀进球，广州恒大客场2-1逆转广州富力",
        "content" => "2015年9月21日19:35中超第26轮最后一场比赛,广州富力坐镇越秀山体育场迎战来访的广州恒大，恒大在先丢一球情况下最终由高拉特、邹正的进球逆转取胜，富力方面...",
        "img" => "/mobile/forum/img/club_simg.png",
        "read_num" => "10086",
        "reply_num" => "10010",
        "author" => "新闻编辑部",
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
    $str_article .= '<span class="commen_margin im_l">'.$each["author"].'律师</span>';
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
