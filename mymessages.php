<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
$link = db_connect_web();

$num_id = $currentuser["num_id"];
$user_id = $currentuser["userid"];

if ($user_id == "guest") {
    $user_id = "";
    $num_id = "";
}

$url_page = "mymessages.php?page=";

$page = intval($_GET["page"]);
if (empty($page)) {
    $page = 1;
}

/* ���� */
$sql = "SELECT count(*) AS count FROM friend_list WHERE numeral_user_id=$num_id AND type=2";
$resultcnt = mysql_query($sql, $link);
if($rowsCnt = mysql_fetch_array($resultcnt)) {
    $total = $rowsCnt["retcount"];
} else {
    $total = "0";
}

$ret = getFriendApply($link, 1, 1);
$ret1 = $ret[0];
//
//foreach ($ret as $i=>$each) {
//    echo "<br />[$i] =><br />";
//    foreach ($each as $key=>$value) {
//        echo "&nbsp;&nbsp;&nbsp;&nbsp;$key => $value<br />";
//    }
//}
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        �ҵ���Ϣ
    </div><!--<End ds_box-->
    <ul class="jy_m_group">
        <?php if ($ret1) {?>
        <li class="jy_m_list">
            <a href="">
                <img src="img/jy_f1.png" alt="jy_f1.png"/>
                <div class="jy_m_group_div">
                    <?php
                    if($ret1["to"] == $currentuser["userid"]) {
                        echo '<span>������</span>';
                        if ($ret1["handle_result"] == "W") {
                            echo '<em>'.$ret1["content"].'</em>';
                        } elseif ($ret1["handle_result"] == "Y") {
                            echo '<em>��ͬ��'.$ret1["from"].'�ĺ�������</em>';
                        } else {
                            echo '<em>�Ѿܾ�'.$ret1["from"].'�ĺ�������</em>';
                        }
                    } elseif ($ret1["from"] == $currentuser["userid"]) {
                        echo '<span>��������</span>';
                        if ($ret1["handle_result"] == "W") {
                            echo '<em>���뽫 '.$ret1["to"].' ��Ϊ����</em>';
                        } elseif ($ret1["handle_result"] == "Y") {
                            echo '<em>'.$ret1["to"].'��ͬ���ҵĺ�������</em>';
                        } else {
                            echo '<em>'.$ret1["to"].'�Ѿܾ��ҵĺ�������</em>';
                        }
                    } ?>
                </div>
            </a>
            <span class="jy_m_pos"><?php echo date("m-d", $ret1["date"]);?></span>
        </li>
        <?php }?>

    </ul><!--End jy_gen_group-->


<?php
// ���·�ҳ��ʾ
echo page_partition($total, $page, $num);
?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var page = <?php echo $page;?>;

            $("#page_now").css("background-color", "blue");
            $("#page_now").removeAttr("href");

            //alert($("#page_part a").size());
            $("#page_part a").click(function(){
                var url_page = "<?php echo $url_page;?>";
                if(this.id == "pre_page")
                    url_page = url_page+(page-1);
                else if(this.id == "sub_page")
                    url_page = url_page+(page+1);
                else{
                    url_page = url_page+$(this).text();
                }
                this.href = url_page;
            });
        });
    </script>

<?php
include_once("foot.php");
mysql_close($link);
?>