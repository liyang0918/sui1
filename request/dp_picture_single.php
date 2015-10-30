<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$shop_id = $_POST["shop_id"];
$type = $_POST["type"];
$pic_num = $_POST["pic_num"];

$total_num = getShopPictureTotal($link, $shop_id, $type);
$pic = getShopPictureList($link, $shop_id, $type, $pic_num, 1, false)[0];

$tag_name = mb_convert_encoding($pic["tag_name"], "UTF-8", "GBK");

$all_arr["result"] = 0;
$all_arr["total_num"] = $total_num;
$all_arr["tag_name"] = $tag_name;
$all_arr["img"] = $pic["img"];

echo json_encode($all_arr);

mysql_close($link);
?>