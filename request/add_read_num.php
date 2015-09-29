<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
$url = urldecode($_POST["href"]);

$board_id = 0;
$article_id = 0;
$club_flag = 0;

$ret = array();
parse_str($url, $ret);
if (!isset($ret["group"])) {
    echo "false";
    return;
}

$article_id = $ret["group"];
$str = "article_id=".$ret["group"];
if (isset($ret["board"])) {
    $board_id = $ret["board"];
    $club_flag = 0;
} else if (isset($ret["club"])){
    $board_id = $ret["club"];
    $club_flag = 1;
} else if (isset($ret["news"])) {
    $board_id = $ret["news"];
    $club_flag = 0;
} else {
    echo "false";
    return;
}
bbs_add_num_art_read($str, $board_id, $club_flag, $article_id);
?>
