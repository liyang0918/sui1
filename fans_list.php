<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
$link = db_connect_web();
$app_type = 3; // 3 获取粉丝

$user_id = $_GET["userid"];
if (empty($user_id))
    $user_id = "guest";

$url_page = "fans_list.php?userid=$user_id&page=";

$page = intval($_GET["page"]);
if (empty($page)) {
    $page = 1;
}
/* 每页显示数 */
$num = 10;
/* 总数 */
$sql = "select numeral_user_id from users where user_id='$user_id'";
$resultcnt = mysql_query($sql, $link);
if($rowscnt = mysql_fetch_array($resultcnt))
    $num_id = $rowscnt["numeral_user_id"];

$sql = "select count(1) as retcount from funs_list f,users u where f.numeral_friend_id='$num_id' and
                 f.numeral_user_id=u.numeral_user_id and f.type=1";
$resultcnt = mysql_query($sql, $link);
if($rowsCnt = mysql_fetch_array($resultcnt)) {
    $total = $rowsCnt["retcount"];
}else {
    $total = "0";
}

$ret = getMyFriendList($link, $user_id, $app_type, $page, $num);

//foreach ($ret as $i=>$each) {
//    echo "<br />[$i] =><br />";
//    foreach ($each as $key=>$value) {
//        echo "&nbsp;&nbsp;&nbsp;&nbsp;$key => $value<br />";
//    }
//}
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        我的粉丝
    </div><!--<End ds_box-->
    <ul class="fs_group margin-bottom">
        <?php foreach ($ret as $each) { ?>
            <li class="border_bottom">
            <a href="<?php echo $each["href"]; ?>"><img src="<?php echo $each["headimg"]; ?>" /><?php echo $each["user_id"]; ?></a>
            <?php if ($each["type"] == 1) { ?>
                <span>互相关注</span>
            <?php } else { ?>
                <em id="add_focus_<?php echo $each["user_id"]; ?>"><a onclick="return add_focus('<?php echo $each["user_id"]; ?>', 1)">+关注</a></em>
            <?php } ?>
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

<?php
include_once("foot.php");
mysql_close($link);
?>