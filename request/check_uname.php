<?php
require("../../../mitbbs_funcs.php");
function check_user_home($user_id) {
    $path = BBS_HOME."/home/".strtoupper(substr($user_id, 0, 1))."/$user_id";
    if (is_dir($path))
        return true;
    else
        return false;
}

if(isset($_POST["user_name"])){
    $user_name = $_POST["user_name"];
    $user_name=iconv('UTF-8','gb2312',$user_name);
    if (!strcmp($user_name, ""))
        $ret_text = "用户代号(ID)不能为空";
    else if(preg_match ("/[A-Za-z]/",  $user_name[0])==0)
        $ret_text = "用户代号必须以字母开头";
    else if (strlen($user_name) > 12) {
        $ret_text = "用户代号长度不得大于12个字符";
    }elseif (preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/", $user_name)){
        $ret_text = " 含有汉字";
    } else {
        for ( $loop=strlen($user_name)-1; $loop > 0 ;$loop--) {
            if (preg_match("/[A-Za-z]/", $user_name[$loop]) == 1
                && preg_match("/[0-9]+/", $user_name[$loop - 1]) == 1
            ) {
                $ret_text = "数字置后，字母开头!";
                break;
            }
            if (preg_match("/[A-Za-z]/", $user_name[$loop]) == 1 || preg_match("/[0-9]/", $user_name[$loop]) == 1) {
            } else {
                $ret_text = "数字置后，字母开头!";
                break;
            }
//            if (preg_match("/[_]/", $user_name[$loop]) == 1 ){
//                $ret_text="under_line";
//            }
        }
    }
    $result=bbs_checkUserName($user_name);
    if (4==$result)
        $ret_text = "重复的用户名申请！";
    elseif (check_user_home($user_name))
        $ret_text = "用户目录已存在";


    if(!isset($ret_text)) {
       $ret_text=true;
       //$ret_text=$result;
    }


    //$ret=charsetToUTF8($ret_text);
    $ret=$ret_text;

    echo $ret;
}
?>