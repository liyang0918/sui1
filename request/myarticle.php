<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();
$sec_category = $_COOKIE["sec_category"];
$article_type = $_GET["article_type"];

$request_type_list = array(
    "article"=>array("board"=>"1", "club"=>"2"),
    "collect"=>array("board"=>"3", "club"=>"4")
);

$request_type = $request_type_list[$sec_category][$article_type];
if (empty($request_type)) {
    mysql_close($link);
    return false;
}

$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

$article_num = 40;

function getMyArticleBoard($link, $page, $num=40) {
    global $currentuser;
    $page = ($page-1)*$num;

    $sql = "SELECT board_id, article_id FROM dir_article_memory USE INDEX (index_ownerid_posttime) WHERE owner_id={$currentuser["num_id"]} ORDER BY posttime DESC LIMIT $page, $num";
    $result = mysql_query($sql, $link);
    if (mysql_num_rows($result) < $num)
        $end_flag = "1";
    else
        $end_flag = "0";
    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        $sql1 = "SELECT title,boardname,total_reply,reply_num,read_num,groupid,article_id FROM dir_article_{$row["board_id"]} WHERE article_id={$row["article_id"]}";
        $sql2 = "SELECT board_desc FROM board WHERE board_id={$row["board_id"]}";
        $result1 = mysql_query($sql1, $link);
        $result2 = mysql_query($sql2, $link);

        $row1 = mysql_fetch_array($result1);
        $row2 = mysql_fetch_array($result2);

        if (empty($row1) or empty($row2))
            continue;

        $tmp["title"] = $row1["title"];
        $tmp["enName"] = $row1["boardname"];
        $tmp["cnName"] = $row2["board_desc"];
        $tmp["cnName"] = trim(substr($tmp["cnName"],strpos($tmp["cnName"],']')+1));
        // 非回复类文章的回复数是total_reply,回复类文扎根回复数是reply_num
        if ($row1["groupid"] == $row1["article_id"])
            $tmp["reply"] = $row1["total_reply"];
        else
            $tmp["reply"] = $row1["reply_num"];
        $tmp["read"] = $row1["read_num"];
        if ($row1["groupid"] == $row["article_id"]) {
            // 非回复类型的文章,直接跳转为同主题阅读
            $tmp["href"] = url_generate(3, array("board"=>$tmp["enName"], "groupid"=>$row["article_id"]));
        } else {
            // 回复类型的文章,只显示本条回复内容
            $tmp["href"] = url_generate(4, array(
                "action" => "one_article.php",
                "args" => array("board"=>$row1["boardname"], "article_id"=>$row["article_id"])
            ));
        }

        $ret[] = $tmp;
    }

    return array($ret, $end_flag);
}

function getMyArticleClub($link, $page, $num=40) {
    global $currentuser;
    $page = ($page-1)*$num;

    $sql = "SELECT club_id, article_id FROM club_dir_article_memory USE INDEX (index_ownerid_posttime) WHERE owner_id={$currentuser["num_id"]} ORDER BY posttime DESC LIMIT $page, $num";
    $result = mysql_query($sql, $link);

    if (mysql_num_rows($result) < $num)
        $end_flag = "1";
    else
        $end_flag = "0";

    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        $club_id = $row["club_id"];
        $table_id = $club_id%256;
        if ($table_id == 0)
            $table_id = 256;

        $sql1 = "SELECT title,club_name,total_reply,read_num,groupid,article_id FROM club_dir_article_{$table_id} WHERE club_id=$club_id AND groupid={$row["article_id"]}";
//        echo $sql1."<br/><br/>";
        $sql2 = "SELECT club_cname FROM club WHERE club_id=$club_id";

        $result1 = mysql_query($sql1, $link);
        $result2 = mysql_query($sql2, $link);

        $row1 = mysql_fetch_array($result1);
        $row2 = mysql_fetch_array($result2);

        if (empty($row1) or empty($row2))
            continue;

        $tmp["title"] = $row1["title"];
        $tmp["enName"] = $row1["club_name"];
        $tmp["cnName"] = $row2["club_cname"];
        if ($row1["groupid"] == $row1["article_id"])
            $tmp["reply"] = $row1["total_reply"];
        else
            $tmp["reply"] = $row1["reply_num"];
        $tmp["read"] = $row1["read_num"];
        if ($row1["groupid"] == $row["article_id"]) {
            // 非回复类型的文章,直接跳转为同主题阅读
            $tmp["href"] = url_generate(3, array("club"=>$tmp["enName"], "groupid"=>$row["article_id"]));
        } else {
            // 回复类型的文章,只显示本条回复内容
            $tmp["href"] = url_generate(4, array(
                "action" => "one_article.php",
                "args" => array("club"=>$tmp["enName"], "article_id"=>$row["article_id"])
            ));
        }

        $ret[] = $tmp;
    }

    return array($ret, $end_flag);
}

