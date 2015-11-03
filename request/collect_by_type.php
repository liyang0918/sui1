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
$user_num_id = $currentuser["num_id"];
if (empty($user_id) or $user_id == "guest")
    error_quit(-1, "����û�е�¼");

/* type: �ղ�����
 *      6  �ղذ���
 *      1  �ղذ�������
 *      2  �ղؾ��ֲ�����
 *
 * */

if (!isset($method_array["type"]))
    error_quit(-2, "δ֪�Ĳ�������");

$type = $method_array["type"];
if ($type == 6) {
    $board_id = $method_array["board_id"];
    $bnum = bbs_getbnum_by_id($board_id);
    if ($bnum == 0)
        error_quit(-3, "���治����");

    $brdarr = array();
    bbs_getboard_bynum($bnum, $brdarr);

    $board_name = $brdarr["NAME"];
    $check_result = check_if_fav($link, $type, $board_id, 0, 0, $board_name, "", "");
    if (mysql_num_rows($check_result)>0) {
        // �Ѿ��ղع�,ȡ���ղ�
        $fav_id = mysql_fetch_array($check_result)["fav_id"];

        $sql = "DELETE FROM favboard WHERE fav_id=$fav_id";
        $result = mysql_query($sql, $link);
        if ($result == NULL)
            error_quit(-12, "ɾ���ղذ���ʧ��");

        mysql_free_result($result);
    } else {
        // �ղظð���
//        $sqlstr="insert into fav_article(user_id,user_numid,title,describle,add_time,type,num_id1,num_id2,num_id3,char_id1,char_id2,char_id3) values ('".
//            $user_id."',$user_num_id,'$board_name','$board_name',now(),{$type},{$board_id},
//		0,0,'$board_name','','')";
        $sql = "INSERT INTO favboard VALUES (NULL, $user_num_id, -1, 'Y', '{$board_name}') ";
        $result = mysql_query($sql, $link);
        if ($result == NULL) {
            error_quit(-11, "�����ղ�ʧ��");
        }

        mysql_free_result($result);
    }

    mysql_free_result($check_result);
} elseif ($type == 1) {
    $board_id = $method_array["board_id"];
    $group_id = $method_array["group_id"];
    $title = $method_array["title"];
    $title_tmp = iconv("UTF-8", "GBK//IGNORE", $title);
    if ($title_tmp)
        $title = $title_tmp;

    $board_name = "";

    // ���������Ƿ����
    $sql_tmp = "SELECT article_id  article_id,board_id,boardname,title
			      FROM dir_article_{$board_id}
			      WHERE article_id={$group_id} ";
    $result_tmp = mysql_query($sql_tmp, $link);
    if ($result_tmp == NULL)
        error_quit(-100, "ϵͳ����");

    if (mysql_num_rows($result_tmp) != 1)
        error_quit(-4, "���²�����");
    else {
        $board_name = mysql_fetch_array($result_tmp)["boardname"];
        mysql_free_result($result_tmp);
    }

    $check_result = check_if_fav($link, $type, $board_id, $group_id, 0, $board_name, "", "");
    if (mysql_num_rows($check_result) > 0) {
        $fav_article_id = mysql_fetch_array($check_result)["fav_article_id"];
        $sql = "DELETE FROM fav_article
                  WHERE user_numid={$user_num_id} AND fav_article_id={$fav_article_id}";
        $result = mysql_query($sql, $link);
        if ($result == NULL)
            error_quit(-14, "ɾ���ղ�����ʧ��");

        mysql_free_result($result);
    } else {
        $sql = "INSERT INTO fav_article(user_id,user_numid,title,describle,add_time,type,num_id1,num_id2,num_id3,char_id1,char_id2,char_id3) VALUES ('".
            $user_id."',$user_num_id,'$title','',now(),{$type},{$board_id},
    		$group_id,0,'$board_name','','')";
        $result = mysql_query($sql, $link);
        if ($result == NULL)
            error_quit(-13, "���������ղ�ʧ��");

        mysql_free_result($result);
    }

    mysql_free_result($check_result);
} elseif ($type == 2) {
    $club_id = $method_array["club_id"];
    $group_id = $method_array["group_id"];
    $title = $method_array["title"];
    $title_tmp = iconv("UTF-8", "GBK//IGNORE", $title);
    if ($title_tmp)
        $title = $title_tmp;

    $club_name = "";

    // ���������Ƿ����
    $table_num = $club_id%256;
    if ($table_num == 0)
        $table_num = 256;
    $sql_tmp = "SELECT article_id,club_id,club_name,title
                  FROM club_dir_article_{$table_num}
                  WHERE club_id=$club_id AND article_id={$group_id} ";
    $result_tmp = mysql_query($sql_tmp, $link);
    if ($result_tmp == NULL)
        error_quit(-100, "ϵͳ����");

    if (mysql_num_rows($result_tmp) != 1)
        error_quit(-5, "���²�����");
    else {
        $club_name = mysql_fetch_array($result_tmp)["club_name"];
        mysql_free_result($result_tmp);
    }

    $check_result = check_if_fav($link, $type, $club_id, $group_id, 0, $club_name, "", "");
    if (mysql_num_rows($check_result) > 0) {
        $fav_article_id = mysql_fetch_array($check_result)["fav_article_id"];
        $sql = "DELETE FROM fav_article
                  WHERE user_numid={$user_num_id} AND fav_article_id={$fav_article_id}";

        $result = mysql_query($sql, $link);
        if ($result == NULL)
            error_quit(-16, "ɾ���ղ�����ʧ��");

        mysql_free_result($result);
    } else {
        $sql = "INSERT INTO fav_article(user_id,user_numid,title,describle,add_time,type,num_id1,num_id2,num_id3,char_id1,char_id2,char_id3) VALUES ('".
            $user_id."',$user_num_id,'$title','',now(),{$type},{$club_id},
    		$group_id,0,'$club_name','','')";
        $result = mysql_query($sql, $link);
        if ($result == NULL)
            error_quit(-15, "���ֲ������ղ�ʧ��");

        mysql_free_result($result);
    }

    mysql_free_result($check_result);
} else {
    error_quit(-2, "δ֪�Ĳ�������");
}

echo json_encode(array("result"=>0));
mysql_close($link);

return true;