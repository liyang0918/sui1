<div id="sec_category" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html">
   <nav class="navtwo">
                <ul class="navtwo_ul border_bottom">
                    <li><a href="" id="mix" onclick="sec_category(this)">���ӻ�</a></li>
                    <li><a href="" id="military" onclick="sec_category(this)">����</a></li>
                    <li><a href="" id="international" onclick="sec_category(this)">����</a></li>
                    <li><a href="" id="sport" onclick="sec_category(this)">����</a></li>
                    <li><a href="" id="recreation" onclick="sec_category(this)">����</a></li>
                    <li><a href="" id="science" onclick="sec_category(this)">�Ƽ�</a></li>
                    <li><a href="" id="finance" onclick="sec_category(this)">�ƾ�</a></li>
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
