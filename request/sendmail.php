<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");


$user_id = $currentuser["userid"];
$owner = $_POST["owner"];
$title = $_POST["title"];
$content = $_POST["content"];

if (empty($user_id) or $user_id == "guest") {
    echo "���ȵ�¼!";
    return false;
}
$reg_days = (time(0) - $currentuser['firstlogin']) / 86400;
if($reg_days < 3) {
    echo "��ע����δ�����죬���ܷ���վ���ʼ�!";
    return false;
}
if (strchr($owner, '@') || strchr($owner, '|')|| strchr($owner, '&') ) {
    echo "������������ʺ�!";
    return false;
}
$userlist = array();
// �ֱ��ԷֺźͿո��и��ռ����б�
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
    echo "�ռ��˳���10��,��ɾ�����ٷ���!";
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
            echo "���͸� ".$each['userId']."ʧ��:".$each['errorMsg'].";\n";
    }
    return false;
}

?>
