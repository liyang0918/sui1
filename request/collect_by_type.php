<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$method_array = $_POST;

function error_quit($errno, $error_str) {
    global $link;
    @mysql_close($link);
    echo json_encode(array("result"=>$errno, "msg"=>iconv("GBK", "UTF-8", $error_str)));
    exit;
}

$user_id = $currentuser["userid"];
$user_num_id = $currentuser["num_id"]
if (empty($user_id) or $user_id == "guest")
    error_quit(-1, "����û�е�¼");

/* type: �ղ�����
 *      1  �ղذ���
 *      6  �ղذ�������
 *      7  �ղؾ��ֲ�����
 *
 * */

if (!isset($method_array["type"]))
    error_quit(-2, "δ֪�Ĳ�������");

$type = $method_array["type"];
if ($type == 1) {
    $board_id = $method_array["board_id"];
    $board_name = getBoardName($board_id, $link);
    if (check_if_fav($link, $type, $board_id, 0, 0, $board_name, "", "")) {
//        error_quit(-3, "���Ѿ��ղع��ð���");
        // �Ѿ��ղع�,ȡ���ղ�

    } else {
        // �ղظð���
        $sqlstr="insert into fav_article(user_id,user_numid,title,describle,add_time,type,num_id1,num_id2,num_id3,char_id1,char_id2,char_id3) values ('".
            $user_id."',$user_num_id,'$board_name','$board_name',now(),{$type},{$board_id},
		0,0,'$board_name','','')";

        $result = mysql_query($sqlstr, $link);
        if (($row = mysql_fetch_array($result)) == NULL) {
            error_quit(-4, "�ղ�ʧ��");
        }
    }
}

return array("result"=>0);

mysql_close($link);