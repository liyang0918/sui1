<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");

$board_name = "";
$filename = "";
$art_id = 0;
$title = "";
$content = "";
// �������� op_flag == 0 ���ֲ����� op_flag == 1
$op_flag = "";
$owner = "";
$kws = array();
$kws_count = 0;
$mode = 6;

$board_name = $_POST["board"];
$filename = $_POST["filename"];
$art_id = intval($_POST["art_id"]);
$title =  urldecode($_POST["title"]);

// �������ʹ��GBK����
$tmp_title = iconv("UTF-8", "GBK//IGNORE", $title);
if ($tmp_title)
    $title = $tmp_title;

$content = urldecode($_POST["content"]);
$tmp_content = iconv("UTF-8", "GBK//IGNORE", $content);
if ($tmp_content)
    $content = $tmp_content;

$op_flag = intval($_POST["op_flag"]);
$mode = intval($_POST["mode"]);


$ret = bbs_editarticle($board_name,$filename,$art_id,preg_replace("/\\\(['|\"|\\\])/", "$1", $title),preg_replace("/\\\(['|\"|\\\])/", "$1", $content),$op_flag,$owner,$kws,$kws_count,$mode);
$echo_ret = $ret;
switch ($ret) {
    case -1:
        $echo_ret="����Ĳ���";
        break;
    case -2:
        $echo_ret="�Բ��������ܱ༭��ƪ����";
        break;
    case -3:
        $echo_ret="�ܱ�Ǹ,���ı�����ܺ��в�ǡ������,����ϸ��黻�����⡣";
        break;
    case -4:
        $echo_ret="�޸�����ʧ�ܣ����¿��ܺ��в�ǡ�������ݡ�";
        break;
    case -5:
        $echo_ret="�޸�����ʧ��!";
        break;
    case -6:
        $echo_ret="ϵͳ����";
        break;
    default:
        $echo_ret = 1;
}
if($echo_ret > 0){
    echo true;
} else {
    echo $echo_ret;
}

