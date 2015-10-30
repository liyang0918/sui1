<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");

$method_array = $_POST;
$user_id = $currentuser["userid"];
$user_num_id = $currentuser["num_id"];
if (empty($user_id) or $user_id == "guest")
    error_quit(-1, "您还没有登录", "beforeExit", array($link, ""));

function beforeExit($link=NULL, $tmp_dir="") {
    if ($link != NULL)
        @mysql_close($link);

    if ($tmp_dir != "")
        @system("rm $tmp_dir -rf");
}

$mode = 6;
if (isset($method_array["dingflag"])) {
    $mode = intval($method_array["dingflag"])!=0?$dir_modes["ZHIDING"]:$dir_modes["ORIGIN"];
}

$boardname = $method_array["boardname"];
if (empty($boardname))
    error_quit(-11, "未知的版名或俱乐部名");

$filename = $method_array["filename"];
if (empty($filename))
    error_quit(-12, "文章文件名不正确");

$article_id = $method_array["article_id"];
if (empty($article_id))
    error_quit(-13, "文章ID错误");

$club_flag = 0;
if ($method_array["club_flag"])
    $club_flag = $method_array["club_flag"];

$group_id = $method_array["group_id"];
if (empty($group_id))
    error_quit(-14, "主题ID错误");

$process = "";
if ($article_id == $group_id) {
    $process = "jump";
} else {
    $process = "reload";
    // 回复
    $mode = 6;
}

if (empty($user_id) or $user_id == "guest")
    error_quit(-1, "您还没有登录");

if($club_flag==0)
    $ret = bbs_delfile($mode,$boardname, $filename, $article_id, 0);
else
    $ret = bbs_delfile($mode, $boardname, $filename, $article_id, 1);

switch ($ret) {
    case -1:
        error_quit(-1, "您无权删除该文");
        break;
    case -2:
        error_quit(-2, "错误的版名或文件名");
        break;
    case -3:
        error_quit(-3, "数据库连接错误");
        break;
    default:
        echo json_encode(array("result"=>0, "msg"=>$process));
}
mysql_close($link);

return true;