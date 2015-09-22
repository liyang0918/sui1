<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
if(empty($_COOKIE["app_type"]))
    setcookie("app_type", "immigration");
if(empty($_COOKIE["app_show"]))
    setcookie("app_show", iconv("GBK","UTF-8//IGNORE", "移民"));
if(empty($_COOKIE["sec_category"]))
    setcookie("sec_category", "i_column");

if(!is_own_label($_COOKIE["sec_category"], "immigration")) {
    setcookie("app_type", "immigration");
    setcookie("app_show", iconv("GBK", "UTF-8//IGNORE", "移民"));
    setcookie("sec_category", "i_column");
    // 切换栏目需要重新加载
    echo '<script language="javascript">location.href=location.href</script>';
}

include_once("head.php");
include_once("sec_immigration.php")
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