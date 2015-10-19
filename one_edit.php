<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."func.php");
include_once("head.php");
$link = db_connect_web();
//data part
$club_flag = 0;
$article_id = $_GET["article_id"];

if (isset($_GET["board"])) {
    $club_flag = 0;
    $board_name = $_GET["board"];
} elseif (isset($_GET["club"])) {
    $club_flag = 1;
    $board_name = $_GET["club"];
}

if ($club_flag == 0) {
    $brdarr = array();
    $brdnum = bbs_getboard($board_name, $brdarr);
    $sql = "SELECT owner,groupid,article_id,title,filename,attachment FROM dir_article_".$brdarr["BOARD_ID"].
        " WHERE article_id=".$article_id;
    $ret = mysql_query($sql, $link);
    $row = mysql_fetch_array($ret);
    mysql_free_result($ret);
    if($row == false){
        mysql_free_result($ret);
        wap_error_quit("获取主题失败");
    }else{
        $art_arr = array();
        $att_arr = array();
        $content_arr = array();
        $title = preg_replace( '/\[[A-Z]{4}\]/', "", $row["title"]);
        $tmp = iconv("UTF-8", "GBK//IGNORE", $title);
        if ($tmp)
            $title = $tmp;
        $art_arr["title"] = $title;
        $art_arr["owner"] = $row["owner"];
        $art_arr["file"] = check_board_filename($board_name, $row["filename"]);
        $content_arr = get_file_content($art_arr["file"], $row["attachment"], $board_name, $row["article_id"], 3, $att_arr);

        $content = file(BBS_HOME."/{$art_arr["file"]}");
        for ($i = 4; $i < count($content); $i++) {
            // 文章结尾部分不读取
            if (preg_match('/^--/', $content[$i]) and preg_match('/※ 来源:·WWW 未名空间站/', $content[$i+2]))
                break;
            $art_arr["content"] = $art_arr["content"].$content[$i];
        }

        $tmp_content = iconv("UTF-8", "GBK//IGNORE", $art_arr["content"]);
        if (!empty($tmp_content))
            $art_arr["content"] = $tmp_content;
        $art_arr["article_id"]=$row["article_id"];
    }
} else {
    $clubarr = array();
    $club_id = bbs_getclub($club_name, $clubarr);

    $sql_table_id = $clubarr["CLUB_ID"]%256;
    if ($sql_table_id == 0)
        $sql_table_id = 256;
    $sql = "SELECT owner,groupid,article_id,title,filename,attachment FROM club_dir_article_".$sql_table_id.
        "WHERE article_id=".$article_id;
    echo $sql;
    $ret = mysql_query($sql, $link);
    $row = mysql_fetch_array($ret);
    mysql_free_result($ret);
    if ($row == false) {
        mysql_free_result($ret);
        wap_error_quit("获取主题失败");
    } else {
        $art_arr = array();
        $att_arr = array();
        $content_arr = array();
        $title = preg_replace( '/\[[A-Z]{4}\]/', "", $row["title"]);
        $tmp = iconv("UTF-8", "GBK//IGNORE", $title);
        if ($tmp)
            $title = $tmp;
        $art_arr["title"] = $title;
        $art_arr["owner"] = $row["owner"];
        $art_arr["file"] = check_board_filename($board_name, $row["filename"]);
        $content_arr = get_file_content($art_arr["file"], $row["attachment"], $board_name, $row["article_id"], 3, $att_arr);

        $content = file(BBS_HOME."/{$art_arr["file"]}");
        for ($i = 4; $i < count($content); $i++) {
            // 文章结尾部分不读取
            if (preg_match('/^--/', $content[$i]) and preg_match('/※ 来源:·WWW 未名空间站/', $content[$i+2]))
                break;
            $art_arr["content"] = $art_arr["content"].$content[$i];
        }

        $tmp_content = iconv("UTF-8", "GBK//IGNORE", $art_arr["content"]);
        if (!empty($tmp_content))
            $art_arr["content"] = $tmp_content;
        $art_arr["article_id"]=$row["article_id"];
    }
}
?>

<div class="ds_box border_bottom">
    <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    修改文章
</div>
<form class="newreply_conter" action="">
    <h4>标题:</h4>
    <input class="newreply_txt" type="text" value="<?php echo $art_arr["title"]; ?>" />
    <h4>正文:</h4>
    <textarea id="text_<?php echo $article_id; ?>" cols="30" rows="10"><?php echo $art_arr["content"]; ?></textarea>
    <input class="newreply_sub" type="submit" value="确认修改" onclick="return reply_submit();"/>
</form><!--End reply_conter-->

<script type="text/javascript">
    function reply_submit() {
        var board = "<?php echo $board_name; ?>";
        var article_id = "<?php echo $article_id; ?>";
        var curr_url = "<?php echo $curr_url; ?>";
        var title = "<?php echo "Re: ".$title; ?>";
        var currentuser = "<?php echo $currentuser["userid"]; ?>";
        if (currentuser == "guest") {
            document.cookie = "before_login="+curr_url;
            window.location = "login.php";
            return false;
        }

        var obj = document.getElementById("text_"+groupid);
        var content = obj.value;
        if (content.replace(/(^\s*)|(\s*$)/g, "").length < 10) {
            alert("评论内容不少于10个字!");
            return false;
        }

        post_article(board, title, groupid, "<?php echo $club_flag; ?>");
        document.location = "<?php echo $father_page; ?>";
        return false;
    }
</script>
<?php
include_once("foot.php");
mysql_close($link);
?>
