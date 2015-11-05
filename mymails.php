<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
include_once("head.php");

$curr_url = $_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];
if ($currentuser["userid"] == "guest") {
    setcookie("before_login", $curr_url);
?>
<script type="text/javascript">
    window.location.href = "login.php";
</script>
<?php
}

$user_id = $currentuser["userid"];
$unread_num = 0;
$total_num = 0;

// all:"unread","total","delete","send"
$arr = getMailNumByType($user_id);

?>
<div class="jy_email">
    <div class="ds_box border_bottom _margin_bottom">
        <a href="" onclick="return go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        我的邮箱
    </div><!--<End ds_box-->
    <div class="board_wrap">
        <div class="bd_list border_bottom border_top _margin_bottom ">
            <a href="writemail.php"><img class="jy_email_img" src="img/jy_email1.png" alt="jy_email1.png"/><strong>写邮件</strong><span class="bd_right"></span></a>
        </div>
        <div class="bd_list border_bottom border_top">
            <a href="one_mail_list.php?type=unread"><img class="jy_email_img" src="img/jy_email2.png" alt="jy_email2.png"/><strong>新邮件</strong><span class="bd_right"></span><i class="i red"><?php echo $arr["unread"]; ?></i></a>
        </div>
        <div class="bd_list border_bottom _margin_bottom">
            <a href="one_mail_list.php?type=total"><img class="jy_email_img" src="img/jy_email3.png" alt="jy_email3.png"/><strong>收邮件</strong><span class="bd_right"></span><i class="i"><?php echo $arr["total"]; ?></i></a>
        </div>
        <div class="bd_list border_bottom border_top">
            <a href="one_mail_list.php?type=delete"><img class="jy_email_img" src="img/jy_email4.png" alt="jy_email4.png"/><strong>垃圾箱</strong><span class="bd_right"></span><i class="i"><?php echo $arr["delete"]; ?></i></a>
        </div>
    </div><!--End board_wrap-->
</div>
    <script type="text/javascript" src="js/jquery.js"></script>

<?php
include_once("foot.php");
?>