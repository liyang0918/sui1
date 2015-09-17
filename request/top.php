<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
$link = db_connect_web();
//top image ²¿·Ö

function getArticleLunbo($link){
    $articlelist = array();
    $sql_str = "select board_id,article_id,new_url from article_image_list where board_id=366 and type=200 group
                          by article_id,board_id ORDER BY id DESC limit 3";
    $result = mysql_query($sql_str,$link);
    $i = 0;
    while($row = mysql_fetch_array($result)){
        $articlelist[$i]["article_id"]= $row["article_id"];
        $articlelist[$i]["board_id"]= $row["board_id"];
        $articlelist[$i]["new_url"]= $row["new_url"];
        $i++;
    }
    mysql_free_result($result);

    return $articlelist;
}

$articlelist = getArticleLunbo($link);
print_r($articlelist);

$all_arr=array();
$img_arr=array();
foreach ($articlelist as $row) {
    $tmp=array();
    $board_id = $row["board_id"];
    $article_id = $row["article_id"];
    $local_img = $row["new_url"];
//boardname
    $sql_str = "select board_desc,boardname from board where board_id=" . $board_id;
    $result2 = mysql_query($sql_str, $link);
    if ($row_board = mysql_fetch_array($result2)) {
        $board_name = $row_board["boardname"];
    }
    mysql_free_result($result2);

    $img = BBS_HOME . '/pic_home/boards/' . $board_name . "/" . $local_img;
    if (is_file($img)) {
        $tmp["imgURL"] = "http://" . $_SERVER["SERVER_NAME"] . "/boardimg/" . $board_name . "/" . $local_img;
    }
//article detail
    $sql_str1 = "select title,groupid,owner,read_num,reply_num,total_reply,board_id,o_bid,o_groupid from dir_article_" . $board_id .
        " where article_id=" . $article_id;
    $result3 = mysql_query($sql_str1, $link);
    if ($row1 = mysql_fetch_array($result3)) {
        $tmp["title"] = $row1["title"];
        $tmp["groupid"] = $row1["groupid"];
        $tmp["article_id"] = $article_id;
        $tmp["type"]="BBS";
    }
    mysql_free_result($result3);
    $img_arr[] = $tmp;
}
$type = "index";
$str_img = '<div id="carouselfigure" class="main_image">';
$str_img .= '<div id="wrapper">';
$str_img .= '<div class="slider-wrapper theme-default box">';
$str_img .= '<div id="slider" class="nivoSlider">';
foreach($img_arr as $index=>$img_one){
    $php_page = "one_group.php?&board=".$board_name."&group=".$img_one["groupid"];
    if ($index == 1) {
        $str_img .= '<a class="hr_a" href="'.$php_page.'"><img src="'.$img_one["imgURL"].'" alt="img" /></a>';
    } else {
        $str_img .= '<a href="'.$php_page.'"><img src="'.$img_one["imgURL"].'" alt="img" /></a>';
    }
}
$str_img .= "</div></div></div></div>";
echo $str_img;
//$str_content =
//top image end
//detail start

//detail end
$all_arr["carouselfigure"] = $str_img;
echo json_encode($all_arr);
mysql_close($link);
?>
