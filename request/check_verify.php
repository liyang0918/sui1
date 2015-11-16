<?php
session_start();
include("../../../mitbbs_funcs.php");
include(dirname(__FILE__)."/../func.php");

function error_quit($errno, $error_str) {
    echo json_encode(array("result"=>$errno, "msg"=>iconv("GBK", "UTF-8", $error_str)));
    exit;
}

if (!isset($_SESSION["num_auth"]) || empty($_SESSION["num_auth"])) {
    error_quit(-1, "服务器未生成验证码");
}

$confirm = $_POST["num_auth"];
if (strcasecmp($confirm, $_SESSION["num_auth"]) == 0) {
    error_quit(0, "验证码验证成功");
} else {
    error_quit(-1, "验证码不正确");
}
