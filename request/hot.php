<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$all_arr=array();
$type="hot";
$str_content = '<div id="detail"><ul class="hot_recommend">';

/* ʮ�����Ż��� */
$t_data = array();
$t_data = getHotSubjects($link);

$str_content .= '<li class="hot_li">';
$str_content .= '<h3 class="hot_title border_bottom">ʮ�����Ż���<span class="hot_trigle"></span></h3>';
$str_content .= '<div class="hot_list_wrap border_bottom">';

$topic_num = count($t_data);
if ($topic_num > 0) {
    if ($topic_num > 1) {
        for ($i = 0; $i < $topic_num-1; $i++) {
            $str_content .= '<div class="content_list nopic padding10">';
            $str_content .= '<h4><a hre m_r_10f="'.$t_data[$i]["href"].'" onclick="add_read_num(this)">'.$t_data[$i]["title"].'</a></h4>';
            $str_content .= '<p class="commen_p padding-bottom border_bottom">';
            $str_content .= '<span class="commen_margin m_r_10">'.$t_data[$i]["boardsname"].'</span>';
            $str_content .= '<span>'.$t_data[$i]["author"].'</span>';
            $str_content .= '<span class="commen_right ">'.$t_data[$i]["popularity"].'</span>';
            $str_content .= '</p></div>';
        }
    }
    $str_content .= '<div class="content_list nopic padding10 noborder_bottom">';
    $str_content .= '<h4><a href="'.$t_data[$topic_num-1]["href"].'" onclick="add_read_num(this)">'.$t_data[$topic_num-1]["title"].'</a></h4>';
    $str_content .= '<p class="commen_p padding-bottom">';
    $str_content .= '<span class="commen_margin m_r_10">'.$t_data[$topic_num-1]["boardsname"].'</span>';
    $str_content .= '<span>'.$t_data[$topic_num-1]["author"].'</span>';
    $str_content .= '<span class="commen_right ">'.$t_data[$topic_num-1]["popularity"].'</span>';
    $str_content .= '</p></div>';
}

/* ʮ���Ƽ����� */
$t_data = array();
$t_data = getRecommendArticle($link);

$str_content .= '<li class="hot_li">';
$str_content .= '<h3 class="hot_title border_bottom">ʮ���Ƽ�����<span class="hot_trigle"></span></h3>';
$str_content .= '<div class="hot_list_wrap border_bottom">';

$topic_num = count($t_data);
if ($topic_num > 0) {
    if ($topic_num > 1) {
        for ($i = 0; $i < $topic_num-1; $i++) {
            $str_content .= '<div class="content_list nopic padding10">';
            $str_content .= '<h4><a href="'.$t_data[$i]["href"].'" onclick="add_read_num(this)">'.$t_data[$i]["title"].'</a></h4>';
            $str_content .= '<p class="commen_p padding-bottom border_bottom">';
            $str_content .= '<span class="commen_margin m_r_10">'.$t_data[$i]["boardsname"].'</span>';
            $str_content .= '<span>'.$t_data[$i]["author"].'</span>';
            $str_content .= '<span class="commen_right ">'.$t_data[$i]["popularity"].'</span>';
            $str_content .= '</p></div>';
        }
    }
    $str_content .= '<div class="content_list nopic padding10 noborder_bottom">';
    $str_content .= '<h4><a href="'.$t_data[$topic_num-1]["href"].'" onclick="add_read_num(this)">'.$t_data[$topic_num-1]["title"].'</a></h4>';
    $str_content .= '<p class="commen_p padding-bottom">';
    $str_content .= '<span class="commen_margin m_r_10">'.$t_data[$topic_num-1]["boardsname"].'</span>';
    $str_content .= '<span>'.$t_data[$topic_num-1]["author"].'</span>';
    $str_content .= '<span class="commen_right ">'.$t_data[$topic_num-1]["popularity"].'</span>';
    $str_content .= '</p></div>';
}

/* ��ǰ���Ű��� */
$t_data = getHotBoards($link);

$str_content .= '<li class="hot_li">';
$str_content .= '<h3 class="hot_title border_bottom">��ǰ���Ű���<span class="hot_trigle"></span></h3>';
$str_content .= '<div class="hot_list_wrap border_bottom">';

$topic_num = count($t_data);
if ($topic_num > 0) {
    for ($i = 0; $i < $topic_num; $i++) {
        $str_content .= '<div class="content_list_wrap padding10 border_bottom padding-bottom">';
        $str_content .= '<a href="'.$t_data[$i]["href"].'">';
        $str_content .= '<img class="hot_li_img" src="'.$t_data[$i]["img"].'" alt="board.png"/>';
        $str_content .= '<div class="hot_content">';
        $str_content .= '<h3 class="hot_name">'.$t_data[$i]["name"].'</h3>';
        $str_content .= '<p class="hot_des">'.$t_data[$i]["des"].'</p>';
        $str_content .= '<a id="fav_'.$t_data[$i]["fav"].'" href="" class="hot_star" onclick="return collect_by_type(6, this, \''.$t_data[$i]["board_id"].'\');"><img src="'.($t_data[$i]["fav"]==1?"img/star2.png":"img/star1.png").'"/></a>';
        $str_content .= '<p class="hot_count"><span class="hot_left">��ǰ���� : '.$t_data[$i]["online"].'��</span><span class="hot_right">�������� : '.$t_data[$i]["total"].'</span></p>';
        $str_content .= '</div></a></div>';
    }
}

/* ��ǰ���ž��ֲ� */
// getHotClubs();
$t_data = array();

$t_data = getHotClubs($link);

$str_content .= '<li class="hot_li">';
$str_content .= '<h3 class="hot_title border_bottom">��ǰ���ž��ֲ�<span class="hot_trigle"></span></h3>';
$str_content .= '<div class="hot_list_wrap border_bottom">';

$topic_num = count($t_data);
if ($topic_num > 0) {
    for ($i = 0; $i < $topic_num; $i++) {
        $str_content .= '<div class="content_list_wrap padding10 border_bottom padding-bottom">';
        $str_content .= '<a href="'.$t_data[$i]["href"].'">';
        $str_content .= '<img class="hot_li_img" src="'.$t_data[$i]["img"].'" alt="club.png"/>';
        $str_content .= '<div class="hot_content">';
        $str_content .= '<h3 class="hot_name">'.$t_data[$i]["name"].'</h3>';
        $str_content .= '<p class="hot_des ellipsis">'.$t_data[$i]["des"].'</p>';
        $str_content .= '<p class="hot_count"><span class="hot_left">��ǰ���� : '.$t_data[$i]["online"].'��</span><span class="hot_right">�������� : '.$t_data[$i]["article_num"].'</span></p>';
        $str_content .= '</div></a></div>';
    }
}

$str_content .= '</div>';
// json_encode()ת�����ַ����������ı���ΪUTF-8����
$str_content = mb_convert_encoding($str_content, "UTF-8", "GBK");
$all_arr["detail"] = $str_content;
echo json_encode($all_arr);
mysql_close($link);
?>

