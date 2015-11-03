<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

$link = db_connect_web();

$city = $_POST["city"];
$user_id = $currentuser["userid"];
$user_num_id = $currentuser["num_id"];
if ($user_id != "guest") {
    dpSetUserLastCity($link, $city, $user_num_id);
}

//detail end

mysql_close($link);
?>
