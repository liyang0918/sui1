<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header('Content-Type:text/xml');
$conn=db_connect_web();

$phone=$_POST["phone"];

if($check = mysql_query("SELECT COUNT(1) FROM users WHERE user_id='$u'",$conn)) {
    $check_row = mysql_fetch_row($check);
    mysql_free_result($check);
    if (intval($check_row[0]) <= 0)
        echo true;
    else
        echo false;
}
mysql_close($conn);