function getCollectArticleBoard($link, $page, $num=40) {
    global $currentuser;
    $page = ($page-1)*$num;
    $user_id = $currentuser["userid"];
    $sql = "SELECT num_id1 AS board_id,num_id2 AS article_id FROM fav_article WHERE type=1 AND user_id=\"$user_id\" ORDER BY add_time DESC LIMIT $page,$num";
    $result = mysql_query($sql, $link);

    if (mysql_num_rows($result) < $num)
        $end_flag = "1";
    else
        $end_flag = "0";

    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        $sql1 = "SELECT title,boardname,total_reply,read_num,groupid,article_id FROM dir_article_{$row["board_id"]} WHERE article_id={$row["article_id"]}";
        $sql2 = "SELECT board_desc FROM board WHERE board_id={$row["board_id"]}";

        $result1 = mysql_query($sql1, $link);
        $result2 = mysql_query($sql2, $link);

        $row1 = mysql_fetch_array($result1);
        $row2 = mysql_fetch_array($result2);
        if (empty($row1) or empty($row2))
            continue;

        $tmp["title"] = $row1["title"];
        $tmp["enName"] = $row1["boardname"];
        $tmp["cnName"] = $row2["board_desc"];
        $tmp["cnName"] = trim(substr($tmp["cnName"],strpos($tmp["cnName"],']')+1));
        if ($row1["groupid"] == $row1["article_id"])
            $tmp["reply"] = $row1["total_reply"];
        else
            $tmp["reply"] = $row1["reply_num"];
        $tmp["read"] = $row1["read_num"];
        if ($row1["groupid"] == $row["article_id"]) {
            // 非回复类型的文章,直接跳转为同主题阅读
            $tmp["href"] = url_generate(3, array("board"=>$tmp["enName"], "groupid"=>$row["article_id"]));
        } else {
            // 回复类型的文章,只显示本条回复内容
            $tmp["href"] = url_generate(4, array(
                "action" => "one_article.php",
                "args" => array("board"=>$tmp["enName"], "article_id"=>$row["article_id"])
            ));
        }

        $ret[] = $tmp;
    }
    mysql_free_result($result);

    return array($ret, $end_flag);
}

function getCollectArticleClub($link, $page, $num=40) {
    global $currentuser;
    $page = ($page-1)*$num;

    $user_id = $currentuser["userid"];
    $sql = "SELECT num_id1 AS club_id,num_id2 AS article_id FROM fav_article WHERE type=2 AND user_id=\"$user_id\" ORDER BY add_time DESC LIMIT $page,$num";

    $result = mysql_query($sql, $link);
    if (mysql_num_rows($result) < $num)
        $end_flag = "1";
    else
        $end_flag = "0";

    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        $club_id = $row["club_id"];
        $table_id = $club_id%256;
        if ($table_id == 0)
            $table_id = 256;

        $sql1 = "SELECT title,club_name,total_reply,read_num,groupid,article_id FROM club_dir_article_{$table_id} WHERE club_id=$club_id AND groupid={$row["article_id"]}";
        $sql2 = "SELECT club_cname FROM club WHERE club_id=$club_id";

        $result1 = mysql_query($sql1, $link);
        $result2 = mysql_query($sql2, $link);

        $row1 = mysql_fetch_array($result1);
        $row2 = mysql_fetch_array($result2);
        if (empty($row1) or empty($row2))
            continue;

        $tmp["title"] = $row1["title"];
        $tmp["enName"] = $row1["club_name"];
        $tmp["cnName"] = $row2["club_cname"];
        if ($row1["groupid"] == $row1["article_id"])
            $tmp["reply"] = $row1["total_reply"];
        else
            $tmp["reply"] = $row1["reply_num"];
        $tmp["read"] = $row1["read_num"];
        if ($row1["groupid"] == $row["article_id"]) {
            // 非回复类型的文章,直接跳转为同主题阅读
            $tmp["href"] = url_generate(3, array("club"=>$tmp["enName"], "groupid"=>$row["article_id"]));
        } else {
            // 回复类型的文章,只显示本条回复内容
            $tmp["href"] = url_generate(4, array(
                "action" => "one_article.php",
                "args" => array("club"=>$tmp["enName"], "article_id"=>$row["article_id"])
            ));
        }

        $ret[] = $tmp;
    }

    return array($ret, $end_flag);
}

$all_arr = array();
$str_content = '<ul class="article_wrap">';

switch ($request_type) {
    case "1":
        list($t_data, $end_flag) = getMyArticleBoard($link, $page, $article_num);
        break;
    case "2":
        list($t_data, $end_flag) = getMyArticleClub($link, $page, $article_num);
        break;
    case "3":
        list($t_data, $end_flag) = getCollectArticleBoard($link, $page, $article_num);
        break;
    case "4":
        list($t_data, $end_flag) = getCollectArticleClub($link, $page, $article_num);
        break;
    default:
        $t_data = array();
        $end_flag = "1";
}
//var_dump($t_data);
//echo "<br /> ==> $end_flag";

setcookie("end_flag", (string)$end_flag, 0, "/");

foreach ($t_data as $each) {
    $str_content .= '<li class="article_list_nopic">';
    $str_content .= '<div class="content_list nopic">';
    $str_content .= '<h4><a href="'.$each["href"].'">'.$each["title"].'</a></h4>';
    $str_content .= '<p class="commen_p">';
    $str_content .= '<span>'.$each["cnName"].'</span>';
    $str_content .= '<span class="commen_right">'.$each["reply"].'/'.$each["read"].'</span>';
    $str_content .= '</p></div></li>';
}
$str_content .= '</ul>';

// json_encode()转换的字符串含的中文必须为UTF-8编码
$str_content = mb_convert_encoding($str_content, "UTF-8", "GBK");
$all_arr["detail"] = $str_content;
if ($page == 1)
    echo json_encode($all_arr);
else
    echo json_encode(array("article"=>$str_content));
mysql_close($link);
?>

