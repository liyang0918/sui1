<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
//data part
$type = $_GET["type"];
$mailid = $_GET["mailid"];
$user_id = $currentuser["userid"];

if ($user_id == "guest")
    return false;

if (empty($type))
    $type = "total";

if (empty($mailid))
    return false;

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
if (bbs_get_records_from_num($mail_fullpath, $mailid, $mails)) {
    bbs_setmailreaded($mail_fullpath, $mailid);
    $ret = getMailInfo($user_id, $mails[0], $type, 1);
}

//data end
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        ÓÊ¼þÄÚÈÝ
    </div>
    <div class="mr_group">
        <div class="mr_list">
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
        <div class="email_content_box margin-bottom">
            <p>
                <?php echo $ret["content"]; ?>
            </p>
        </div>
    </div> <!--End mr_group-->
    <div class="common_bottom_box">
        <a href="jiaye_email_reply.html"><img src="img/backlogo.png" alt="backlogo.png"/>»Ø¸´</a>
        <a onclick="_alertClearEmail()"><img src="img/trash.png" alt="trash.png"/>É¾³ý</a>
    </div>

    <script type="text/javascript" src="js/jquery.js"></script>

<?php
include_once("head.php");
?>
