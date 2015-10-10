<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
//data part
if (isset($_GET["board"])) {
    $board_name = $_GET["board"];
    $article_type = "board";
} elseif (isset($_GET["club"])) {
    $board_name = $_GET["club"];
    $article_type = "club";
} else {
    return false;
}

$article_id = $_GET["article_id"];

$infoarr = array();
if ($article_type == "board")
    $infonum = bbs_getboard($board_name, $infoarr);
else
    $infonum = bbs_getclub($board_name, $infoarr);

if ($infonum == 0) {
    wap_error_quit("不存在的版面");
}
$prt_arr = array();
$link = db_connect_web();
if ($article_type == "board") {
    $sql = "SELECT owner,groupid,article_id,boardname,title,posttime,total_reply,read_num,filename,attachment FROM dir_article_" . $infoarr["BOARD_ID"] . " ".
        "WHERE article_id=".$article_id;
} else {
    $table_id = $infoarr["CLUB_ID"]%256;
    if ($table_id == 0)
        $table_id = 256;

    $sql = "SELECT owner,groupid,article_id,title,posttime,total_reply,read_num,filename,attachment FROM club_dir_article_".$table_id." ".
        "WHERE article_id=".$article_id;
}
$ret = mysql_query($sql,$link);
$row = mysql_fetch_array($ret);
mysql_free_result($ret);
if($row == false){
    mysql_free_result($ret);
    wap_error_quit("获取主题失败");
}else{
    $tmp_arr = array();
    $att_arr = array();
    $content_arr = array();
    $group_id =$row["groupid"];
    $title = preg_replace( '/\[[A-Z]{4}\]/', "", $row["title"]);
    if ($article_type == "board")
        $board_cname = $infoarr["DESC"];
    else
        $board_cname = $infoarr["CLUB_CNAME"];
    $reply_num = $row["total_reply"];
    $read_num = $row["read_num"];
    $board_link = "";

    $tmp_arr["owner"] = $row["owner"];
    $tmp_arr["posttime"] = $row["posttime"];
    $tmp_arr["floor"] = "楼主";
    $tmp_arr["img"] = get_user_img($row["owner"]);
    $tmp_arr["file"] = check_board_filename($board_name, $row["filename"]);
    $content_arr = get_file_content($tmp_arr["file"], $row["attachment"], $board_name, $row["article_id"], 1, $att_arr);

    $tmp_arr["content"] = trans_content_html($content_arr[1]);
    $tmp_content = iconv("UTF-8", "GBK//IGNORE", $tmp_arr["content"]);
    if (!empty($tmp_content))
        $tmp_arr["content"] = $tmp_content;
    $tmp_arr["attach"] = $att_arr;
    $tmp_arr["article_id"]=$row["article_id"];
    $prt_arr[] = $tmp_arr;
}

mysql_free_result($ret);
mysql_close($link);
$i_cnt = count($prt_arr);
//data end
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        文章
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
                <p id="content_<?php echo $prt_arr[$i_loop]["article_id"];?>"class="theme_middle black_font"><?php echo $prt_arr[$i_loop]["content"];?></p>
                <div id="re_<?php echo $prt_arr[$i_loop]["article_id"];?>" class="news_reply">
                    <a type="button" href="<?php echo $reply_href = url_generate(4, array(
                        "action" => "one_reply.php",
                        "args" => array(
                            "article_id"=>$prt_arr[$i_loop]["article_id"],
                            "group_id"=>$group_id,
                            "board"=>$board_name,
                            "title"=>$title,
                            "page"=>1)
                    )); ?>">回复</a>
                </div>
            </li>
            <?php }?>
        </ul>
    </div>

    <script type="text/javascript" src="js/jquery.js"></script>

</div>
</body>
</html>
