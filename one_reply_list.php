<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."func.php");
include_once("head.php");
$link = db_connect_web();

//data part
$board_name = $_GET["board"];
$group_id = $_GET["group"];
$url_page = url_generate(4, array(
        "action" => "one_reply_list.php",
        "args" => array("board"=>$board_name, "group"=>$group_id)
    ))."&page=";
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

//page part
$total_row = get_row_count($brdarr["BOARD_ID"],$group_id,$link);
$total_page = intval($total_row/$per_page)+1;

//end page
$sql = "SELECT owner_id,owner,groupid,article_id,boardname,title,type_flag,UNIX_TIMESTAMP(posttime) as posttime,total_reply,read_num,filename,attachment  FROM dir_article_" . $brdarr["BOARD_ID"] . " ".
    "WHERE groupid=".$group_id." AND article_id<>".$group_id;
$order = " ORDER BY posttime";
$limit = "";
$sql .= $order;
$start = ($page-1)*$per_page;
if($page == 1){
    $per_page--;
    $limit = " limit $start,$per_page";
}else{
    $limit = " limit $start,$per_page";
}
$sql .= $limit;
$ret = mysql_query($sql,$link);
while ($row = mysql_fetch_array($ret)) {
    //if more than one article
    $content_arr = array();
    $tmp_arr = array();
    $tmp_arr["owner"] = $row["owner"];
    $tmp_arr["img"] = get_user_img($row["owner"]);
    $tmp_arr["posttime"] = strftime("%Y-%m-%d&nbsp;&nbsp;%H:%M", $row["posttime"]);
    $tmp_arr["file"] = check_board_filename($board_name,$row["filename"]);
    $content_arr = get_file_content($tmp_arr["file"],$row["attachment"],$board_name,$row["article_id"],$article_type,$att_arr);
    $tmp_arr["content"] = trans_content_html($content_arr[1]);
    $tmp_content = iconv("UTF-8", "GBK//IGNORE", $tmp_arr["content"]);
    if (!empty($tmp_content))
        $tmp_arr["content"] = $tmp_content;
    $tmp_arr["attach"] = $att_arr;
    $tmp_arr["article_id"] = $row["article_id"];
    $tmp_arr["re_content"] = get_add_textarea_context($tmp_arr["file"],$tmp_arr["owner"]) ;
    $prt_arr[] = $tmp_arr;
}
mysql_free_result($ret);
mysql_close($link);
$i_cnt = count($prt_arr);
//data end
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        评论列表
    </div>
    <div>
        <ul class="news_content_detail_ul">
            <?php for($i_loop=0;$i_loop<$i_cnt;$i_loop++) {
                if ($i_loop != $i_cnt-1) {
            ?>
                <li class="theme_li noborder_bottom no_margin-bottom">
            <?php } else { ?>
                <li class="theme_li no_margin-bottom">
            <?php } ?>
                <div class="theme_up">
                    <img class="theme_small" src="<?php echo $prt_arr[$i_loop]["img"];?>" alt="theme_pic"/>
                    <div class="theme_right">
                        <h4><?php echo $prt_arr[$i_loop]["owner"];?></h4>
                        <span><?php echo $prt_arr[$i_loop]["posttime"];?></span>
                    </div>
                </div>
                <p id="content_<?php echo $prt_arr[$i_loop]["article_id"];?>"class="theme_middle no_margin-bottom"><?php echo $prt_arr[$i_loop]["content"];?></p>
            </li>
            <?php }?>
        </ul>
    </div>
<?php
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
?>
