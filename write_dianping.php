<?php
session_start();
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");

$curr_url = $_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];

if (empty($currentuser) or $currentuser["userid"] == "guest") {
    setcookie("before_login", $curr_url);
    header("Location:login.php");
}

$shop_id = $_GET["shop_id"];
$father_page = "one_shopinfo.php?shop_id=$shop_id";

?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>δ���ռ�</title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/footer.css" type="text/css" media="screen"/>
</head>
<body>
<div class="conter">
    <nav class="navone">
        <div class="navone_menu">
            <h3 class="navone_h3"><img class="navone_forum" src="img/menu.png" alt="menu.png"/>����</h3>
        </div>
        <img class="navone_space" src="img/space.png" alt="space.png"/>
        <?php
        if($currentuser["userid"]=="guest")  {
            setcookie("before_login", $curr_url);
        ?>
            <a class="navone_home" href="login.php">
                <img src="img/home.png" alt="home.png">
            </a>
        <?php }else{ ?>
            <a class="navone_home" href="jiaye.php"><img src="img/home.png" alt="home.png"/></a>
        <?php } ?>
    </nav>
    <ul class="navone_ul">
        <li class="navone_li"><a href="index.php"><img src="img/item.png" alt="item.png"/>��̳</a></li>
        <li class="navone_li"><a href="news.php"><img src="img/item.png" alt="item.png"/>����</a></li>
        <li class="navone_li"><a href="club.php"><img src="img/item.png" alt="item.png"/>���ֲ�</a></li>
        <li class="navone_li"><a href="immigration.php"><img src="img/item.png" alt="item.png"/>����ר��</a></li>
        <li class="navone_li"><a href="dianping.php"><img src="img/item.png" alt="item.png"/>����</a></li>
        <li class="navone_li noborder"><a href="search.php"><img src="img/item.png" alt="item.png"/>����</a></li>
    </ul>
<div class="ds_box border_bottom">
    <a onclick="go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    д����
</div><!--<End ds_box-->
<section class="shop-mycomment">
    <form id="write_dp_form" action="" method="">
        <div class="mycomm-frame">
            <ul>
                <li id="all_comment" class="all-comment">
                    <span class="name">�������ۣ�</span>
                    <a href="javascript:;"  class="comm-star "></a>
                    <a href="javascript:;"  class="comm-star "></a>
                    <a href="javascript:;"  class="comm-star "></a>
                    <a href="javascript:;"  class="comm-star "></a>
                    <a href="javascript:;"  class="comm-star "></a>
                </li>
                <li>
                    <span class="name">��ζ</span>
                    <select id="taste_score" name="score1" title="��ζ">
                        <option value='-1'>--��ѡ��--</option>
                        <option value="5" >�ǳ���(5)</option>
                        <option value="4" >�ܺ�(4)</option>
                        <option value="3" >��(3)</option>
                        <option value="2" >һ��(2)</option>
                        <option value="1" >��(1)</option>
                    </select>
                    <div class="select-btn">
                        <i class="arrow-down" style="top:12px;right:12px;"></i>
                    </div>
                </li>
                <li >
                    <span class="name">����</span>
                    <select id="env_score" name="score2" title="����">
                        <option value='-1'>--��ѡ��--</option>
                        <option value="5" >�ǳ���(5)</option>
                        <option value="4" >�ܺ�(4)</option>
                        <option value="3" >��(3)</option>
                        <option value="2" >һ��(2)</option>
                        <option value="1" >��(1)</option>
                    </select>
                    <div class="select-btn">
                        <i class="arrow-down" style="top:12px;right:12px;"></i>
                    </div>
                </li>
                <li >
                    <span class="name">����</span>
                    <select id="sev_score" name="score3" title="����">
                        <option value='-1'>--��ѡ��--</option>
                        <option value="5" >�ǳ���(5)</option>
                        <option value="4" >�ܺ�(4)</option>
                        <option value="3" >��(3)</option>
                        <option value="2" >һ��(2)</option>
                        <option value="1" >��(1)</option>
                    </select>
                    <div class="select-btn">
                        <i class="arrow-down" style="top:12px;right:12px;"></i>
                    </div>
                </li>
                <li >
                    <span class="name">�˾�</span>
                    <input id="J_average" value="" name="avgPrice" placeholder="��ѡ�" class="inp-t"/>
                </li>
                <li >
                    <span class="name">����</span>
                    <textarea id="J_describe" placeholder="����15��" name="body" cols="" rows="8" class="tarea"></textarea>
                </li>
            </ul>
        </div>
        <p><a id="J_submit" href="javascript:;" title="�ύ" class="icon-btn icon-btn-orange" onclick="return dp_check_comment();">�ύ</a></p>
    </form>
</section>
<!--���� end-->

</div>
<br><br><br><br>
<div class="footer">
    <ul>
        <li>mitbbs.com</li>
        <li><a href="index.php">�ͻ���</a></li>
        <li><a href="http://www.mitbbs.com">���԰�</a></li>
    </ul>
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/funs.js"></script>
<script type="text/javascript">
    function dp_check_comment() {
        var all_comment = document.getElementById("all_comment");
        var tags_a = all_comment.getElementsByTagName("a");
        for(var i = 0; i < tags_a.length; i++) {
            if (tags_a[i].className.match(/star-cur/) == null)
                break;
        }
        if (i == 0) {
            Alert("�������ֲ���Ϊ��", 1);
            return false;
        }

        var comment_score = i;
        var taste_score = document.getElementById("taste_score").value;
        if (taste_score == "-1") {
            Alert("����д��ζ��ֵ", 1);
            return false;
        }

        var env_score = document.getElementById("env_score").value;
        if (env_score == "-1") {
            Alert("����д������ֵ", 1);
            return false;
        }

        var sev_score = document.getElementById("sev_score").value;
        if (sev_score == "-1") {
            Alert("����д�����֧", 1);
            return false;
        }

        var avg_pay = 0;
        var J_average = document.getElementById("J_average");
        if (J_average.value != undefined && J_average.value.match(/^\d+(\.\d+)?$/)) {
            avg_pay = J_average.value;
        }

        var des = document.getElementById("J_describe").value;
        if (des.replace(/(^\s*)|(\s*$)/g, "").length < 15) {
            Alert("��������������15��", 1);
            return false;
        }

        var url = "/mobile/forum/request/dp_writecomment.php?shop_id="+"<?php echo $shop_id; ?>";
        var para = {
            "shop_id":"<?php echo $shop_id; ?>",
            "comment_score":comment_score,
            "taste_score":taste_score,
            "env_score":env_score,
            "sev_score":sev_score,
            "avg_pay":avg_pay,
            "des":des
        };

        dp_send_comment(url, para, "<?php echo $father_page; ?>");
        return false;
    }
</script>
</body>
</html>

