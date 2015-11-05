<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
if(empty($_COOKIE["app_type"]))
    setcookie("app_type", "club");
if(empty($_COOKIE["app_show"]))
    $_COOKIE["app_show"] = iconv("GBK", "UTF-8//IGNORE", "俱乐部");
if(empty($_COOKIE["sec_category"]))
    setcookie("sec_category", "club_handpick");

if(!is_own_label($_COOKIE["sec_category"], "club")) {
    setcookie("app_type", "club");
    setcookie("app_show", iconv("GBK", "UTF-8//IGNORE", "俱乐部"));
    setcookie("sec_category", "club_handpick");
    // 切换栏目需要重新加载
    echo '<script language="javascript">location.href=location.href</script>';
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
<script type="text/javascript">
    function show_club_list(obj) {
        var type = obj.id.split("_")[1];
        if (type == "handpick") {
            window.location.href = "club_classify.php";
            return false;
        }

        window.location.href = "one_class_club.php?club_class="+type;
        return false;
    }
</script>
<?php
include_once("foot.php");
?>