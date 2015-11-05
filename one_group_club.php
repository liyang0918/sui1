<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
$link = db_connect_web();

$user_id = $currentuser["userid"];
$user_num_id = $currentuser["num_id"];

//data part
$club_name = $_GET["club"];
$group_id = $_GET["group"];
if (isset($_GET["dingflag"]))
    $dingflag = $_GET["dingflag"];
else
    $dingflag = 0;

$url_page = url_generate(3, array("type"=>$_COOKIE["app_type"], "club"=>$club_name, "groupid"=>$group_id))."&page=";
$article_type = 1; //3是新闻
$clubarr = array();
$club_id = bbs_getclub($club_name, $clubarr);
$member_type = clubCheckMember($club_id, $user_num_id, $link);

$per_page=10;
$page = intval($_GET["page"]);
if(empty($page)){
    $page = 1;
}

if ($club_id == 0) {
    mysql_close($link);
    wap_error_quit("俱乐部不存在!");
}


/* 检查俱乐部的审批状态 */
if ($clubarr["CLUB_APPROVAL_STATE"] > 1) {
    wap_error_alert_quit("此俱乐部尚未通过审批,暂时无法访问！", array("db_link"=>$link));
}

/* 检查访问俱乐部的权限 */
if ($clubarr["CLUB_TYPE"] == 0) {
    if ($user_id == "guest") {
        wap_error_alert_quit("本俱乐部为私密俱乐部，仅限成员访问，请先登录您的帐号！", array("db_link"=>$link));
    }


    if ($member_type != 2) {
        wap_error_alert_quit("本俱乐部为私密俱乐部，仅限成员访问！", array("db_link"=>$link));
    }
}

$prt_arr = array();

$fav_flag = 0;
$check_result = check_if_fav($link, 2, $clubarr["CLUB_ID"], $group_id, 0, $clubarr["CLUB_NAME"], "", "");
if ($check_result && mysql_num_rows($check_result) > 0)
    $fav_flag = 1;

$sql_table_id = $clubarr["CLUB_ID"]%256;
if ($sql_table_id == 0)
    $sql_table_id = 256;
$sql = "SELECT owner_id,owner,groupid,article_id,title,posttime,total_reply,read_num,filename,attachment FROM club_dir_article_".$sql_table_id." ".
    "WHERE groupid=".$group_id." AND article_id=".$group_id;
