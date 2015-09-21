<div id="sec_category" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html">
    <nav class="navtwo">
        <ul class="navtwo_ul border_bottom">
            <li><a href="" id="club_handpick" onclick="sec_category(this)">精选</a></li>
            <li><a href="" id="club_emotion" onclick="sec_category(this)">情感</a></li>
            <li><a href="" id="club_woman" onclick="sec_category(this)">女性</a></li>
            <li><a href="" id="club_sport" onclick="sec_category(this)">体育</a></li>
            <li><a href="" id="club_game" onclick="sec_category(this)">游戏</a></li>
            <li><a href="" id="club_recreation" onclick="sec_category(this)">娱乐</a></li>
            <li><a href="" id="club_music" onclick="sec_category(this)">音乐</a></li>
        </ul>
        <a class="club_click a_right"><img class="btn_down club_img_w" src="img/btn_down.png" alt="btn_down.png"/></a>
    </nav>
    <div class="club_nav_wrap">
        <ul class="club_nav">
            <div class="club_nav_top">
                <h4>分类切换</h4>
                <span><img class="btn_down club_btn_up" src="img/d.png" alt="d.png"/></span>
            </div>
            <dl class="club_dl">
                <dt><a href="" id="club_handpick" onclick="sec_category(this)"></a>精选</dt>
                <dd><a href="" id="club_emotion" onclick="sec_category(this)">情感</a></dd>
                <dd><a href="" id="club_woman" onclick="sec_category(this)">女性</a></dd>
                <dd><a href="" id="club_sport" onclick="sec_category(this)">体育</a></dd>
                <dd><a href="" id="club_game" onclick="sec_category(this)">游戏</a></dd>
                <dd><a href="" id="club_recreation" onclick="sec_category(this)">娱乐</a></dd>
                <dd><a href="" id="club_music" onclick="sec_category(this)">音乐</a></dd>
                <dd><a href="" id="club_hobby" onclick="sec_category(this)">爱好</a></dd>
                <dd><a href="" id="club_life" onclick="sec_category(this)">生活</a></dd>
                <dd><a href="" id="club_finance" onclick="sec_category(this)">财经</a></dd>
                <dd><a href="" id="club_schoolfellow" onclick="sec_category(this)">校友</a></dd>
                <dd><a href="" id="club_hisfellow" onclick="sec_category(this)">同乡</a></dd>
                <dd><a href="" id="club_politics" onclick="sec_category(this)">时政</a></dd>
                <dd><a href="" id="club_science" onclick="sec_category(this)">科技</a></dd>
                <dd><a href="" id="club_literature" onclick="sec_category(this)">文学</a></dd>
                <dd><a href="" id="club_art" onclick="sec_category(this)">艺术</a></dd>
                <dd><a href="" id="club_other" onclick="sec_category(this)">其他</a></dd>
            </dl>
        </ul>
    </div>
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
