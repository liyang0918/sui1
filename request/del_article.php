<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");

$method_array = $_POST;
$user_id = $currentuser["userid"];
$user_num_id = $currentuser["num_id"];
if (empty($user_id) or $user_id == "guest")
    error_quit(-1, "����û�е�¼", "beforeExit", array($link, ""));

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
    error_quit(-11, "δ֪�İ�������ֲ���");

$filename = $method_array["filename"];
if (empty($filename))
    error_quit(-12, "�����ļ�������ȷ");

$article_id = $method_array["article_id"];
if (empty($article_id))
    error_quit(-13, "����ID����");

$club_flag = 0;
if ($method_array["club_flag"])
    $club_flag = $method_array["club_flag"];

$group_id = $method_array["group_id"];
if (empty($group_id))
    error_quit(-14, "����ID����");

$process = "";
if ($article_id == $group_id) {
    $process = "jump";
} else {
    $process = "reload";
    // �ظ�
    $mode = 6;
}

if (empty($user_id) or $user_id == "guest")
    error_quit(-1, "����û�е�¼");

if($club_flag==0)
    $ret = bbs_delfile($mode,$boardname, $filename, $article_id, 0);
else
    $ret = bbs_delfile($mode, $boardname, $filename, $article_id, 1);

switch ($ret) {
    case -1:
        error_quit(-1, "����Ȩɾ������");
        break;
    case -2:
        error_quit(-2, "����İ������ļ���");
        break;
    case -3:
        error_quit(-3, "���ݿ����Ӵ���");
        break;
    default:
        echo json_encode(array("result"=>0, "msg"=>$process));
}
mysql_close($link);

return true;