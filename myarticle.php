<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
setcookie("app_type", "jiaye");
setcookie("app_show", iconv("GBK","UTF-8//IGNORE","��ҳ"));

if ($_COOKIE["sec_category"] != "article") {
    setcookie("sec_category", "article");
    setcookie("article_type", "board");
}

include_once("head.php");

$page=intval($_GET["page"]);
if(empty($page)){
    $page=1;
}

?>
<div class="ds_box border_bottom">
    <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    �ҵ�����
</div>
<div id="myarticle">
   <nav class="navtwo">
        <ul class="navtwo_ul border_bottom">
            <li><a href="" id="board" onclick="myarticle(this)">����������</a></li>
            <li><a href="" id="club" onclick="myarticle(this)">���ֲ�����</a></li>
        </ul>
    </nav>
</div>
<div id="detail" class="jy_articles">
</div><!--End article_wrap-->
<div id="pagebox">
</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funs.js"></script>


<script type="text/javascript">
    /* ˢ��ҳ��ʱ������Ѷ���ҳ�� */
    $(document).ready(document.cookie="current_page=1");
    $(document).ready(function () {
        var article_type=getCookie_wap("article_type");
        $("#"+article_type).addClass("active");
        myarticle_auto();
    });


</script>

<?php
include_once("foot.php");
?>
