<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
include_once("head.php");

if ($currentuser["userid"] == "guest") {
?>
<script type="text/javascript">
    location.href = "login.php";
</script>
<?php
}

$link = db_connect_web();
$page = $_GET["page"];
if (empty($page))
    $page = 1;

$url_page = "mydiscuss.php?page=";

function getMyDiscuss($link, $page, $num) {
    global $currentuser;

    if (empty($num))
        $num = 10;
    $page = ($page-1)*$num;

    $sql = "SELECT fav_id,name FROM favboard WHERE user_id={$currentuser["num_id"]} AND isboard=\"Y\" ORDER BY name LIMIT $page,$num";
    $result = mysql_query($sql, $link);
    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        $brdarr = array();
        $brdnum = bbs_getboard($row["name"], $brdarr);
        $tmp["enName"] = $row["name"];
        $tmp["cnName"] = $brdarr["DESC"];
        $tmp["article_total"] = $brdarr["TOTAL"];
        $tmp["img"] = getBoardImg($row["name"]);
        $tmp["online"] = $brdarr["CURRENTUSERS"];
        $tmp["href"] = url_generate(2, array("board"=>$row["name"]));

        $ret[] = $tmp;
    }

    return $ret;
}

// 每页显示数
$num = 10;
// 总数
$total = 0;
$sql = "SELECT count(*) AS count FROM favboard WHERE isboard=\"Y\" AND user_id={$currentuser["num_id"]};";
$result = mysql_query($sql, $link);
if ($row = mysql_fetch_array($result))
    $total = $row["count"];

$t_data = getMyDiscuss($link, $page, $num);
?>
<div class="ds_box border_bottom">
    <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    我的讨论区
</div><!--<End ds_box-->
<ul class="hot_list_wrap border_bottom block">
    <?php foreach ($t_data as $each) { ?>
    <li class="content_list_wrap padding10 border_bottom padding-bottom board_link">
        <a href="<?php echo $each["href"]; ?>">
            <img class="hot_li_img" src="<?php echo $each["img"]; ?>" alt="club.png"/>
            <div class="hot_content">
                <h3 class="hot_name"><?php echo $each["cnName"]; ?></h3>
                <p class="hot_des"><?php echo $each["enName"]; ?></p>
                <p class="hot_count"><span class="hot_left">当前在线：<?php echo $each["online"]; ?>人</span><span class="hot_right">文章总数：<?php echo $each["article_total"]; ?></span></p>
            </div>
        </a>
    </li>
    <?php } ?>
</ul>
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