<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
include_once("head.php");

$link = db_connect_web();

function search_result_board($link, $sql) {
    $ret = array();
    $result = mysql_query($sql, $link);
    while ($row = mysql_fetch_array($result)) {
        $brdarr = array();
        $brdnum = bbs_getboard($row["enName"], $brdarr);
        $tmp["online"] = $brdarr["CURRENTUSERS"];
        $tmp["enName"] = $row["enName"];
        $tmp["cnName"] = $brdarr["DESC"];
        $tmp["total_article"] = getBoardGroupNum($row["board_id"], $link);
        $tmp["href"] = url_generate(2, array("board"=>$row["enName"]));
        $tmp["boardimg"] = getBoardImg($row["enName"]);

        $ret[] = $tmp;
    }

    mysql_free_result($result);
    return $ret;
}

function search_result_club($link, $sql) {
    $ret = array();
    $result = mysql_query($sql, $link);
    while ($row = mysql_fetch_array($result)) {
        $tmp["enName"] = $row["enName"];
        $tmp["cnName"] = $row["cnName"];
        $tmp["total_member"] = $row["member_sum"];
        $tmp["total_article"] = $row["post_sum"];
        $tmp["clubimg"] = getClubImg($row["enName"]);
        $tmp["href"] = url_generate(2, array("club"=>$row["enName"]));

        $ret[] = $tmp;
    }

    mysql_free_result($result);
    return $ret;
}

function search_result_member($link, $sql) {
    $ret = array();
    $result = mysql_query($sql, $link);
    while ($row = mysql_fetch_array($result)) {
        $tmp["href"] = "";
        $tmp["headimg"] = get_user_img($row["enName"]);
        $tmp["enName"] = $row["enName"];
        $tmp["cnName"] = $row["cnName"];

        $ret[] = $tmp;
    }

    mysql_free_result($result);
    return $ret;
}

//data part
$search_type_list = array(
    "0" => "board",
    "1" => "club",
    "2" => "member"
);

$search_type = 0; // 0 搜版面   1 搜俱乐部   2 搜会员
$search_name = "";
if (isset($_GET["board"])) {
    $search_type = 0;
    $search_name = $_GET["board"];
} else if (isset($_GET["club"])) {
    $search_type = 1;
    $search_name = $_GET["club"];
} else if (isset($_GET["member"])) {
    $search_type = 2;
    $search_name = $_GET["member"];
}

if ($search_name == "")
    return false;

$url_page = url_generate(4, array(
    "action" => "one_search.php",
    "args"=>array($search_type_list[$search_type]=>$search_name)
))."&page=";

$per_page=10;
$page = intval($_GET["page"]);
if(empty($page)){
    $page = 1;
}

if ($search_type == 0) { // 搜版面
    $sql = "select board_id,boardname as enName,board_desc as cnName from board";
    $condition = " WHERE boardname LIKE '%{$search_name}%' OR board_desc LIKE '%{$search_name}%'";
    $sql .= $condition;
    $sql_count = "select count(*) as count from board $condition";
} elseif ($search_type == 1) { // 搜俱乐部
    $sql = "select club_id,club_group_id,club_name as enName,club_cname as cnName,post_sum,member_sum from club";
    $condition = " WHERE club_name LIKE '%{$search_name}%' OR club_cname LIKE '%{$search_name}%'";
    $sql .= $condition;
    $sql_count = "select count(*) as count from club $condition";
} elseif ($search_type == 2) { // 搜会员
    $sql = "select user_id as enName,username as cnName from users";
    $condition = " WHERE user_id LIKE '%{$search_name}%' OR username LIKE '%{$search_name}%'";
    $sql .= $condition;
    $sql_count = "select count(*) as count from users $condition";
} else {
    return false;
}
$result = mysql_query($sql_count, $link);
$total_row = mysql_fetch_array($result)["count"];
mysql_free_result($result);

$order = " ORDER BY enName";
$sql .= $order;

$limit = "";

$start = ($page-1)*$per_page;
if($page == 1){
    $per_page--;
    $limit = " limit $start,$per_page";
}else{
    $limit = " limit $start,$per_page";
}
$sql .= $limit;

//data end
?>
<div class="ds_box border_bottom">
    <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    搜索结果列表
</div>
<?php if ($search_type == 0) { ?>
<div class="hot_li">
    <ul class="hot_list_wrap border_bottom block">
<?php
if ($total_row == 0)
    echo '<h3><span>没有找到相匹配的结果</span></h3>';
$t_data = search_result_board($link, $sql);
foreach ($t_data as $each) {
?>
        <li class="content_list_wrap padding10 border_bottom padding-bottom board_link">
            <a href="<?php echo $each["href"]; ?>">
                <img class="hot_li_img" src="<?php echo $each["boardimg"]; ?>" alt="img"/>
                <div class="hot_content">
                    <h3 class="hot_name"><?php echo $each["cnName"]; ?></h3>
                    <p class="hot_des"><?php echo $each["enName"]; ?></p>
                    <p class="hot_count"><span class="hot_left">当前在线：<?php echo $each["online"]; ?>人</span><span class="hot_right">文章总数：<?php echo $each["total_article"]; ?></span></p>
                </div>
            </a>
        </li>
<?php } ?>
    </ul>
</div>
<?php
} elseif ($search_type == 1) {
?>
<div class="hot_li">
    <ul class="hot_list_wrap border_bottom block">
<?php
if ($total_row == 0)
    echo '<h3><span>没有找到相匹配的结果</span></h3>';
$t_data = search_result_club($link, $sql);
foreach ($t_data as $each) {
?>
        <li class="content_list_wrap padding10 border_bottom padding-bottom board_link">
            <a href="<?php echo $each["href"]; ?>">
                <img class="hot_li_img" src="<?php echo $each["clubimg"]; ?>" alt="img"/>
                <div class="hot_content">
                    <h3 class="hot_name"><?php echo $each["cnName"]; ?></h3>
                    <p class="hot_des"><?php echo $each["enName"]; ?></p>
                    <p class="hot_count"><span class="hot_left">成员总数：<?php echo $each["total_member"]; ?>人</span><span class="hot_right">文章总数：<?php echo $each["total_article"]; ?></span></p>
                </div>
            </a>
        </li>
<?php } ?>
    </ul>
</div>
<?php
} elseif ($search_type == 2) {
?>
<ul class="jy_f_group">
<?php
if ($total_row == 0)
    echo '<h3><span>没有找到相匹配的结果</span></h3>';
$t_data = search_result_member($link, $sql);
foreach ($t_data as $each) {
?>
    <li class="border_bottom">
        <a href="<?php echo $each["href"]; ?>">
            <img src="<?php echo $each["headimg"]; ?>" alt="headimg"/>
            <span><?php echo $each["enName"]; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo $each["cnName"]; ?></span>
        </a>
    </li>
<?php
    }
?>
</ul>
<?php
}
// 分页显示
echo page_partition($total_row, $page, $per_page);
?>

<head>
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
</head>
<?php
include_once("foot.php");
mysql_close($link);
?>
