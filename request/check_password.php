<?php
require("../../../mitbbs_funcs.php");
if(isset($_POST["user_name"])){
    $user_name = $_POST["user_name"];
    $password= $_POST["pass_word"];
    $user_name=iconv('UTF-8','gb2312',$user_name);
    $password=iconv('UTF-8','gb2312',$password);
    if(empty($user_name) ){
       $ret_text="�����û�����";
    }
    if(!strcmp($password,""))
        $ret_text = "���벻��Ϊ��";
    elseif(!strcmp($user_name,$password))
        $ret_text = "
        ���벻�������û�����ͬ
        ���벻�������û�����ͬ
        ���벻�������û�����ͬ
        ���벻�������û�����ͬ
        ���벻�������û�����ͬ
        ���벻�������û�����ͬ
        ���벻�������û�����ͬ
        ���벻�������û�����ͬ
        ";
    elseif (preg_match("/[\x7f-\xff]/", $password)) {
        $ret_text = "���벻Ҫ��������!";
    }
//    elseif (preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/", $user_name)){
//        $ret_text = "���벻���Ժ��к���";
    if(strlen($password)<6 or strlen($password)>18)
    {
           $ret_text = "���볤��Ϊ6-18���ַ�";
    }
    if(!isset($ret_text)) {
        $ret_text=true;
        //$ret_text=$result;
        //$ret=charsetToUTF8($ret);
    }
    //$ret=charsetToUTF8($ret);
    $ret=$ret_text;
    echo $ret;
}
?>
