<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
include_once("head.php");
?>
<div id="sec_category" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
xmlns="http://www.w3.org/1999/html">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/funs.js"></script>
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/style.css" />
<script type="text/javascript">
    $(document).ready(document.cookie="current_page=1");
</script>

    <div class="conter">
        <div class="ds_box border_bottom">
            <a href="" onclick="return go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
            ѡ�����
        </div>
        <ul class="club_good_nav margin-bottom">
            <li><a href="<?php echo url_generate(1, array("club_class"=>"emotion")); ?>"><img src="img/b1.png" alt="b1.png"/>���</a></li>
            <li class="noboder_right"><a href="<?php echo url_generate(1, array("club_class"=>"sport")); ?>""><img src="img/b2.png" alt="b2.png"/>����</a></li>
            <li><a href="<?php echo url_generate(1, array("club_class"=>"recreation")); ?>""><img src="img/b3.png" alt="b3.png"/>����</a></li>
            <li class="noboder_right"><a href="<?php echo url_generate(1, array("club_class"=>"hobby")); ?>""><img src="img/b4.png" alt="b4.png"/>����</a></li>
            <li><a href="<?php echo url_generate(1, array("club_class"=>"finance")); ?>""><img src="img/b5.png" alt="b5.png"/>�ƾ�</a></li>
            <li class="noboder_right"><a href="<?php echo url_generate(1, array("club_class"=>"hisfellow")); ?>""><img src="img/b6.png" alt="b6.png"/>ͬ��</a></li>
            <li><a href="<?php echo url_generate(1, array("club_class"=>"science")); ?>""><img src="img/b7.png" alt="b7.png"/>�Ƽ�</a></li>
            <li class="noboder_right"><a href="<?php echo url_generate(1, array("club_class"=>"art")); ?>""><img src="img/b8.png" alt="b8.png"/>����</a></li>
            <li><a href="<?php echo url_generate(1, array("club_class"=>"woman")); ?>""><img src="img/b9.png" alt="b9.png"/>Ů��</a></li>
            <li class="noboder_right"><a href="<?php echo url_generate(1, array("club_class"=>"game")); ?>""><img src="img/b10.png" alt="b10.png"/>��Ϸ</a></li>
            <li><a href="<?php echo url_generate(1, array("club_class"=>"music")); ?>""><img src="img/b11.png" alt="b11.png"/>����</a></li>
            <li class="noboder_right"><a href="<?php echo url_generate(1, array("club_class"=>"life")); ?>""><img src="img/b12.png" alt="b12.png"/>����</a></li>
            <li><a href="<?php echo url_generate(1, array("club_class"=>"schoolfellow")); ?>""><img src="img/b13.png" alt="b13.png"/>У԰</a></li>
            <li class="noboder_right"><a href="<?php echo url_generate(1, array("club_class"=>"politics")); ?>""><img src="img/b14.png" alt="b14.png"/>ʱ��</a></li>
            <li><a href="<?php echo url_generate(1, array("club_class"=>"literature")); ?>""><img src="img/b15.png" alt="b15.png"/>��ѧ</a></li>
            <li class="noboder_right"><a href="<?php echo url_generate(1, array("club_class"=>"other")); ?>""><img src="img/b16.png" alt="b16.png"/>����</a></li>
        </ul><!--End club_good_nav-->
    </div>
<?php
include_once("foot.php");
?>