<?php
session_start();
include(dirname(__FILE__)."/../func.php");

function error_quit($errno, $error_str) {
    echo json_encode(array("result"=>$errno, "msg"=>iconv("GBK", "UTF-8", $error_str)));
    exit;
}

if (!isset($_SESSION["confirm_check_ok"]) || $_SESSION["confirm_check_ok"] == false) {
    error_quit(-1, "��ȫ��֤ʧ��");
}

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
} else {
    error_quit(-2, "�û�IDʧЧ");
}

if (isset($_POST["passwd"])) {
    $ret = bbs_setuserpasswd($user_id, $_POST["passwd"]);
    switch ($ret) {
        case -1:
            error_quit(-4, "����̫��");
        case -2:
            error_quit(-5, "������û�ID");
        case 0:
            error_quit(0, "�ɹ�");
        default:
            error_quit(-6, "δ֪����");
    }
} else {
    error_quit(-3, "������Ϊ��");
}