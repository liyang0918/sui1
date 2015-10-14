<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");

$user_id = $currentuser["userid"];
if (empty($user_id) or $user_id == "guest") {
    echo "���ȵ�¼!";
    return false;
}

/* option �����������
 *      == "clear"  ����ʼ���,Ҫ��ָ�� dir
 *      == "move"   �ƶ�,Ҫ��ָ��s_dir d_dir mailid
 *      == "del"    ����ɾ���ʼ�,Ҫ��ָ�� dir mailid
 * */
if (isset($_POST["option"]))
    $option = $_POST["option"];
else {
    echo "δ֪�Ĳ�������";
    return false;
}

function do_action($user_id, $option, &$msg) {
    if ($option == "clear") {
        if (isset($_POST["dir"]))
            $dir = $_POST["dir"];
        else {
            $msg = "δָ���ʼ���!";
            return false;
        }

        $dirname = shadow_to_dir($dir);
        if (empty($dirname)) {
            $msg = "��ָ����ȷ���ʼ���";
            return false;
        }

        $mail_fullpath = bbs_setmailfile($user_id, $dirname);
        $mail_total = bbs_getmailnum2($mail_fullpath);
        if ($mail_total == 0) {
            $msg = "�ʼ���Ϊ��!";
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
            $msg = "��ָ��Դ�ʼ���";
            return false;
        }

        if (isset($_POST["d_dir"]))
            $d_dir = $_POST["d_dir"];
        else {
            $msg = "��ָ��Ŀ���ʼ���";
            return false;
        }

        if (isset($_POST["mailid"]))
            $mailid = $_POST["mailid"];
        else {
            $msg = "��ѡ��Ҫɾ�����ʼ�";
            return false;
        }

        $s_dirname = shadow_to_dir($s_dir);
        if (empty($s_dirname)) {
            $msg = "��ָ����ȷ��Դ�ʼ���";
            return false;
        }

        $d_dirname = shadow_to_dir($d_dir);
        if (empty($d_dirname)) {
            $msg = "��ָ����ȷ��Ŀ���ʼ���";
            return false;
        }

        $mails = array();
        $mail_fullpath = bbs_setmailfile($user_id, $s_dirname);
        if (bbs_get_records_from_num($mail_fullpath, $mailid, $mails))
            $filename = $mails[0]["FILENAME"];
        else {
            $msg = "�ʼ�ID����!";
            return false;
        }

        bbs_movemail($s_dirname, $d_dirname, $filename);
        return true;
    } elseif ($option == "del") {
        if (isset($_POST["dir"]))
            $dir = $_POST["dir"];
        else {
            $msg = "δָ���ʼ���!";
            return false;
        }

        if (isset($_POST["mailid"]))
            $mailid = $_POST["mailid"];
        else {
            $msg = "��ѡ��Ҫɾ�����ʼ�";
            return false;
        }

        $dirname = shadow_to_dir($dir);
        if (empty($dirname)) {
            $msg = "��ָ����ȷ���ʼ���";
            return false;
        }

        $mail_fullpath = bbs_setmailfile($user_id, $dirname);
        $mails = array();
        if (bbs_get_records_from_num($mail_fullpath, $mailid, $mails))
            $filename = $mails[0]["FILENAME"];
        else {
            $msg = "�ʼ�ID����!";
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
        $msg = "δ֪�Ĳ�������";
        return false;
    }
}

if (!securityCheck()) {
    echo "��ȫ��֤ʧ��!";
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

