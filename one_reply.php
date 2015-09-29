<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."func.php");
include_once("head.php");
//data part
$club_flag = 0;
$group_id = $_GET["group_id"];
$article_id = $_GET["article_id"];
$title = $_GET["title"];

if (isset($_GET["board"])) {
    $club_flag = 0;
    $board_name = $_GET["board"];
} elseif (isset($_GET["club"])) {
    $club_flag = 1;
    $board_name = $_GET["club"];
}

$page = $_GET["page"];
$curr_url = curPageURL();
if ($club_flag == 0)
    $father_page = url_generate(3, array("type"=>$_COOKIE["app_type"], "board"=>$board_name, "groupid"=>$group_id))."&page=$page";
else
    $father_page = url_generate(3, array("type"=>$_COOKIE["app_type"], "club"=>$board_name, "groupid"=>$group_id))."&page=$page";
?>

<div class="ds_box border_bottom">
    <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    回复
</div>
<form class="newreply_conter" action="">
    <h4>标题:</h4>
    <input disabled="true" class="newreply_txt" type="text" value="<?php echo "Re: ".$title; ?>" />
    <h4>正文:</h4>
    <textarea id="text_<?php echo $article_id; ?>" cols="30" rows="10"></textarea>
    <input class="newreply_sub" type="submit" value="发表" onclick="return reply_submit();"/>
</form><!--End reply_conter-->

<script type="text/javascript">
    function reply_submit() {
        var board = "<?php echo $board_name; ?>";
        var groupid = "<?php echo $article_id; ?>";
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
        alert(content);
        post_article(board, title, groupid, "<?php echo $club_flag; ?>");
        document.location = "<?php echo $father_page; ?>";
        return false;
    }
</script>
<?php
include_once("foot.php");
?>
