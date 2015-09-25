<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
if(empty($_COOKIE["app_type"]))
    setcookie("app_type","index");
if(empty($_COOKIE["app_show"]))
    setcookie("app_show",iconv("GBK","UTF-8//IGNORE","ÂÛÌ³"));
if(empty($_COOKIE["sec_category"]))
    setcookie("sec_category","top");
include_once("head.php");
include_once("sec_index.php")
?>
<div id="linklist">
</div>
<div id="carouselfigure">
</div>
<div id="detail">
</div>
<?php
include_once("foot.php");
?>