<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."func.php");
include_once("head.php");
$link = db_connect_web();
//data part
$club_flag = 0;
$article_id = $_GET["article_id"];
$group_id = $_GET["groupid"];
if (isset($_GET["dingflag"]))
    $dingflag = $_GET["dingflag"];
else
    $dingflag = 0;

if ($dingflag == 1)
    $mode = $dir_modes["ZHIDING"];
else
    $mode = $dir_modes["ORIGIN"];

$curr_url = $_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];

if (isset($_GET["board"])) {
    $club_flag = 0;
    $board_name = $_GET["board"];
    $father_page = url_generate(3, array("type"=>$_COOKIE["app_type"], "board"=>$board_name, "groupid"=>$group_id));
} elseif (isset($_GET["club"])) {
    $club_flag = 1;
    $board_name = $_GET["club"];
    $father_page = url_generate(3, array("type"=>$_COOKIE["app_type"], "club"=>$board_name, "groupid"=>$group_id));
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
        $title = preg_replace( '/\[[A-Z]{4}\]/', "", $row["title"]);
        $tmp = iconv("UTF-8", "GBK//IGNORE", $title);
        if ($tmp)
            $title = $tmp;
        $art_arr["title"] = $title;
        $art_arr["owner"] = $row["owner"];
        $art_arr["filename"] = $row["filename"];
        $art_arr["file"] = check_board_filename($board_name, $row["filename"]);

        $content = file(BBS_HOME."/{$art_arr["file"]}");
        for ($i = 4; $i < count($content); $i++) {
            $content_tmp = iconv("UTF-8", "GBK//IGNORE", $content[$title]);
            if ($content_tmp)
                $content[$i] = $content_tmp;
            // 文章结尾部分不读取
            if (preg_match('/^--/', $content[$i]) and
                (preg_match('/※ 来源:·WWW 未名空间站/', $content[$i+1]) or preg_match('/※ 来源:·WWW 未名空间站/', $content[$i+2]) ))
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
    $club_id = bbs_getclub($board_name, $clubarr);

    $sql_table_id = $clubarr["CLUB_ID"]%256;
    if ($sql_table_id == 0)
        $sql_table_id = 256;
    $sql = "SELECT owner,groupid,article_id,title,filename,attachment FROM club_dir_article_".$sql_table_id.
        " WHERE article_id=".$article_id;
    $ret = mysql_query($sql, $link);
    $row = mysql_fetch_array($ret);
    mysql_free_result($ret);
    if ($row == false) {
        mysql_free_result($ret);
        wap_error_quit("获取主题失败");
    } else {
        $art_arr = array();
        $att_arr = array();
        $title = preg_replace( '/\[[A-Z]{4}\]/', "", $row["title"]);
        $tmp = iconv("UTF-8", "GBK//IGNORE", $title);
        if ($tmp)
            $title = $tmp;
        $art_arr["title"] = $title;
        $art_arr["owner"] = $row["owner"];
        $art_arr["filename"] = $row["filename"];
        $art_arr["file"] = check_club_filename($board_name, $row["filename"]);

        $content = file(BBS_HOME."/{$art_arr["file"]}");
        for ($i = 4; $i < count($content); $i++) {
            $content_tmp = iconv("UTF-8", "GBK//IGNORE", $content[$title]);
            if ($content_tmp)
                $content[$i] = $content_tmp;
            // 文章结尾部分不读取
            if (preg_match('/^--/', $content[$i]) and
                (preg_match('/※ 来源:·WWW 未名空间站/', $content[$i+1]) or preg_match('/※ 来源:·WWW 未名空间站/', $content[$i+2]) ))
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
<form class="newreply_conter">
    <h4>标题:</h4>
    <?php if ($article_id == $group_id) { ?>
    <input id="art_title" class="newreply_txt" type="text" value="<?php echo $art_arr["title"]; ?>" />
    <?php } else { ?>
    <input disabled="true" id="art_title" class="newreply_txt" type="text" value="<?php echo $art_arr["title"]; ?>" />
    <?php } ?>
    <h4>正文:</h4>
    <textarea id="art_content" cols="30" rows="10"><?php echo $art_arr["content"]; ?></textarea>
    <input class="newreply_sub" type="button" value="确认修改" onclick="return edit_submit();"/>
</form><!--End reply_conter-->

<script type="text/javascript">
    function edit_submit() {
        var father_page = "<?php echo $father_page; ?>";
        var board = "<?php echo $board_name; ?>";
        var filename = "<?php echo $art_arr["filename"]; ?>";
        var article_id = "<?php echo $article_id; ?>";
        var curr_url = "<?php echo $curr_url; ?>";
        var op_flag = "<?php echo $club_flag; ?>";
        var owner = "<?php echo $art_arr["owner"]; ?>";
        var mode = "<?php echo $mode; ?>";
        var currentuser = "<?php echo $currentuser["userid"]; ?>";
        if (currentuser == "guest") {
            document.cookie = "before_login="+curr_url;
            window.location = "login.php";
            return false;
        }

        var title = document.getElementById("art_title").value;
        if (title.length <= 0) {
            Alert("标题不能为空!", 1);
            return false;
        }

        var content = document.getElementById("art_content").value;
        if (content.replace(/(^\s*)|(\s*$)/g, "").length < 10) {
            Alert("内容不少于10个字!", 1);
            return false;
        }

        var url = "/mobile/forum/request/edit_article.php";
        var para = "board="+board+"&filename="+filename+"&art_id="+article_id+"&title="+title+"&content="+content+"&op_flag="+op_flag+"&owner="+owner+"&mode="+mode;
        para = encodeURI(para);

        var myAjax = new Ajax.Request(url,
            {
                method: "post",
                parameters: para,
                onSuccess: function (ret) {
                    if (ret.responseText == true) {
                        alert("修改成功");
                        window.location.href = father_page;
                    } else {
                        Alert("修改失败,"+ret.responseText, 2);
                    }
                },
                onFailure: function (x) {
                    Alert("请求失败", 1);
                }
            }
        );

        return false;
    }
</script>
<?php
include_once("foot.php");
mysql_close($link);
?>
