<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");
require(dirname(__FILE__)."/../../../mqtt/service/Action_msg.php");

$user_id = $currentuser["userid"];
if (empty($user_id) or $user_id == "guest") {
    echo "���ȵ�¼!";
    return false;
}

/* option ��������
 *      == "add" ��Ӻ��ѹ�ϵ Ҫ��ָ����������type Ŀ���û�userid
 *              type: 0 �Ӻ��� 1�ӹ�ע
 *      == "del" ɾ�����ѹ�ϵ ͬ��
 */
if (isset($_POST["option"]))
    $option = $_POST["option"];
else {
    echo "δ֪�Ĳ�������";
    return false;
}

function do_action($user_id, $option, &$msg) {
    if (isset($_POST["type"])) {
        $type = $_POST["type"];
    } else {
        $msg = "δ֪�ĺ�������";
        return false;
    }

    if ($option == "add") {
        if (isset($_POST["userid"])) {
            $friend_id = $_POST["userid"];
        } else {
            $msg = "δ֪�ĺ���id";
            return false;
        }

        $apply_msg = $user_id."�����Ϊ����";
        $ret = add_friend($friend_id, mitbbs_b2g($apply_msg), "", $type);
        $addstate = 1;
        if($ret>0 && $ret!=20 && $ret!=16) {
            if ($type == 0) {
                $msg_arr = array();
                $msg_arr['f_user'] = $user_id;
                $msg_arr['t_user'] = $friend_id;
                $action = new Action($msg_arr);
                if (true != $action->friend_invite())
                    $addstate = -2;
            }

            bbs_sitemail_all($friend_id, 2);
        }


    } elseif ($option =="del") {

    } else {
        $msg = "δ֪�Ĳ�������";
        return false;
    }

    if($ret == -1)
        $msg ="��û��Ȩ���趨���ѻ��ߺ��Ѹ�����������";
    elseif($ret == -2)
        $msg ="�����Ѿ�������Ĺ�ע�б���";
    elseif($ret == -3)
        $msg ="ϵͳ����";
    elseif($ret == -4)
        $msg ="�û�������";
    elseif($ret == -6)
        $msg ="�û�������";
    elseif($ret == -8)
        $msg ="�û��Ѿ������ĺ��ѣ�����������󱻾ܾ�����ɾ���˼�¼�������";
    elseif($ret == -9)
        $msg ="���û��Ѿ��ں�������������Ӻ������ȴӺ�������ɾ�����û�";
    elseif($ret == -10)
        $msg ="���ڶԷ��ĺ�������,�޷�����˺���";
    elseif($ret == 2)
        $msg ="���˱����Ͳ�����ĺ���������";
    elseif($ret == 3)
        $msg ="ɾ��ʧ��";
    elseif($ret == -99)
        $msg ="��������Լ�";
    else if ($addstate==1) {
        if ($type == 0) {
            if($ret == 16)
                $msg = "���ѷ��͹��������룬��ȴ��Է���Ӧ��";
            elseif($ret == 11)
                $msg = "������������˸ú��ѣ�";
            else
                $msg = "��ϲ,�ɹ�����Է��������ĺ���,�����ĵȴ��Է�����!";
        }
        else {
            $ret = 1;
            $msg = "��ӳɹ�";
        }
    }
    elseif ($addstate==-1) {
        if($ret == 20 || $ret=16)
            $msg = "���ѷ��͹��������룬��ȴ��Է���Ӧ��";
        else
            $msg = "�Բ���,���ʧ��";
    }
    elseif ($addstate==-3)
        $msg ="ɾ�����ͳɹ�������֪ͨʧ��";
    elseif ($addstate==-3)
        $msg ="ɾ���ɹ�";

    if ($ret == 1 || $ret == 21 || $ret == 11 || $ret == 12) {
        return true;
    } else {
        $msg.=$ret;
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
