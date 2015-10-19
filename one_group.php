<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
//data part
$board_name = $_GET["board"];
$group_id = $_GET["group"];
$user_id = $currentuser["userid"];

$curr_url = url_generate(3, array("type"=>$_COOKIE["app_type"], "board"=>$board_name, "groupid"=>$group_id));
$url_page = $curr_url."&page=";
$article_type = 1; //3是新闻
$brdarr = array();
$brdnum = bbs_getboard($board_name, $brdarr);

$per_page=10;
$page = intval($_GET["page"]);
if(empty($page)){
    $page = 1;
}
if ($brdnum == 0) {
    if ($num == 0) wap_error_quit("不存在的版面");
}
$prt_arr = array();
$conn = db_connect_web();
$sql = "SELECT owner_id,owner,groupid,article_id,boardname,title,type_flag,posttime,total_reply,read_num,filename,attachment FROM dir_article_" . $brdarr["BOARD_ID"] . " ".
    "WHERE groupid=".$group_id." AND article_id=".$group_id;
$ret = mysql_query($sql,$conn);
$floor_cnt = 1;
$row = mysql_fetch_array($ret);
mysql_free_result($ret);
if($row == false){
    mysql_free_result($ret);
    wap_error_quit("获取主题失败");
}else{
    $tmp_arr = array();
    $att_arr = array();
    $content_arr = array();
    $title = preg_replace( '/\[[A-Z]{4}\]/', "", $row["title"]);
    $tmp = iconv("UTF-8", "GBK//IGNORE", $title);
    if ($tmp)
        $title = $tmp;
    $board_cname = $brdarr["DESC"];
    $reply_num = $row["total_reply"];
    $read_num = $row["read_num"];
    $board_link = "";
    if($page == 1) {
        $floor_cnt++;
        $tmp_arr["owner"] = $row["owner"];
        $tmp_arr["posttime"] = $row["posttime"];
        $tmp_arr["floor"] = "楼主";
        $tmp_arr["img"] = get_user_img($row["owner"]);
        $tmp_arr["file"] = check_board_filename($board_name, $row["filename"]);
        $content_arr = get_file_content($tmp_arr["file"], $row["attachment"], $board_name, $row["article_id"], $article_type, $att_arr);

        $tmp_arr["content"] = trans_content_html($content_arr[1]);
        $tmp_content = iconv("UTF-8", "GBK//IGNORE", $tmp_arr["content"]);
        if (!empty($tmp_content))
            $tmp_arr["content"] = $tmp_content;
        $tmp_arr["attach"] = $att_arr;
        $tmp_arr["article_id"]=$row["article_id"];
        $prt_arr[] = $tmp_arr;
    }
}

//page part
$total_row = get_row_count($brdarr["BOARD_ID"],$group_id,$conn);
$total_page = intval($total_row/$per_page)+1;

//end page
$sql = "SELECT owner_id,owner,groupid,article_id,boardname,title,type_flag,posttime,total_reply,read_num,filename,attachment  FROM dir_article_" . $brdarr["BOARD_ID"] . " ".
    "WHERE groupid=".$group_id." AND article_id<>".$group_id;
