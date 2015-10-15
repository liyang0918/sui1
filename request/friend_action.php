<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");
require(dirname(__FILE__)."/../../../mqtt/service/Action_msg.php");

$user_id = $currentuser["userid"];
if (empty($user_id) or $user_id == "guest") {
    echo "请先登录!";
    return false;
}

/* option 操作类型
 *      == "add" 添加好友关系 要求指定好友类型type 目标用户userid
 *              type: 0 加好友 1加关注
 *      == "del" 删除好友关系 同上
 */
if (isset($_POST["option"]))
    $option = $_POST["option"];
else {
    echo "未知的操作类型";
    return false;
}

function do_action($user_id, $option, &$msg) {
    if (isset($_POST["type"])) {
        $type = $_POST["type"];
    } else {
        $msg = "未知的好友类型";
        return false;
    }

    if ($option == "add") {
        if (isset($_POST["userid"])) {
            $friend_id = $_POST["userid"];
        } else {
            $msg = "未知的好友id";
            return false;
        }

        $apply_msg = $user_id."想加您为好友";
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
        $msg = "未知的操作类型";
        return false;
    }

    if($ret == -1)
        $msg ="您没有权限设定好友或者好友个数超出限制";
    elseif($ret == -2)
        $msg ="此人已经存在你的关注列表中";
    elseif($ret == -3)
        $msg ="系统出错";
    elseif($ret == -4)
        $msg ="用户不存在";
    elseif($ret == -6)
        $msg ="用户不存在";
    elseif($ret == -8)
        $msg ="用户已经是您的好友，如果您的请求被拒绝，请删除此记录后再添加";
    elseif($ret == -9)
        $msg ="该用户已经在黑名单，如需添加好友请先从黑名单中删除该用户";
    elseif($ret == -10)
        $msg ="您在对方的黑名单中,无法邀请此好友";
    elseif($ret == 2)
        $msg ="此人本来就不在你的好友名单中";
    elseif($ret == 3)
        $msg ="删除失败";
    elseif($ret == -99)
        $msg ="不能添加自己";
    else if ($addstate==1) {
        if ($type == 0) {
            if($ret == 16)
                $msg = "您已发送过好友邀请，请等待对方回应！";
            elseif($ret == 11)
                $msg = "您已重新添加了该好友！";
            else
                $msg = "恭喜,成功邀请对方加入您的好友,请耐心等待对方接受!";
        }
        else {
            $ret = 1;
            $msg = "添加成功";
        }
    }
    elseif ($addstate==-1) {
        if($ret == 20 || $ret=16)
            $msg = "您已发送过好友邀请，请等待对方回应！";
        else
            $msg = "对不起,添加失败";
    }
    elseif ($addstate==-3)
        $msg ="删除发送成功，发送通知失败";
    elseif ($addstate==-3)
        $msg ="删除成功";

    if ($ret == 1 || $ret == 21 || $ret == 11 || $ret == 12) {
        return true;
    } else {
        $msg.=$ret;
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
