<div id="sec_category">
   <nav class="navtwo">
                <ul class="navtwo_ul border_bottom">
                    <li><p id="mix" onclick="sec_category(this)">���ӻ�</p></li>
                    <li><p id="military" onclick="sec_category(this)">����</p></li>
                    <li><p id="international" onclick="sec_category(this)">����</p></li>
                    <li><p id="sport" onclick="sec_category(this)">����</p></li>
                    <li><p id="recreation" onclick="sec_category(this)">����</p></li>
                    <li><p id="science" onclick="sec_category(this)">�Ƽ�</p></li>
                    <li><p id="finance" onclick="sec_category(this)">�ƾ�</p></li>
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