$order = " ORDER BY article_id";
$limit =
$sql .= $order;
$start = ($page-1)*$per_page;
if($page == 1){
    $per_page--;
    $limit = " limit $start,$per_page";
}else{
    $limit = " limit $start,$per_page";
}
$sql .= $limit;
$ret = mysql_query($sql,$conn);
while ($row = mysql_fetch_array($ret)) {
    //if more than one article
    $content_arr = array();
    $tmp_arr = array();
    $tmp_arr["title"] = preg_replace( '/\[[A-Z]{4}\]/', "", $row["title"]);
    $tmp = iconv("UTF-8", "GBK//IGNORE", $tmp_arr["title"]);
    if ($tmp)
        $tmp_arr["title"] = $tmp;
    $tmp_arr["owner"] = $row["owner"];
    $tmp_arr["img"] = get_user_img($row["owner"]);
    $tmp_arr["posttime"] = $row["posttime"];
    $tmp_arr["floor"] = ($floor_cnt+($page-1)*$per_page)."楼";
    $tmp_arr["file"] = check_board_filename($board_name,$row["filename"]);
    $content_arr = get_file_content($tmp_arr["file"],$row["attachment"],$board_name,$row["article_id"],$article_type,$att_arr);

    $tmp_arr["content"] = trans_content_html($content_arr[1]);
    $tmp_content = iconv("UTF-8", "GBK//IGNORE", $tmp_arr["content"]);
    if (!empty($tmp_content))
        $tmp_arr["content"] = $tmp_content;
    $tmp_arr["attach"] = $att_arr;
    $tmp_arr["article_id"] = $row["article_id"];
    $tmp_arr["re_content"] = get_add_textarea_context($tmp_arr["file"],$tmp_arr["owner"]);
    $prt_arr[] = $tmp_arr;
    $floor_cnt++;
}
mysql_free_result($ret);
mysql_close($conn);
$i_cnt = count($prt_arr);
//data end
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php echo $brdarr["DESC"]; ?>
    </div>
    <div class="theme_wrap">
        <div class="news_title">
            <p><?php echo $title;?></p>
            <span><?php echo $board_cname;?></span>
            <em><?php echo $reply_num."/".$read_num;?></em>
        </div>
        <ul class="news_conter">
            <?php for($i_loop=0;$i_loop<$i_cnt;$i_loop++) {
            ?>
            <li  class="news_li" name="abc">
                <div class="theme_up">
                    <img class="theme_small" src="<?php echo $prt_arr[$i_loop]["img"];?>" alt="theme_pic"/>
                    <div class="theme_right fc_span">
                        <h4><?php echo $prt_arr[$i_loop]["owner"];?></h4>
                        <span class="theme_time"><?php echo date("Y-m-d", strtotime($prt_arr[$i_loop]["posttime"]));?>&nbsp;&nbsp;<?php echo date("H:i", strtotime($prt_arr[$i_loop]["posttime"]));?></span>
                    </div>
                </div>
                <span class="news_position news_host"><?php echo $prt_arr[$i_loop]["floor"];?></span>
                <p id="re_content_<?php echo $prt_arr[$i_loop]["article_id"];?>" hidden="hidden"><?php echo $prt_arr[$i_loop]["re_content"];?></p>
                <p id="content_<?php echo $prt_arr[$i_loop]["article_id"];?>" class="theme_middle black_font"><?php echo $prt_arr[$i_loop]["content"];?></p>
                <div id="re_<?php echo $prt_arr[$i_loop]["article_id"];?>" class="news_reply">
                    <?php if ($user_id != "guest" and $user_id == $prt_arr[$i_loop]["owner"]) { ?>
                    <a href="one_edit.php?board=<?php echo $board_name; ?>&article_id=<?php echo $prt_arr[$i_loop]["article_id"]; ?>">修改</a>
                    <?php } ?>
                    <a type="button" href="<?php echo $reply_href = url_generate(4, array(
                        "action" => "one_reply.php",
                        "args" => array(
                            "article_id"=>$prt_arr[$i_loop]["article_id"],
                            "group_id"=>$group_id,
                            "board"=>$board_name,
                            "title"=>$prt_arr[$i_loop]["title"],
                            "page"=>$page)
                    )); ?>">回复</a>
                    <?php if ($user_id != "guest" and $user_id == $prt_arr[$i_loop]["owner"]) { ?>
                    <a class="cancel" href="javascript:;">删除</a>
                    <?php } ?>
                </div>
            </li>
            <?php }?>
        </ul>
    </div>
<?php
    // 分页显示
    echo page_partition($total_row, $page, $per_page);
?>
<br><br><br><br>
    <div class="news_foot">
        <input type="button" value="写跟帖" onclick="document.location='<?php
        echo url_generate(4, array(
            "action" => "one_reply.php",
            "args" => array(
                "article_id"=>$prt_arr[0]["article_id"],
                "group_id"=>$group_id,
                "board"=>$board_name,
                "title"=>$title,
                "page"=>$page
            )
        ));
        ?>'" />
        <span class="news_share"><img src="img/share.png" alt="share.png"/>分享</span>
        <span class="news_collect"><img src="img/star.png" alt="star.png"/>收藏</span>
    </div><!--  End news_foot-->
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
</div>
</body>
</html>
