<div id="sec_category" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html">
    <ul class="dp_group1 border_bottom">
        <li class="dp_list_l">
            <?php ?>请选择城市
            <a id="1" href="" onclick="sec_select(this)"><img src="img/btn_down.png" alt="btn_down.png"/></a>
        </li>
        <li class="dp_list_r">
            <a href="" id="dp_recommend" onclick="sec_category(this)">推荐</a>
            <a href="" id="dp_near" onclick="sec_category(this)">附近</a>
            <a href="" id="dp_search" onclick="sec_category(this)">搜索</a>
            <a href="" id="dp_rank" onclick="sec_category(this)">排行</a>
        </li>
    </ul>
</div>


<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funs.js"></script>
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/style.css" />
<script type="text/javascript">
    $(document).ready(document.cookie="current_page=1");
    $(document).ready(function () {
        var sec_category=getCookie_wap("sec_category");
        $("#"+sec_category).addClass("active");
        sec_category_auto();
    });

</script>