$ret = mysql_query($sql, $link);
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
//    $row["title"] = htmlentities($row["title"]);
    $title = preg_replace( '/\[[a-zA-Z]{4}\]/', "", $row["title"]);
    $tmp = iconv("UTF-8", "GBK//IGNORE", $title);
    if ($tmp)
        $title = $tmp;
    $board_cname = $clubarr["CLUB_CNAME"];
    $reply_num = $row["total_reply"];
    $read_num = $row["read_num"];
    $board_link = "";
    if($page == 1) {
        $floor_cnt++;
        $tmp_arr["owner"] = $row["owner"];
        $tmp_arr["title"] = $title;
        $tmp_arr["posttime"] = $row["posttime"];
        $tmp_arr["floor"] = "楼主";
        $tmp_arr["img"] = get_user_img($row["owner"]);
        $tmp_arr["filename"] = $row["filename"];
        $tmp_arr["file"] = check_club_filename($club_name, $row["filename"]);
        $content_arr = get_file_content($tmp_arr["file"], $row["attachment"], $club_name, $row["article_id"], $article_type, $att_arr, 1);
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
$total_row = get_row_count($clubarr["CLUB_ID"], $group_id, $link, 2);
$total_page = intval($total_row/$per_page)+1;

//end page
$sql = "SELECT owner_id,owner,groupid,article_id,title,posttime,total_reply,read_num,filename,attachment  FROM club_dir_article_" .$sql_table_id. " ".
    "WHERE groupid=".$group_id." AND article_id<>".$group_id;
$order = " ORDER BY article_id";
$limit =
$sql .= $order;
$start = ($page-1)*$per_page;
if($page == 1){
    $per_page--;
    $limit = " limit $start,$per_page";
}else{
    $start--;
    $limit = " limit $start,$per_page";
}
$sql .= $limit;
$ret = mysql_query($sql,$link);

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
    $tmp_arr["filename"] = $row["filename"];
    $tmp_arr["file"] = check_club_filename($club_name,$row["filename"]);
    $content_arr = get_file_content($tmp_arr["file"],$row["attachment"],$club_name,$row["article_id"],$article_type,$att_arr, 1);
    $tmp_arr["content"] = trans_content_html($content_arr[1]);
    $tmp_content = iconv("UTF-8", "GBK//IGNORE", $tmp_arr["content"]);
    if (!empty($tmp_content))
        $tmp_arr["content"] = $tmp_content;
    $tmp_arr["attach"] = $att_arr;
    $tmp_arr["article_id"] = $row["article_id"];
    $tmp_arr["re_content"] = get_add_textarea_context($tmp_arr["file"],$tmp_arr["owner"]) ;
    $prt_arr[] = $tmp_arr;
    $floor_cnt++;
}
mysql_free_result($ret);
mysql_close($link);
$i_cnt = count($prt_arr);
//data end
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="return go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php echo $clubarr["CLUB_CNAME"]; ?>
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
                        <span class="theme_time"><?php echo $prt_arr[$i_loop]["posttime"];?></span>
                    </div>
                </div>
                <span class="news_position news_host"><?php echo $prt_arr[$i_loop]["floor"];?></span>
                <p id="re_content_<?php echo $prt_arr[$i_loop]["article_id"];?>" hidden="hidden"><?php echo $prt_arr[$i_loop]["re_content"];?></p>
                <p id="content_<?php echo $prt_arr[$i_loop]["article_id"];?>" class="theme_middle black_font"><?php echo $prt_arr[$i_loop]["content"];?></p>
                <div id="re_<?php echo $prt_arr[$i_loop]["article_id"];?>" class="news_reply">
                    <?php if ($user_id != "guest" and $user_id == $prt_arr[$i_loop]["owner"] and $member_type == 2) { ?>
                    <a href="one_edit.php?club=<?php echo $club_name; ?>&article_id=<?php echo $prt_arr[$i_loop]["article_id"]; ?>&groupid=<?php echo $group_id;?>&dingflag=<?php echo $dingflag; ?>">修改</a>
                    <?php } ?>
                    <a type="button" href="" onclick="return jump_to_write_reply('<?php echo url_generate(4, array(
                        "action" => "one_reply.php",
                        "args" => array(
                            "article_id"=>$prt_arr[$i_loop]["article_id"],
                            "group_id"=>$group_id,
                            "club"=>$club_name,
                            "title"=>$prt_arr[$i_loop]["title"],
                            "page"=>$page)
                    )); ?>');">回复</a>
                    <?php if ($user_id != "guest" and $user_id == $prt_arr[$i_loop]["owner"] and $member_type == 2) { ?>
                    <a class="cancel" href="javascript:;" onclick="return del('<?php echo $club_name; ?>', '<?php echo $group_id; ?>', '<?php echo $prt_arr[$i_loop]["article_id"]; ?>', '<?php echo $prt_arr[$i_loop]["filename"]; ?>', '<?php echo $dingflag; ?>', 1);">删除</a>
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
        <input type="button" value="写跟帖" onclick="return jump_to_write_reply('<?php
        echo url_generate(4, array(
            "action" => "one_reply.php",
            "args" => array(
                "article_id"=>$prt_arr[0]["article_id"],
                "group_id"=>$group_id,
                "club"=>$club_name,
                "title"=>$title,
                "page"=>$page
            )
        ));
        ?>')" />
        <span class="news_collect">
           <a id="fav_<?php echo $fav_flag; ?>" href="" onclick="return collect_by_type(2, this, '<?php echo $clubarr["CLUB_ID"];?>', '<?php echo $group_id; ?>', '<?php echo $title;?>')">
                <?php if ($fav_flag == 1) { ?>
                    <img id="collect_img" src="img/star.png" alt="star.png" hidden="hidden"/>
                    <span id="collect_span" >已收藏</span>
                <?php } else { ?>
                    <img id="collect_img" src="img/star.png" alt="star.png"/>
                    <span id="collect_span">收藏</span>
                <?php } ?>
            </a>
        </span>
    </div><!--  End news_foot-->


    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        function jump_to_write_reply(jumpto) {
            var user_id = "<?php echo $user_id; ?>";
            if (user_id == "guest") {
                window.location.href = "login.php";
                return false;
            }

            var flag = check_user_perm('<?php echo $member_type; ?>', '<?php echo $clubarr["CLUB_TYPE"]; ?>');
            if (flag) {
                window.location.href = jumpto;
            }

            return false;
        }

        function del(boardname, group_id, article_id, filename, dingflag, club_flag) {
            if (!confirm("你真的要删除本文吗?"))
                return false;

            var url = "/mobile/forum/request/del_article.php";
            var para = "boardname="+boardname+"&group_id="+group_id+"&article_id="+article_id+"&filename="+filename+"&dingflag="+dingflag+"&club_flag="+club_flag;

            var jumpto = "one_club.php?club=<?php echo $club_name; ?>";
            del_article(url, para, jumpto);
            return false;
        }

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
</div>
</body>
</html>
