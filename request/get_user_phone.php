<?php
session_start();
include("../../../mitbbs_funcs.php");
include(dirname(__FILE__)."/../func.php");
$link = db_connect_web();
// sql 放注入,在【;,'"】处截断
$user_id = mbstr_split($_POST["user_id"], ";,'\"");
$_SESSION["user_id"] = $user_id;

$sql = "SELECT phone_num, country_code FROM users u, countries c WHERE user_id='$user_id' AND c.en_name=u.country;";
if($result = mysql_query($sql, $link)) {
    if ($row = mysql_fetch_array($result)) {
        echo json_encode(array("result"=>0, "phone_num"=>$row["phone_num"], "country_code"=>$row["country_code"]));
    } else {
        echo json_encode(array("result"=>-1));
    }
}
mysql_close($link);
