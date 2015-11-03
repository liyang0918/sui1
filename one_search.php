<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
include_once("head.php");

$link = db_connect_web();
$hls_flag = false;   // 搜索关键字高亮显示
$high_light_show_style = "color:black;background-color:#ffff66;";

// 将str中的关键字keyword设置高亮
function setHighLightShow($str, $keyword) {
    global $hls_flag, $high_light_show_style;

    if ($hls_flag==false or empty($keyword))
        return $str;

    return preg_replace("/($keyword)/i", "<span style=\"$high_light_show_style\">$1</span>", $str);
}

function search_result_board($link, $sql, $keyword="") {
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

        // 高亮显示
        $tmp["enName"] = setHighLightShow($tmp["enName"], $keyword);
        $tmp["cnName"] = setHighLightShow($tmp["cnName"], $keyword);

        $ret[] = $tmp;
    }

    mysql_free_result($result);
    return $ret;
}

function search_result_club($link, $sql, $keyword="") {
    $ret = array();
    $result = mysql_query($sql, $link);
    while ($row = mysql_fetch_array($result)) {
        $tmp["enName"] = $row["enName"];
        $tmp["cnName"] = $row["cnName"];
        $tmp["total_member"] = $row["member_sum"];
        $tmp["total_article"] = $row["post_sum"];
        $tmp["clubimg"] = getClubImg($row["enName"]);
        $tmp["href"] = url_generate(2, array("club"=>$row["enName"]));

        // 高亮显示
        $tmp["enName"] = setHighLightShow($tmp["enName"], $keyword);
        $tmp["cnName"] = setHighLightShow($tmp["cnName"], $keyword);

        $ret[] = $tmp;
    }

    mysql_free_result($result);
    return $ret;
}

function search_result_member($link, $sql, $keyword="") {
    $ret = array();
    $result = mysql_query($sql, $link);
    while ($row = mysql_fetch_array($result)) {
        $tmp["headimg"] = get_user_img($row["enName"]);
        $tmp["enName"] = $row["enName"];
        $tmp["cnName"] = $row["cnName"];
        $tmp["href"] = "memberinfo.php?userid=".$tmp["enName"];

        // 高亮显示
        $tmp["enName"] = setHighLightShow($tmp["enName"], $keyword);
        $tmp["cnName"] = setHighLightShow($tmp["cnName"], $keyword);

        $ret[] = $tmp;
    }

    mysql_free_result($result);
    return $ret;
}

//data part
$search_type_list = array(
    "board"=>"版面",
    "club"=>"俱乐部",
    "member"=>"会员"
);

$search_type = ""; // board 搜版面   club 搜俱乐部   member 搜会员
$search_name = "";
if (isset($_GET["board"])) {
    $search_type = "board";
    $search_name = strip_tags($_GET["board"]);
} else if (isset($_GET["club"])) {
    $search_type = "club";
    $search_name = strip_tags($_GET["club"]);
} else if (isset($_GET["member"])) {
    $search_type = "member";
    $search_name = strip_tags($_GET["member"]);
}

// 防注入,遇到【;,'"】直接截断
$search_name = mbstr_split($search_name, ";,'\"");

// 模糊查询标志: 1开启 0关闭
$fuzzy = 1;
if (isset($_GET["fuzzy"])) {
    $fuzzy = $_GET["fuzzy"]==0?0:1;
}

if ($search_name == "")
    return false;

$url_page = url_generate(4, array(
    "action" => "one_search.php",
    "args"=>array($search_type=>$search_name)
))."&page=";

$per_page=10;
$page = intval($_GET["page"]);
if(empty($page)){
    $page = 1;
}

if ($fuzzy == 1) {
    if ($search_type == "board") { // 搜版面
        $sql = "SELECT board_id,boardname AS enName,board_desc AS cnName FROM board";
        $condition = " WHERE boardname LIKE '%{$search_name}%' OR board_desc LIKE BINARY '%{$search_name}%'";
        $sql .= $condition;
        $sql_count = "SELECT COUNT(*) AS count FROM board $condition";
    } elseif ($search_type == "club") { // 搜俱乐部
        $sql = "SELECT club_id,club_group_id,club_name AS enName,club_cname AS cnName,post_sum,member_sum FROM club";
        $condition = " WHERE club_name LIKE '%{$search_name}%' OR club_cname LIKE BINARY '%{$search_name}%'";
        $sql .= $condition;
        $sql_count = "SELECT COUNT(*) AS count FROM club $condition";
    } elseif ($search_type == "member") { // 搜会员
        $sql = "SELECT user_id AS enName,username AS cnName FROM users";
        $condition = " WHERE user_id LIKE '%{$search_name}%' OR username LIKE BINARY '%{$search_name}%'";
        $sql .= $condition;
        $sql_count = "SELECT COUNT(*) AS count FROM users $condition";
    } else {
        return false;
    }
} else {
    if ($search_type == "board") {

    } elseif ($search_type == "club") {

    } elseif ($search_type == "member") {
        $sql = "SELECT user_id AS enName,username AS cnName FROM users WHERE user_id='$search_name'";
    } else {
        return false;
    }
}
if ($fuzzy == 1) {
    $result = mysql_query($sql_count, $link);
    $total_row = mysql_fetch_array($result)["count"];
    mysql_free_result($result);
} else {
    $total_row = 1;
}

$order = " ORDER BY enName";
$sql .= $order;

$limit = "";

$start = ($page-1)*$per_page;
$limit = " limit $start,$per_page";

$sql .= $limit;
//data end
log2file($sql);
?>
<div class="ds_box border_bottom">
    <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    <?php echo $search_type_list[$search_type]; ?>搜索结果 <span>【<?php echo $search_name; ?>】</span>
</div>
<?php if ($search_type == "board") { ?>
<div class="hot_li">
    <ul class="hot_list_wrap border_bottom block">
<?php
if ($total_row == 0)
    echo '<h3 class="search_msg">没有找到相匹配的结果</h3>';
$t_data = search_result_board($link, $sql, $search_name);
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
} elseif ($search_type == "club") {
?>
<div class="hot_li">
    <ul class="hot_list_wrap border_bottom block">
<?php
if ($total_row == 0)
    echo '<h3 class="search_msg">没有找到相匹配的结果</h3>';
$t_data = search_result_club($link, $sql, $search_name);
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
} elseif ($search_type == "member") {
?>
<ul class="jy_f_group">
<?php
if ($total_row == 0)
    echo '<h3 class="search_msg">没有找到相匹配的结果</h3>';
$t_data = search_result_member($link, $sql, $search_name);
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
