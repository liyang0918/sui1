<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$type="classical";
$str_content = '<div id="detail"><ul class="hot_recommend">';

function getSectionHotArticles($msg){
    global $link;

    $ret=array();

    $brdarr = array();
    $secid=$msg["sectionId"];

    if(is_china() == 1)
        $xmlfile = BBS_HOME . sprintf('/xml/day_sec%d_limit.xml', $secid-1);
    else
        $xmlfile = BBS_HOME . sprintf('/xml/day_sec%d.xml', $secid-1);

    $results = '';
    $results = read_xmlfile_content_web($xmlfile, 3);
    $ii = 0;

    $count = 0;
    foreach ($results as $each) {
        if ($count >= 10)
            break;

        $hot_title = $each['title'];
        $hot_author = $each['author'];
        $hot_board = $each['board'];
        $hot_groupid = $each['groupid'];

        $brdnum = bbs_getboard($hot_board, $brdarr);
        if ($brdnum == 0)
            continue;

        $data = array();
        $data["title"] = $hot_title;
        $data["author"] = $hot_author;

        $data["boardsname"] = $brdarr["DESC"];

        $sql = "select total_reply,read_num from dir_article_".$brdarr["BOARD_ID"]." where article_id=".$hot_groupid.";";
        $row = @mysql_fetch_array(mysql_query($sql, $link));
        if ($row)
            $data["popularity"] = $row["total_reply"].'/'.$row["read_num"];
        else
            $data["popularity"] = "0/0";

        $data["href"] = url_generate(3, array("type"=>$_COOKIE["app_type"],"board" => $brdarr["NAME"], "groupid" => $hot_groupid));

        $ret[] = $data;
        $count++;
    }

    return $ret;
}

foreach ($forum_class_list as $each) {
    $sectionId = intval($each["section_num"]);
    if($sectionId == 0)
        $sectionId = 13;

    $forum_name = $each["section_name"];

    $t_data = array();
    $t_data = getSectionHotArticles(array("sectionId" => $sectionId));

    $str_content .= '<li class="hot_li">';
    $str_content .= '<h3 class="hot_title border_bottom">'.$forum_name.'<span class="hot_trigle"></span></h3>';
    $str_content .= '<div class="hot_list_wrap border_bottom">';

    $article_num = count($t_data);
    if ($article_num > 0) {
        if ($article_num > 1) {
            for ($i = 0; $i < $article_num-1; $i++) {
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
        $str_content .= '<h4><a href="'.$t_data[$article_num-1]["href"].'" onclick="add_read_num(this)">'.$t_data[$article_num-1]["title"].'</a></h4>';
        $str_content .= '<p class="commen_p padding-bottom">';
        $str_content .= '<span class="commen_margin m_r_10">'.$t_data[$article_num-1]["boardsname"].'</span>';
        $str_content .= '<span>'.$t_data[$article_num-1]["author"].'</span>';
        $str_content .= '<span class="commen_right ">'.$t_data[$article_num-1]["popularity"].'</span>';
        $str_content .= '</p></div>';

        $str_content .= '</li>';
    }
}

$str_content .= '</div>';
// echo $str_content;
// json_encode()转换的字符串含的中文必须为UTF-8编码
$all_arr["detail"] = iconv('GBK', 'UTF-8', $str_content);
//var_dump($all_arr);
echo json_encode($all_arr);
mysql_close($link);
?>
