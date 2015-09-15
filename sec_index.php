<div id="sec_category">
   <nav class="navtwo">
                <ul class="navtwo_ul border_bottom">
                    <li><p id="top" onclick="sec_category(this)">置顶文章</p></li>
                    <li><p id="hot" onclick="sec_category(this)">热门推荐</p></li>
                    <li><p id="classical" onclick="sec_category(this)">论坛集萃</p></li>
                    <li><p id="classes" onclick="sec_category(this)">分类讨论区</p></li>
                </ul>
    </nav>
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funs.js"></script>
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/style.css" />
<script type="text/javascript">
    $(document).ready(function () {
        var sec_category=getCookie_wap("sec_category");
        $("#"+sec_category).addClass("active");
        sec_category_auto();
    });

</script>
