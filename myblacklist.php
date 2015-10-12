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

$url_page = "myblacklist.php?page=";

$page = intval($_GET["page"]);
if (empty($page)) {
    $page = 1;
}

function getMyBlackList($link, $page, $num=10) {
    global $currentuser;
    $page = ($page-1)*$num;

    $user_id = $currentuser["userid"];
    $num_id = $currentuser["num_id"];
    $sql="SELECT users.user_id user_id,users.numeral_user_id numeral_user_id,expr
	   FROM friend_list,users
	   WHERE friend_list.numeral_user_id=$num_id AND
	        friend_list.numeral_friend_id=users.numeral_user_id AND
	        friend_list.type=2
	  ORDER BY friend_list.add_date DESC LIMIT $page,$num";

    $result = mysql_query($sql, $link);
    $ret = array();
    while($row = mysql_fetch_array($result)) {
        if ($row) {
            $data = $row;
            $data["headimg"] = get_user_img($row["user_id"]);
            $data["href"] = "memberinfo.php?userid=".$row["user_id"];

            $ret[] = $data;
        } else
            continue;
    }

    mysql_free_result($result);
    return $ret;
}


/* 每页显示数 */
$num = 10;
/* 总数 */
$sql = "SELECT count(*) AS count FROM friend_list WHERE numeral_user_id=$num_id AND type=2";
$resultcnt = mysql_query($sql, $link);
if($rowsCnt = mysql_fetch_array($resultcnt)) {
    $total = $rowsCnt["retcount"];
} else {
    $total = "0";
}

$ret = getMyBlackList($link, $page, $num);
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
        我的黑名单
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

<?php
include_once("foot.php");
mysql_close($link);
?>