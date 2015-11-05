<?php
session_start();
include_once(dirname(__FILE__)."/../func.php");
	$_SESSION['confirm_num']=(string)rand(1000,9999);
    $_SESSION['confirm_error_num'] = 0;
	$_SESSION['publish_time']=time();
    log2file("confirm_code: {$_SESSION["confirm_num"]}");
//	echo $_SESSION['confirm_num'];
    echo true;
?>
