<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
if(empty($_COOKIE["app_type"]))
    setcookie("app_type", "news");
if(empty($_COOKIE["app_show"]))
    setcookie("app_show", iconv("GBK","UTF-8//IGNORE","新闻"));
if(empty($_COOKIE["sec_category"]))
    setcookie("sec_category", "news_mix");

if(!is_own_label($_COOKIE["sec_category"], "news")) {
    setcookie("app_type", "news");
    setcookie("app_show", iconv("GBK", "UTF-8//IGNORE", "新闻"));
    setcookie("sec_category", "news_mix");
    // 切换栏目需要重新加载
    echo '<script language="javascript">location.href=location.href</script>';
}

include_once("head.php");
include_once("sec_news.php")
?>
<div id="linklist">
</div>
<div class="newsIndex_showBox">
    <img src="images/1.png" alt="images/1.png"/>
</div>
<div id="detail">
</div>
<div id="pagebox">
</div>
<?php
include_once("foot.php");
?>
