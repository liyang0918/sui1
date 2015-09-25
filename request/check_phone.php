<?php
require("../../../mitbbs_funcs.php");
$conn=db_connect_web();
$phone=$_POST["phone"];

if($check = mysql_query("SELECT COUNT(1) FROM users WHERE phone_num='$phone'",$conn)) {
    $check_row = mysql_fetch_row($check);
    mysql_free_result($check);
    if (intval($check_row[0]) <= 0)
        echo true;
    else
        echo "该电话号已经注册过";
}
mysql_close($conn);
