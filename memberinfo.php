<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
$link = db_connect_web();

$member = $_GET["userid"];
$user_id = $currentuser["userid"];
$user_num_id = $currentuser["num_id"];
if (empty($user_id))
    $user_id = "guest";
$member_exist = false;
$focus_flag = 0;
$friend_flag = 0;

function get_userinfo($link, $user_id) {
    if (empty($user_id))
        return false;

    $sql = "SELECT numeral_user_id AS num_id,username,numlogins,numposts FROM users WHERE user_id=\"$user_id\";";
    $result = mysql_query($sql, $link);
    $ret = array();
    if ($row = mysql_fetch_array($result)) {
        $ret = $row;
        $ret["headimg"] = get_user_img($user_id);
        $ret["exp"] = bbs_getuser_exp($user_id);
        $ret["exp_level"] = bbs_getuser_expstr($user_id, $ret["exp"]);
        $ret["identity"] = bbs_user_level_char($user_id);
        $tmpcash = load_bank_web($link, $row["num_id"]);
        $ret["allcash"] = $tmpcash[0]["all_newcash"];
        $ret["newcash"] = $tmpcash[0]["new_cash"];
    }

    return $ret;
}

$user_arr = get_userinfo($link, $member);
if (empty($user_arr)) {
    $member_exist = false;
} else {
    $member_exist = true;
    if ($user_id != "guest") {
        $focus_flag = checkFocusEach($link, $user_num_id, $user_arr["num_id"]);
        $friend_flag = checkFriendEach($link, $user_num_id, $user_arr["num_id"]);
    }
}

?>
<html lang="zh">
<head>
    <meta charset="gb2312">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>未名空间</title>


    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/silder.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/reg.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/footer.css" type="text/css" media="screen"/>

</head>
<body>
<div class="conter">
    <nav class="navone">
        <div class="navone_menu">
            <h3 class="navone_h3"><img class="navone_forum" src="img/menu.png" alt="menu.png"/>家页</h3>
        </div>
        <img class="navone_space" src="img/space.png" alt="space.png"/>
    </nav>
    <ul class="navone_ul">
        <li class="navone_li"><a href="index.php"><img src="img/item.png" alt="item.png"/>论坛</a></li>
        <li class="navone_li"><a href="news.php"><img src="img/item.png" alt="item.png"/>新闻</a></li>
        <li class="navone_li"><a href="club.php"><img src="img/item.png" alt="item.png"/>俱乐部</a></li>
        <li class="navone_li"><a href="immigration.php"><img src="img/item.png" alt="item.png"/>移民专栏</a></li>
        <li class="navone_li"><a href="dianping.php"><img src="img/item.png" alt="item.png"/>点评</a></li>
        <li class="navone_li noborder"><a href="search.php"><img src="img/item.png" alt="item.png"/>搜索</a></li>
    </ul>
    <script type="text/javascript" src="js/jquery.nivo.slider.js" charset="utf-8"></script>
    <script type="text/javascript" src="js/slide.js"></script>
    <script type="text/javascript" src="js/funs.js"></script>
    <script type="text/javascript" src="../../js/prototype.js"></script>

    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        个人信息
    </div>

    <div class="jy_generalInfo border_bottom">
        <div class="gen_img">
            <img src="<?php echo $user_arr["headimg"]; ?>" alt="userimg" />
        </div>
        <div class="gen_info">
            <h4><?php echo $member; ?></h4>
            <span>昵称：<?php echo $user_arr["username"]; ?></span>
        </div>
    </div>
    <!--End jy_generalInfo-->
    <ul class="jy_gen_group">
        <li><span>身份</span><em><?php echo $user_arr["identity"]; ?></em></li>
        <li><span>经验值</span><em><?php echo $user_arr["exp"]; ?>（<?php echo $user_arr["exp_level"]; ?>）</em></li>
        <li><span>登陆次数</span><em><?php echo $user_arr["numlogins"]; ?></em></li>
        <li><span>发文数</span><em><?php echo $user_arr["numposts"]; ?></em></li>
        <li><span>伪币</span><em><?php echo $user_arr["allcash"]; ?></em></li>
        <li><span>可用伪币</span><em><?php echo $user_arr["newcash"]; ?></em></li>
    </ul>
    <?php if($member_exist == false) {?>
        <script type="text/javascript">
            Alert("用户不存在", 1);
        </script>
    <?php } ?>
<?php if ($user_id != "guest" and $user_id != $member and $member_exist) { ?>
    <div class="search_result_memberInfo">
        <?php if ($friend_flag == 0 or $friend_flag == 2) { ?>
        <input id="add_friend" class="search_btn1" type="button" value="加为好友" onclick="return add_friend('<?php echo $member; ?>', 2)"/>
        <?php } else { ?>
        <input class="search_btn1 button_disable" type="button" value="已经成为好友" onclick="return false"/>
        <?php } ?>
        <p class="search_inp_box">
            <?php if ($focus_flag == 0 or $focus_flag == 2) { ?>
            <input id="add_focus" class="margin_right" type="button" value="+关注" onclick="return add_focus('<?php echo $member; ?>', 2)" />
            <?php } else { ?>
            <input id="add_focus" class="margin_right button_disable" type="button" value="已关注" onclick="return false" />
            <?php } ?>
            <input type="button" value="发邮件" onclick="window.location.href='writemail.php?mailto=<?php echo $member; ?>';"/>
        </p>
    </div>
    <br />
    <br />
<?php } ?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/js.js"></script>
<?php
include_once("foot.php");
mysql_close($link);

?>

