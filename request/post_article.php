<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");
$club_flag = 0; // 0��̳ 1���ֲ�

if(isset($_POST["board"])) {
    $board_name = $_POST["board"];
    $club_flag = 0;
} else if (isset($_POST["club"])) {
    $board_name = $_POST["club"];
    $club_flag = 1;
    // ��ӶԾ��ֲ�����post��Ȩ���ж�
    $user_id = $currentuser["userid"];
    $user_num_id = $currentuser["num_id"];
    $clubarr = array();
    $club_id = bbs_getclub($board_name, $clubarr);
    $link = db_connect_web();
    $member_type = clubCheckMember($club_id, $user_num_id, $link);
    mysql_close($link);
    if ($member_type != 2) {
        echo iconv("GBK", "UTF-8//IGNORE", "���ȼ�����ֲ��ٷ���");
        return false;
    }
}

$title = "";
if(!empty($_POST["title"])) {
    $title = illCharIgnore($_POST["title"]);
    $tmp = iconv("UTF-8", "GBK//IGNORE", $title);
    if ($tmp)
        $title = $tmp;
}

$reid=0;
if(!empty($_POST["reid"])) {
    $reid = intval($_POST["reid"]);
}

$content = "";
if(!empty($_POST["content"])) {
    $content = $_POST["content"];
    $tmp = iconv("UTF-8", "GBK//IGNORE", $content);
    if ($tmp)
        $content = $tmp;
}

//�ò��ֲ�����֮�����ҳ��������ݲ��丳ֵ
$signature=0; //ǩ����˳�� int
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
$echo_ret=$ret;
switch ($ret) {
    case -1:
        $echo_ret="���������������";
        break;
    case -2:
        $echo_ret="����Ϊ����Ŀ¼��";
        break;
    case -3:
        $echo_ret="����Ϊ�գ���ֻ������Ч�ַ�";
        break;
    case -4:
        $echo_ret="����������ֻ����, ����������Ȩ���ڴ˷�������";
        break;
    case -5:
        $echo_ret="�ܱ�Ǹ, �㱻������Աֹͣ�˱����postȨ��";
        break;
    case -6:
        $echo_ret="���η��ļ������,����Ϣ��������";
        break;
    case -7:
        $echo_ret="�޷���ȡ�����ļ�! ��֪ͨվ����Ա, лл";
        break;
    case -8:
        $echo_ret="���Ĳ��ɻظ�";
        break;
    case -9:
        $echo_ret="ϵͳ�ڲ�����, ��Ѹ��֪ͨվ����Ա, лл";
        break;
    case -10:
        $echo_ret="�ǳ���Ǹ������Ƶ���ķ�����������";
        break;
}
if($echo_ret>0){
    echo true;
} else {
    echo mb_convert_encoding($echo_ret, "UTF-8", "GBK");
//    echo $echo_ret;
}

