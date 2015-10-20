<?php
session_start();
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$_SESSION["locate_flag"] = false;
$_SESSION["lon"] = "0.0000";
$_SESSION["lat"] = "0.0000";

log2file($_POST["lon"]." ".$_POST["lat"]."\n\n");
if ($_POST["result"] == "success") {
    $_SESSION["lon"] = intval($_POST["lon"]);
    $_SESSION["lat"] = intval($_POST["lat"]);
}

?>
