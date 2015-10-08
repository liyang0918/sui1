<div id="sec_category">
   <nav class="navtwo">
                <ul class="navtwo_ul border_bottom">
                    <li><a href="" id="top" onclick="sec_category(this)">置顶文章</a></li>
                    <li><a href="" id="hot" onclick="sec_category(this)">热门推荐</a></li>
                    <li><a href="" id="classical" onclick="sec_category(this)">论坛集萃</a></li>
                    <li><a href="" id="classes" onclick="sec_category(this)">分类讨论区</a></li>
                </ul>
    </nav>
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funs.js"></script>

<?php

?>

<script type="text/javascript">
    /* 刷新页面时再清除已读的页数 */
    $(document).ready(document.cookie="current_page=1");
    $(document).ready(function () {
        var sec_category=getCookie_wap("sec_category");
        $("#"+sec_category).addClass("active");
        sec_category_auto();
    });


</script>
