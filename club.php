<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
if(empty($_COOKIE["app_type"]))
    setcookie("app_type", "club");
if(empty($_COOKIE["app_show"]))
    setcookie("app_show", iconv("GBK","UTF-8//IGNORE","¾ãÀÖ²¿"));
if(empty($_COOKIE["sec_category"]))
    setcookie("sec_category", "club_handpick");

if(!is_own_label($_COOKIE["sec_category"], "club")) {
    setcookie("app_type", "club");
    setcookie("app_show", iconv("GBK", "UTF-8//IGNORE", "¾ãÀÖ²¿"));
    setcookie("sec_category", "club_handpick");
}

include_once("head.php");
include_once("sec_club.php")
?>
<div id="linklist">
</div>
<div id="carouselfigure">
</div>
<div id="detail">
</div>
<div id="pagebox">
</div>
<?php
include_once("foot.php");
?>