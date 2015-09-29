<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");
$club_flag = 0; // 0论坛 1俱乐部

if(isset($_POST["board"])) {
    $board_name = $_POST["board"];
    $club_flag = 0;
} else if (isset($_POST["club"])) {
    $board_name = $_POST["club"];
    $club_flag = 1;
}

if(!empty($_POST["title"]))
    $title = $_POST["title"];
if(!empty($_POST["reid"]))
    $reid = $_POST["reid"];
if(!empty($_POST["content"]))
    $content = $_POST["content"];

//该部分参数，之后根据页面添加内容补充赋值
$signature=0; //签名当顺序 int
$outgo=0;
$anony=0;
$type_flag=0;
$keywords=array();
$keyword_num=0;
$tmpl=0;
//
$ret = bbs_postarticle($board_name, preg_replace("/\\\(['|\"|\\\])/", "$1", $title)
    , preg_replace("/\\\(['|\"|\\\])/", "$1", $content), $signature, $reid, $outgo, $anony
    , $type_flag, $club_flag, $keywords, $keyword_num,$tmpl);
log2file("$board_name".mb_detect_encoding($content,array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5')));
$echo_ret=$ret;
switch ($ret) {
    case -1:
        $echo_ret="错误的讨论区名称";
        break;
    case -2:
        $echo_ret="本版为二级目录版";
        break;
    case -3:
        $echo_ret="标题为空，或只含有无效字符";
        break;
    case -4:
        $echo_ret="此讨论区是唯读的, 或是您尚无权限在此发表文章";
        break;
    case -5:
        $echo_ret="很抱歉, 你被版务人员停止了本版的post权利";
        break;
    case -6:
        $echo_ret="两次发文间隔过密,请休息几秒再试";
        break;
    case -7:
        $echo_ret="无法读取索引文件! 请通知站务人员, 谢谢";
        break;
    case -8:
        $echo_ret="本文不可回复";
        break;
    case -9:
        $echo_ret="系统内部错误, 请迅速通知站务人员, 谢谢";
        break;
    case -10:
        $echo_ret="非常抱歉，不能频繁的发表相似文章";
        break;
}
if($echo_ret>0){
    echo true;
} else {
    echo $echo_ret;
}

