<?php
	session_start();
	$_SESSION['confirm_num']=(string)rand(1000,9999);
	$_SESSION['publish_time']=time();
	echo $_SESSION['confirm_num'];
?>
