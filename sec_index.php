<div id="sec_category">
   <nav class="navtwo">
                <ul class="navtwo_ul border_bottom">
                    <li><p id="top" onclick="sec_category(this)">�ö�����</p></li>
                    <li><p id="hot" onclick="sec_category(this)">�����Ƽ�</p></li>
                    <li><p id="classical" onclick="sec_category(this)">��̳����</p></li>
                    <li><p id="classes" onclick="sec_category(this)">����������</p></li>
                </ul>
    </nav>
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funs.js"></script>

<script type="text/javascript">
    /* ˢ��ҳ��ʱ������Ѷ���ҳ�� */
    $(document).ready(document.cookie="current_page=1");
    $(document).ready(function () {
        var sec_category=getCookie_wap("sec_category");
        $("#"+sec_category).addClass("active");
        sec_category_auto();
    });

</script>
