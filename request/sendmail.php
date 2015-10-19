<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");


$user_id = $currentuser["userid"];
$owner = $_POST["owner"];
$title = $_POST["title"];
$content = $_POST["content"];

if (empty($user_id) or $user_id == "guest") {
    echo "请先登录!";
    return false;
}
$reg_days = (time(0) - $currentuser['firstlogin']) / 86400;
if($reg_days < 3) {
    echo "您注册尚未满三天，不能发送站内邮件!";
    return false;
}
if (strchr($owner, '@') || strchr($owner, '|')|| strchr($owner, '&') ) {
    echo "错误的收信人帐号!";
    return false;
}
$userlist = array();
// 分别以分号和空格切割收件人列表
$user_num = 0;
$tmp1 = explode(";", trim($owner));
foreach ($tmp1 as $each) {
    $tmp2 = explode(" ", trim($each));
    foreach ($tmp2 as $tmp3) {
        if (!empty($tmp3)) {
            $user_num++;
            $userlist[] = $tmp3;
        }
    }
}
if ($user_num > 10) {
    echo "收件人超过10个,请删减后再发送!";
    return false;
}

$errorlist = "";
$all_success = true;
foreach ($userlist as $user) {
    $ret = sendmail($user, $title, $content, 0, 0);
    $errorlist[] = $ret;
    if ($ret["error"])
        $all_success = false;
}

if ($all_success) {
    echo true;
    return false;
} else {
    foreach ($errorlist as $each) {
        if ($each["error"])
            echo "发送给 ".$each['userId']."失败:".$each['errorMsg'].";\n";
    }
    return false;
}

?>
