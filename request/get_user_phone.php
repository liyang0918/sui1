<?php
session_start();
include("../../../mitbbs_funcs.php");
include(dirname(__FILE__)."/../func.php");
$link = db_connect_web();
$user_id = mysql_real_escape_string($_POST["user_id"]);

$sql = "SELECT phone_num, area_code AS country_code FROM users WHERE user_id='$user_id';";
if($result = mysql_query($sql, $link)) {
    if ($row = mysql_fetch_array($result)) {
        $_SESSION["user_id"] = $user_id;
        echo json_encode(array("result"=>0, "phone_num"=>$row["phone_num"], "country_code"=>$row["country_code"]));
    } else {
        echo json_encode(array("result"=>-1));
    }
}
mysql_close($link);
