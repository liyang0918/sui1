<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
include_once("head.php");
$curr_url = $_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];
if ($currentuser["userid"] == "guest") {
    setcookie("before_login", $curr_url);
?>
<script type="text/javascript">
    window.location.href = "login.php";
</script>
<?php
}

$link = db_connect_web();
$page = $_GET["page"];
if (empty($page))
    $page = 1;

$url_page = "myclub.php?page=";

function getMyClub($link, $page, $num) {
    global $currentuser;

    if (empty($num))
        $num = 10;
    $page = ($page-1)*$num;

    $sql = "SELECT club_id FROM club_member WHERE member_num_id={$currentuser['num_id']} LIMIT $page,$num";

    $result = mysql_query($sql, $link);
    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        $sql = "SELECT club_id,club_group_id,club_name as enName,club_cname as cnName,post_sum,member_sum FROM club WHERE club_id={$row["club_id"]};";
        $result_each = mysql_query($sql, $link);
        if ($row_each = mysql_fetch_array($result_each)) {
            $tmp["enName"] = $row_each["enName"];
            $tmp["cnName"] = $row_each["cnName"];
            $tmp["post_sum"] = $row_each["post_sum"];
            $tmp["img"] = getClubImg($row_each["enName"]);
            $tmp["member_sum"] = $row_each["member_sum"];
            $tmp["href"] = url_generate(2, array("club"=>$row_each["enName"]));

            $ret[] = $tmp;
        }
    }

    return $ret;
}

// ÿҳ��ʾ��
$num = 10;
// ����
$total = 0;
$sql = "select count(*) as count from club_member where club_member.member_num_id={$currentuser['num_id']};";
$result = mysql_query($sql, $link);
if ($row = mysql_fetch_array($result))
    $total = $row["count"];

$t_data = getMyClub($link, $page, $num);
?>
<div class="ds_box border_bottom">
    <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    �ҵľ��ֲ�
</div><!--<End ds_box-->
<ul class="hot_list_wrap border_bottom block">
    <?php foreach ($t_data as $each) { ?>
    <li class="content_list_wrap padding10 border_bottom  padding-bottom board_link">
        <a href="<?php echo $each["href"]; ?>">
            <img class="hot_li_img" src="<?php echo $each["img"]; ?>" alt="club.png"/>
            <div class="hot_content">
                <h3 class="hot_name"><?php echo $each["cnName"]; ?></h3>
                <p class="hot_des"><?php echo $each["enName"]; ?></p>
                <p class="hot_count"><span class="hot_left">��Ա������<?php echo $each["member_sum"]; ?>��</span><span class="hot_right">����������<?php echo $each["post_sum"]; ?></span></p>
            </div>
        </a>
    </li>
    <?php } ?>
</ul>
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