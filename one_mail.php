<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
//data part
if (!isset($_GET["mailid"]))
    return false;

$type = $_GET["type"];
$mailid = $_GET["mailid"];
$user_id = $currentuser["userid"];

if ($user_id == "guest")
    return false;

if (empty($type))
    $type = "total";

$father_page = '/mobile/forum/one_mail_list.php?type='.$type;

if ($mailid < 0) {
    header("Location:".$father_page);
}

$dirname = "";
switch ($type) {
    case "unread":
    case "total":
        $dirname = ".DIR";
        break;
    case "send":
        $dirname = ".SENT";
        break;
    case "delete":
        $dirname = ".DELETED";
        break;
    default:
        $dirname = ".DIR";
}


$mail_fullpath = bbs_setmailfile($user_id, $dirname);
$mails = array();
$ret = array();
if (bbs_get_records_from_num($mail_fullpath, $mailid, $mails)) {
    bbs_setmailreaded($mail_fullpath, $mailid);
    $ret = getMailInfo($user_id, $mails[0], $type, 1);
} else {
    header("Location:".$father_page);
}

//data end
?>
    <div class="ds_box border_bottom">
        <a href="<?php echo $father_page; ?>" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        邮件内容
    </div>
    <div class="mr_group">
        <div class="mr_list margin_bottom">
            <div class="mr_list_div">
                <img class="mr_list_img" src="<?php echo $ret["owner_img"]; ?>" alt="pic"/>
                <div>
                    <span><?php echo $ret["owner"]; ?></span>
                    <em><?php echo $ret["title"]; ?></em>
                    <p class="mr_p_bottom">
                        <span class="mr_span_r"><?php echo date("Y-m-d", $ret["time"]); ?></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="email_content_box">
            <p>
                <?php echo $ret["content"]; ?>
            </p>
        </div>
    </div> <!--End mr_group-->
        <?php if ($type != "delete") { ?>
        <div class="common_bottom_box">
            <a href="/mobile/forum/writemail.php?mailto=<?php echo $ret["owner"]; ?>&title=<?php echo "回复:".urlencode($ret["title"]); ?>"><img src="img/backlogo.png" alt="backlogo.png"/>回复</a>
            <a id="<?php echo $type."_".$mailid; ?>" onclick="_alertClearEmail(this);"><img src="img/trash.png" alt="trash.png"/>删除</a>
        <?php } else { ?>
        <div class="common_bottom_box">
            <a id="<?php echo $type."_".$mailid; ?>" onclick="_alertRecoverEmail(this)"><img src="img/circle.png" alt="circle.png"/>还原</a>
            <a id="<?php echo $type."_".$mailid; ?>" onclick="_alertDelEmail(this)"><img src="img/trash.png" alt="trash.png"/>彻底删除</a>
        <?php } ?>
    </div>

<?php
include_once("head.php");
?>
