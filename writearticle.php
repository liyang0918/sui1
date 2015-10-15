<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");

// 文章类型：1论坛文章 2俱乐部文章
$type = 0;
if (isset($_GET["board"])) {
    $type = 1;
    $name = $_GET["board"];
} elseif (isset($_GET["club"])) {
    $type = 2;
    $name = $_GET["club"];
}



$curr_url = $_SERVER["REQUEST_URI"];

?>
    <div class="ds_box border_bottom">
        <a onclick="go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        发文
    </div><!--<End ds_box-->
    <form id="form_article" class="newreply_conter input_padding1" method="post" action="send_article.php">
        <h4>标题:</h4>
        <input class="newreply_txt" type="text"/>
        <h4>正文:</h4>
        <textarea cols="30" rows="10"></textarea>
        <input type="submit" style="display: none">
    </form>
    <form id="form_image" class="newreply_conter input_padding2" method="post" action="check_file.php">
        <p id="image_area">
            <span class="add_img margin_r"></span>
            <input type="file" onchange=""/>
        </p>
    </form>
    <p class="newreply_conter input_padding2"><input class="newreply_sub" type="submit" value="发表" onclick="return sendmail();"/></p>
        <!-- /# -->
    <script type="text/javascript">
        function sendmail() {
            var curr_url = "<?php echo $curr_url; ?>";
            var currentuser = "<?php echo $currentuser["userid"]; ?>";
            var mail_type = "<?php echo $mail_type; ?>";
            if (currentuser == "guest") {
                document.cookie = "before_login="+curr_url;
                window.location = "login.php";
                return false;
            }

            // 获取表单内容，使用ajax post文章

            go_last_page();
            return false;
        }
    </script>
<?php
include_once("foot.php");
?>