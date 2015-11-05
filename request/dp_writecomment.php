<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$method_array = $_POST;

function error_quit($errno, $error_str) {
    global $link;
    @mysql_close($link);
    echo json_encode(array("result"=>$errno, "msg"=>iconv("GBK", "UTF-8", $error_str)));
    exit;
}

$user_id = $currentuser["num_id"];
$user_name = $currentuser["userid"];

if (empty($user_name) || $user_name == "guest")
    error_quit(-100, "���ȵ�¼");

$shop_id = $method_array["shop_id"];
if (empty($shop_id))
    error_quit(-1, "δ֪�ĵ���");

$comment_score = intval($method_array["comment_score"]);
if (empty($comment_score))
    error_quit(-2, "����û�жԵ�������");

$taste_score = intval($method_array["taste_score"]);
if (empty($taste_score))
    error_quit(-2, "����û�жԵ�������");

$env_score = intval($method_array["env_score"]);
if (empty($taste_score))
    error_quit(-2, "����û�жԵ�������");

$sev_score = intval($method_array["sev_score"]);
if (empty($sev_score))
    error_quit(-2, "�㻹û�жԵ�������");

$avg_pay = floatval($method_array["avg_pay"]);

$des = $method_array["des"];
if (empty($des))
    error_quit(-3, "�������ݲ���Ϊ��");
$tmp = iconv("UTF-8", "GBK//IGNORE", $des);
if ($tmp)
    $des = $tmp;


$shop_name = getShopInfoById($link, $shop_id)["cnName"];
if (empty($shop_name))
    error_quit(-1, "δ֪�ĵ���");

$comment_info = array(
    "user_id" => $user_id,
    "user_name" => $user_name,
    "shop_id" => $shop_id,
    "comment_score" => $comment_score,
    "taste_score" => $taste_score,
    "env_score" => $env_score,
    "sev_score" => $sev_score,
    "avg_pay" => $avg_pay,
    "has_pic" => 0,
//    "content" => illCharIgnore($des),
    "content" => $des,
    "wifi" => "X",
    "park" => "X"
);

if (!dpInsertComment($link, $comment_info)) {
    error_quit(-10, "��������ʧ��");
}

dpDealPoints($link, $user_id, $user_name, $shop_id, $shop_name);
dpUpdateShopInfo($link, $shop_id);

echo json_encode(array("result"=>0));
mysql_close($link);
return true;
?>
