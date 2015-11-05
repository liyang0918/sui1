<?php
session_start();
include("../../../mitbbs_funcs.php");
include(dirname(__FILE__)."/../func.php");

function error_quit($errno, $error_str) {
    echo json_encode(array("result"=>$errno, "msg"=>iconv("GBK", "UTF-8", $error_str)));
    exit;
}

if (!isset($_SESSION["confirm_num"]) || empty($_SESSION["confirm_num"])) {
    error_quit(-1, "������δ������֤��");
}

if (!isset($_SESSION["confirm_error_number"])) {
    $_SESSION["confirm_error_number"] = 0;
}

if ($_SESSION["confirm_error_number"] > 3) {
    error_quit(-2, "��֤����������������3��,������������֤��");
}

$confirm = $_POST["confirm_code"];
if (strcmp($confirm, $_SESSION["confirm_num"]) == 0) {
    if (time() - $_SESSION["publish_time"] > 600) {
        error_quit(-3, "��֤���ѹ���,����������");
    }

    $_SESSION["confirm_error_number"] = 0;
    $_SESSION["confirm_check_ok"] = true;
    error_quit(0, "��֤����֤�ɹ�");
} else {
    $_SESSION["confirm_error_number"]++;
    error_quit(-1, "��֤�벻��ȷ");
}
