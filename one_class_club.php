<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
$link = db_connect_web();
//data part
$class = $_GET["club_class"];
$group_id = intval($club_class_list[$class]["id"]);
$group_name = $club_class_list[$class]["name"];
if ($group_id < 1 or ($group_id > 15 && $group_id != 100))
    return;
$page = intval($_GET["page"]);
if(empty($page)){
    $page=1;
}
$url_page = url_generate(1, array("type"=>$_COOKIE["app_type"], "club_class"=>$class))."&page=";

function getClubList($link, $group_id, $pagen, $pagesize)
{
    global $china_flag;
    global $dir_modes;
    $china_flag = is_china();

    $group_id = intval($group_id);

    //计算俱乐部总数
    $sqlcount="select count(1) count_num from club where approval_state<=1 and club_group_id={$group_id}";
    if ($china_flag == 1)
        $sqlcount .= " and limit_flag=0 ";
    $count_result = mysql_query($sqlcount, $link);
    $count_num = 0;
    if (mysql_num_rows($count_result) > 0) {
        if ($row = mysql_fetch_array($count_result))
            $count_num = $row['count_num'];
    }
    mysql_free_result($count_result);

    //从数据库取出数据
    $offset = ($pagen - 1) * $pagesize;

    $order_by = " order by a.onlines desc ";
    $use_index = " use index(onlines_index) ";

    $sqlstr = "select a.club_id club_id,a.club_name club_name,a.club_cname club_cname,a.club_description club_description,a.modifytime modifytime,a.member_sum member_sum,a.post_sum post_sum,a.join_way join_way,a.club_type club_type,'" . $this_group['group_name'] . "' group_name,c.user_id user_id,c.numeral_user_id numeral_user_id, a.enterprise_id,a.onlines,a.approval_state from club a {$use_index},users c where a.club_group_id={$group_id} and a.approval_state<=1 and a.managerid=c.numeral_user_id ";
    if ($china_flag == 1)
        $sqlstr .= " and limit_flag=0 ";
    $sqlstr = $sqlstr . $order_by . " limit {$pagesize} offset {$offset}";
    $club_result = mysql_query($sqlstr, $link);
    $ret = array();

    while ($club_result && $clubrow = mysql_fetch_array($club_result)) {
        $club = array("clubId" => $clubrow["club_id"],
            "name" => $clubrow["club_name"],
            "cnName" => $clubrow["club_cname"],
            "description" => $clubrow["club_description"],
            "modifytime" => "".strtotime($clubrow["modifytime"]),
            "postSum" => $clubrow["post_sum"],
            "totalarticle" => $clubrow["post_sum"],
            "memberSum" => $clubrow["member_sum"],
            "joinWay" => $clubrow["join_way"],
            "club_type" => $clubrow["club_type"],
            "managerUserNumId" => $clubrow["numeral_user_id"],
            "onlines" => $clubrow["onlines"],
            "club_approval_state"=>$clubrow["approval_state"],
            "clubimg"=>getClubImg($clubrow["club_name"]),
            "href"=>url_generate(2, array("club"=>$clubrow["club_name"])),
        );

        $ret[] = $club;
    }

    return array($ret, $count_num);
}

/* 每页版面数 */
$pagesize = 10;
/* 起始页 */
$start_num = $pagesize*($page-1)+1;
$totalclub = 0;
list($clubs, $totalclub) = getClubList($link, $group_id, $page, $pagesize);
?>
<div class="hot_li">
    <h3 class="content_h3 border_bottom"><?php echo $group_name; ?>俱乐部<span>（<?php echo $totalclub; ?>）</span></h3>
    <ul class="club_list_wrap border_bottom block">
<?php
    $count = count($clubs);
    foreach ($clubs as $i=>$each) {
        if($i < $count-1)
            echo '<li class="content_list_wrap padding10 border_bottom padding-bottom board_link">';
        else
            echo '<li class="content_list_wrap padding10  padding-bottom board_link">';

        echo '<a href="'.$each["href"].'">';
        echo '<img class="hot_li_img" src="'.$each["clubimg"].'" alt="club.png" />';
        echo '<div class="hot_content">';
        echo '<h3 class="hot_name">'.$each["cnName"].'</h3>';
        echo '<p class="hot_des">'.$each["description"].'</p>';
        echo '<p class="hot_count"><span class="hot_left">成员人数: '.$each["memberSum"].'</span><span class="hot_right">文章总数: '.$each["totalarticle"].'</span></p>';
        echo '</div></a></li>';
    }
?>
    </ul>
</div>
<?php
    // 文章分页显示
    echo page_partition($totalclub, $page, $pagesize);
?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var page = <?php echo $page;?>;

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
mysql_close($link);
include_once("foot.php");
?>
