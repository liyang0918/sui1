<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");

$user_id = $currentuser["userid"];
if (empty($user_id) or $user_id == "guest") {
    echo "请先登录!";
    return false;
}

/* option 邮箱操作类型
 *      == "clear"  清空邮件夹,要求指定 dir
 *      == "move"   移动,要求指定s_dir d_dir mailid
 *      == "del"    彻底删除邮件,要求指定 dir mailid
 * */
if (isset($_POST["option"]))
    $option = $_POST["option"];
else {
    echo "未知的操作类型";
    return false;
}

function do_action($user_id, $option, &$msg) {
    if ($option == "clear") {
        if (isset($_POST["dir"]))
            $dir = $_POST["dir"];
        else {
            $msg = "未指定邮件夹!";
            return false;
        }

        $dirname = shadow_to_dir($dir);
        if (empty($dirname)) {
            $msg = "请指定正确的邮件夹";
            return false;
        }

        $mail_fullpath = bbs_setmailfile($user_id, $dirname);
        $mail_total = bbs_getmailnum2($mail_fullpath);
        if ($mail_total == 0) {
            $msg = "邮件夹为空!";
            return false;
        }

        $mails = bbs_getmails($mail_fullpath, 0, $mail_total);

        $sync_size = 0;
        $no_sync_size = 0;
        foreach ($mails as $each) {
            $del_info = array();
            bbs_delmail($dirname,  $each["FILENAME"], $del_info);
            if ($del_info["MAIL_SIZE"] > 0) {
                if ($del_info["TYPE_FLAG"] == 0) {
                    $no_sync_size += $del_info["MAIL_SIZE"];
                } else {
                    $sync_size += $del_info["MAIL_SIZE"];
                }
            }
        }

        if ($no_sync_size > 0)
            bbs_update_usedspace_todb(-$no_sync_size, 0);

        if ($sync_size > 0)
            bbs_update_usedspace_todb(-$sync_size, 1);

        return true;
    } elseif ($option == "move") {
        if (isset($_POST["s_dir"]))
            $s_dir = $_POST["s_dir"];
        else {
            $msg = "请指定源邮件夹";
            return false;
        }

        if (isset($_POST["d_dir"]))
            $d_dir = $_POST["d_dir"];
        else {
            $msg = "请指定目标邮件夹";
            return false;
        }

        if (isset($_POST["mailid"]))
            $mailid = $_POST["mailid"];
        else {
            $msg = "请选择要删除的邮件";
            return false;
        }

        $s_dirname = shadow_to_dir($s_dir);
        if (empty($s_dirname)) {
            $msg = "请指定正确的源邮件夹";
            return false;
        }

        $d_dirname = shadow_to_dir($d_dir);
        if (empty($d_dirname)) {
            $msg = "请指定正确的目标邮件夹";
            return false;
        }

        $mails = array();
        $mail_fullpath = bbs_setmailfile($user_id, $s_dirname);
        if (bbs_get_records_from_num($mail_fullpath, $mailid, $mails))
            $filename = $mails[0]["FILENAME"];
        else {
            $msg = "邮件ID错误!";
            return false;
        }

        bbs_movemail($s_dirname, $d_dirname, $filename);
        return true;
    } elseif ($option == "del") {
        if (isset($_POST["dir"]))
            $dir = $_POST["dir"];
        else {
            $msg = "未指定邮件夹!";
            return false;
        }

        if (isset($_POST["mailid"]))
            $mailid = $_POST["mailid"];
        else {
            $msg = "请选择要删除的邮件";
            return false;
        }

        $dirname = shadow_to_dir($dir);
        if (empty($dirname)) {
            $msg = "请指定正确的邮件夹";
            return false;
        }

        $mail_fullpath = bbs_setmailfile($user_id, $dirname);
        $mails = array();
        if (bbs_get_records_from_num($mail_fullpath, $mailid, $mails))
            $filename = $mails[0]["FILENAME"];
        else {
            $msg = "邮件ID错误!";
            return false;
        }

        $sync_size = 0;
        $no_sync_size = 0;
        $del_info = array();
        bbs_delmail($dirname,  $filename, $del_info);
        if ($del_info["MAIL_SIZE"] > 0) {
            if ($del_info["TYPE_FLAG"] == 0) {
                $no_sync_size += $del_info["MAIL_SIZE"];
            } else {
                $sync_size += $del_info["MAIL_SIZE"];
            }
        }

        if ($no_sync_size > 0)
            bbs_update_usedspace_todb(-$no_sync_size, 0);

        if ($sync_size > 0)
            bbs_update_usedspace_todb(-$sync_size, 1);

        return true;

    } else {
        $msg = "未知的操作类型";
        return false;
    }
}

if (!securityCheck()) {
    echo "安全验证失败!";
    return false;
}

$msg = "";
if (do_action($user_id, $option, $msg)) {
    echo true;
    return true;
} else {
    echo $msg;
    return false;
}

