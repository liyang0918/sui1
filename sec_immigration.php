<div id="sec_category" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html">
    <nav class="navtwo">
        <ul class="navtwo_ul immigration_nav">
            <li><a class="active" href="" id="i_column" onclick="sec_category(this)">��ʦר��</a></li>
            <li><a href="" id="i_lawyer" onclick="sec_category(this)">������ʦ</a></li>
            <li><a href="" id="i_news" onclick="sec_category(this)">��������</a></li>
            <li><a href="" id="i_visa" onclick="sec_category(this)">����ǩ֤��Ϣ</a></li>
            <li><a href="" id="i_forum" onclick="sec_category(this)">������</a></li>
        </ul>
    </nav>
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
