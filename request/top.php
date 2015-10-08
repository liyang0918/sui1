<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

//top image 部分
function getArticleLunbo($link){
    $articlelist = array();
    $sql_str = "select board_id,article_id,new_url from article_image_list where board_id=366 and type=200 group
                          by article_id,board_id ORDER BY id DESC limit 3";
    $result = mysql_query($sql_str,$link);
    $i = 0;
    while($row = mysql_fetch_array($result)){
        $articlelist[$i]["article_id"]= $row["article_id"];
        $articlelist[$i]["board_id"]= $row["board_id"];
        $articlelist[$i]["new_url"]= $row["new_url"];
        $i++;
    }
    mysql_free_result($result);

    return $articlelist;
}



function getTopArticle($link, $page, $limit_article) {
    global $articlelist;
    // 轮播图中显示的文章不再显示到文章列表中
    $limit_article = $articlelist;

    $pagenum = 40;
    $from = ($page-1)*$pagenum;
    $is_china_flag = is_china();
    if ($is_china_flag == 1)
        $station_id = 2;
    else
        $station_id = 1;
    $sql = "SELECT board_id, article_id, read_num,
         user_numid, input_time FROM fenlei_zhandian_xinxi WHERE station_id=$station_id AND data_type=1 AND
         del_flag=0 AND displayflag=1 ORDER BY modify_time DESC LIMIT $from,$pagenum";
    $result = mysql_query($sql,$link);
    if($pagenum == mysql_num_rows($result))
        $end_flag = 0;
    else
        $end_flag = 1;
    $ret = array();
    while($row=mysql_fetch_array($result)) {
        $data = array();
        $data["groupID"]="";
        $data["articleID"] = "";
        $data["title"] = "";
        $data["href"] = "";
        $data["author"] = "";
        $data["BoardsName"] = "";
        $data["BoardsEngName"] = "";
        $data["boardID"] = "";
        $data["imgNum"] = "0";
        $data["imgList"] = array();
        $data["replyNum"] = "0";
        $data["readNum"] = "0";
        $data["replyNum"] = "";
        $data["readNum"] = "";
        $data["posttime"] = "";
        $sql1="select boardname,title,groupid,owner,
               total_reply,read_num,posttime,o_bid,o_id from dir_article_{$row['board_id']} where article_id=groupid and article_id={$row['article_id']}";
        $result1 = mysql_query($sql1,$link);
        if($result1 && $row1=mysql_fetch_array($result1)) {
            $data["title"] = $row1["title"];
            $data["author"] = $row1["owner"];
            if(!empty($row1["o_bid"])&&!empty($row1["o_id"])){
                $data["groupID"] = $row1["o_id"];
                $data["articleID"] = $row1["o_id"];
                $data["boardID"] = $row1["o_bid"];
                $readnum = $row1["read_num"];
                $totalreply = $row1["total_reply"];
                //add by baibing for get old information
                $sql_old = "select owner,read_num,total_reply from dir_article_{$row1["o_bid"]} where article_id=groupid and article_id={$row1["o_id"]}";
                $result_old = mysql_query($sql_old,$link);
                if ($result_old && $row_old = mysql_fetch_array($result_old)) {
                    $data["author"] = $row_old["owner"];
                    $readnum = $row_old["read_num"];
                    $totalreply = $row_old["total_reply"];
                }
                //add by baibing for get old information
            } else {
                $data["groupID"] = $row1["groupid"];
                $data["articleID"] = $row["article_id"];
                $data["boardID"] = $row["board_id"];
                $readnum = $row1["read_num"];
                $totalreply = $row1["total_reply"];
            }
            $limit["article_id"] =$row["article_id"];
            $limit["board_id"] =$row["board_id"];
            $data["replyNum"] = $totalreply;
            $data["readNum"] = $readnum;
            $data["posttime"] = $row1["posttime"];
        } else {
            continue;
        }
        if(in_array_list($limit, $limit_article)){
            continue;
        }

        $sql1="select  board_desc,boardname from board where board_id={$data["boardID"]}";
        $result1 = mysql_query($sql1,$link);
        while($row1=mysql_fetch_array($result1)){
            $data["BoardsName"] = $row1["board_desc"];
            $data["BoardsName"] = trim(substr($data["BoardsName"],strpos($data["BoardsName"],']')+1));
            $data["BoardsEngName"] = $row1["boardname"];
        }
        $data["href"] = url_generate(3, array("board"=>$data["BoardsEngName"], "groupid"=>$data["groupID"]));

        $sql1 = "SELECT new_url FROM article_image_list WHERE article_id={$row["article_id"]} and board_id=366 and  type=102";
        $num = 0;
        $result1 = mysql_query($sql1, $link);
        while($row1 = mysql_fetch_array($result1)) {
            $num ++;

            $img = BBS_HOME.'/pic_home/boards/TopArticle/'.$row1["new_url"];
            if(is_file($img)){
                $pic_list ="http://". $_SERVER["SERVER_NAME"]."/boardimg/TopArticle/".$row1["new_url"];
            }else{
                $pic_list = "";
            }
            $data["imgList"][] = $pic_list;
        }
        $data["imgNum"] = $num;
        $ret[] = $data;
    }

    return array($ret, $end_flag);
}

$all_arr = array();

//detail start
$str_article = '<ul class="article_wrap">';

list($top_article, $end_flag) = getTopArticle($link, $page, $articlelist);

setcookie("end_flag", (string)$end_flag, 0, "/");

foreach ($top_article as $each) {
    if ($each["imgNum"] == 1) {
        $str_article .= '<li class="article_list_singlepic">';
        $str_article .= '<a href="'.$each["href"].'">';
        $str_article .= '<img class="single_img" src="'.$each["imgList"][0].'" alt="pic"/>';
        $str_article .= '</a>';
        $str_article .= '<div class="content_list singlepic">';
        $str_article .= '<h4><a href="'.$each["href"].'" onclick="add_read_num(this)">'.$each["title"].'</a></h4>';
    } elseif ($each["imgNum"] == 0) {
        $str_article .= '<li class="article_list_nopic">';
        $str_article .= '<div class="content_list nopic">';
        $str_article .= '<h4><a href="'.$each["href"].'" onclick="add_read_num(this)">'.$each["title"].'</a></h4>';
    } else {
        $str_article .= '<li class="article_list_allpic">';
        $str_article .= '<div class="content_list allpic">';
        $str_article .= '<h4><a href="'.$each["href"].'" onclick="add_read_num(this)">'.$each["title"].'</a></h4>';
        $str_article .= '<ul class="img_list">';
        $i = 0;
        foreach ($each["imgList"] as $imgUrl) {
            if($i >= 3)
                break;
            $str_article .= '<li><a href="'.$each["href"].'" onclick="add_read_num(this)"><img src="'.$imgUrl.'" alt="pic"/></a></li>';
            $i++;
        }
        $str_article .= '</ul>';
    }

    $str_article .= '<p class="commen_p">';
    $str_article .= '<span>'.$each["author"].'</span>';
    $str_article .= '<span class="commen_margin">'.$each["boardsName"].'</span>';
    $str_article .= '<span class="commen_right">'.$each["replyNum"].'/'.$each["readNum"].'</span>';
    $str_article .= '</p></div></li>';


}
$str_article .= '</div>';
//detail end
$str_article = mb_convert_encoding($str_article, "UTF-8", "GBK");
$all_arr["detail"] = $str_article;
if ($page == 1)
    echo json_encode($all_arr);
else
    echo json_encode(array("article"=>$str_article));

mysql_close($link);
?>
