<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
$link = db_connect_web();
$app_type = 0; // 0 获取好友列表

$num_id = $currentuser["num_id"];
$user_id = $currentuser["userid"];

if ($user_id == "guest") {
    $user_id = "";
    $num_id = "";
}

$url_page = "myfriends.php?page=";

$page = intval($_GET["page"]);
if (empty($page)) {
    $page = 1;
}
/* 每页显示数 */
$num = 10;
/* 总数 */
$sql = "SELECT count(*) AS count FROM friend_list WHERE numeral_user_id=$num_id AND type=$app_type AND is_approve = 'Y' ";
$resultcnt = mysql_query($sql, $link);
if($rowsCnt = mysql_fetch_array($resultcnt)) {
    $total = $rowsCnt["retcount"];
} else {
    $total = "0";
}

$ret = getMyFriendList($link, $user_id, $app_type, $page, $num);
//
//foreach ($ret as $i=>$each) {
//    echo "<br />[$i] =><br />";
//    foreach ($each as $key=>$value) {
//        echo "&nbsp;&nbsp;&nbsp;&nbsp;$key => $value<br />";
//    }
//}
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="return go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        我的好友
        <a class="span_r jy_f_a" ><img src="img/friend.png" alt="friend.png" onclick="return membersearch();"/></a>
    </div><!--<End ds_box-->
    <ul class="fs_group margin-bottom">
        <?php foreach ($ret as $each) { ?>
            <li class="border_bottom">
                <a href="<?php echo $each["href"]; ?>"><img src="<?php echo $each["headimg"]; ?>" /><?php echo $each["user_id"]; ?></a>
            </li>
        <?php } ?>
    </ul><!--End jy_gen_group-->


<?php
// 文章分页显示
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
    <script type="text/javascript">
        function membersearch() {
            var name = prompt("请输入用户名", "");
            if (name) {
                var url = "one_search.php?member="+name+"&fuzzy=0";
                document.location = url;

                return false;
            }
            return false;
        }
    </script>

<?php
include_once("foot.php");
mysql_close($link);
?>