<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
include_once("head.php");
if ($currentuser["userid"] == "guest") {
?>
    <script type="text/javascript">
        location.href = "login.php";
    </script>
<?php
}
if(empty($_COOKIE["app_type"]))
setcookie("app_type", "jiaye");
if(empty($_COOKIE["app_show"]))
setcookie("app_show", iconv("GBK","UTF-8//IGNORE","家页"));
if(empty($_COOKIE["sec_category"]))
setcookie("sec_category", "jiaye");

if(!is_own_label($_COOKIE["sec_category"], "jiaye")) {
    setcookie("app_type", "jiaye");
    setcookie("app_show", iconv("GBK", "UTF-8//IGNORE", "家页"));
    setcookie("sec_category", "jiaye");
    // 切换栏目需要重新加载
    echo '<script language="javascript">location.href=location.href</script>';
}


$userinfo = getCurrUserInfo();
?>
    <div class="jy">
        <div class="jy_info">
            <div><img src="<?php echo $userinfo["headimg"]; ?>" alt="person.png"/></div>
            <a href="memberinfo.php?userid=<?php echo $userinfo["UTMPUSERID"]; ?>"><?php echo $userinfo["UTMPUSERID"]; ?><img src="img/pen.png" alt="pen.png"/></a>
        </div>
        <ul class="jy_item">
            <li><a href="focus_list.php?userid=<?php echo $userinfo["UTMPUSERID"]; ?>"><span>关注</span><em> <?php echo $userinfo["solicitudeNum"]; ?></em></a></li>
            <li class="noborder_left"><a href="fans_list.php?userid=<?php echo $userinfo["UTMPUSERID"]; ?>"><span>粉丝</span><em> <?php echo $userinfo["funsNum"]; ?></em></a></li>
        </ul>
        <ul class="jy_group noborder_bottom">
            <li><a href="mydiscuss.php">我的讨论区 <img src="img/btn-right.png" alt="btn-right;.png"/></a></li>
            <li><a href="myclub.php">我的俱乐部 <img src="img/btn-right.png" alt="btn-right;.png"/></a></li>
            <li><a href="jiaye_dp_shops.html">我的点评<img src="img/btn-right.png" alt="btn-right;.png"/></a></li>
            <li><a href="myarticle.php">我的文章 <img src="img/btn-right.png" alt="btn-right;.png"/></a></li>
            <li><a href="mycollect.php">我的收藏 <img src="img/btn-right.png" alt="btn-right;.png"/></a></li>
            <li><a href="myfriends.php">我的好友 <img src="img/btn-right.png" alt="btn-right;.png"/></a></li>
            <li><a href="myblacklist.php">我的黑名单 <img src="img/btn-right.png" alt="btn-right;.png"/></a></li>
            <li><a href="mymessages.php">我的消息<img src="img/btn-right.png" alt="btn-right;.png"/></a></li>
            <li><a href="mymails.php">我的邮箱<img src="img/btn-right.png" alt="btn-right;.png"/></a></li>
        </ul><!--End jy_gen_group-->
        <p class="jy_back"><a href="logout.php">退出登录<img src="img/btn-right.png" alt="btn-right;.png"/></a></p>
    </div><!--End jiaye_personalInfo-->
    <script type="text/javascript" src="js/jquery.js"></script>
<?php
include_once("foot.php");
?>