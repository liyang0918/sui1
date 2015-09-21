<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

function getHeadLineNews($link, $page) {
    $pagenum = 40;
    $from = ($page-1)*$pagenum;
    $is_china_flag = is_china();
    if ($is_china_flag == 1)
        $station_id = 2;
    else
        $station_id = 1;
    $sql="SELECT board_id, article_id FROM fenlei_zhandian_xinxi
      WHERE station_id=$station_id AND data_type=4 AND del_flag=0 ORDER BY modify_time DESC LIMIT $from, $pagenum";
    $result = mysql_query($sql, $link);
    if(mysql_num_rows($result) < 40)
        $end_flag = 1;
    else
        $end_flag = 0;

    $ret = array();
    while($row = mysql_fetch_array($result)) {
        $aNew["boardID"] = $row["board_id"];
        $aNew["articleID"] = $row["article_id"];
        $aNew["groupID"] = "";
        $aNew["href"] = "";
        $aNew["author"] = "";
        $aNew["title"] = "";
        $aNew["BoardsName"] = "";
        $aNew["notes"] = "";
        $aNew["BoardsEngName"] = "";
        $aNew["postTime"]="";
        $aNew["imgList"] = array();
        $aNew["imgNum"]="";
        $aNew["source"] = "未名空间";
        $aNew["read_num"] = "0";
        $aNew["total_reply"] = "0";
        $sql1="select boardname,title,groupid,owner,filename,
           total_reply,read_num,UNIX_TIMESTAMP(posttime),source from dir_article_{$row['board_id']} where article_id={$row['article_id']}";
        $result1 = mysql_query($sql1, $link);
        while ($row1=mysql_fetch_array($result1)) {
            $aNew["groupID"] = $row1["groupid"];
            $aNew["title"] = $row1["title"];
            if($aNew["title"] == null){
                $aNew["title"] = "";
            }
            $aNew["author"] = $row1["owner"];
            $aNew["BoardsEngName"] = $row1["boardname"];
            $aNew["total_reply"] = getNewsReply($link, $row["board_id"], $row1["groupid"]);
            $aNew["read_num"] = $row1["read_num"];
            $aNew["postTime"] = $row1["posttime"];
            $aNew["href"] = url_generate(3, array("board"=>$row1["boardname"], "groupid"=>$aNew["groupID"]));
            $filepath = BBS_HOME."/boards/".$aNew["BoardsEngName"]."/".$row1["filename"];
            $filehandle = fopen($filepath, "r");

            if($filehandle){
                for($i = 0; $i < 4; $i++)
                    fgets($filehandle);
                while ($notes = fgets($filehandle)) {
                    if (!strncmp($notes, "--", 2)) {
                        $notes = "1";//end
                        break;
                    } else if (!strncmp($notes, "http://", 7) or $notes == "\n") {
                        continue;
                    }else if(strpos(iconv("GBK", "UTF-8//IGNORE", $notes), "编者按") !== false){
                        if(strlen($notes) >= 30){
                            break;//ok,it is
                        }else{
                            while($notes1 = fgets($filehandle)){
                                if(strlen($notes.$notes1) >= 30){
                                    $notes .= $notes1;
                                    break;
                                }else if(!strncmp($notes1, "--", 2) or !strncmp($notes1, "http://", 7)){
                                    break;
                                }else{
                                    $notes .= $notes1;
                                }
                            }
                            break;
                        }
                    } else {
                        if(!isset($notestmp)){
                            $notestmp = $notes;
                        }
                    }
                }
            } else
                $notes="0";

            if($notes==1)
                $notes=$notestmp;
            else
                $notes="";

            unset($notestmp);
            $arr=explode("。",  $notes);
            $aNew["notes"] = str_replace("编者按：","", $arr[0]);
            $postdate = "".$row1["UNIX_TIMESTAMP(posttime)"];
            $nowdate = strtotime(date('Y/m/d'));

            $aNew["postTime"] = $postdate;
            if($row1["source"] != null)
                $aNew["source"] = $row1["source"];
            else
                $aNew["source"] = "未名空间";
        }
        $sql1="select  board_desc from board where board_id={$row['board_id']}";
        $result1 = mysql_query($sql1, $link);
        while($row1=mysql_fetch_array($result1)){
            $aNew["BoardsName"] = $row1["board_desc"];
            $aNew["BoardsName"] = trim(substr($aNew["BoardsName"],strpos($aNew["BoardsName"],']')+1));
        }
        $sql = "SELECT new_url FROM article_image_list WHERE article_id={$aNew["articleID"]} and board_id={$aNew["boardID"]}";
        $num1 = "0";
        $result1 = mysql_query($sql, $link);
        $aNew["imgList"] = array();
        while($row1 = mysql_fetch_array($result1)) {
            $num1 ++;
            $img = BBS_HOME.'/pic_home/boards/'.$aNew["BoardsEngName"]."/".$row1["new_url"];
            if(is_file($img)){
                $pic_list ="http://". $_SERVER["SERVER_NAME"]."/boardimg/" . $aNew["BoardsEngName"]. "/".$row1["new_url"];
            }else{
                $pic_list = "";
            }
            $aNew["imgList"][] = $pic_list;
        }
        $aNew["imgNum"] = $num1;
        $ret[] = $aNew;
    }

    return array($ret, $end_flag);
}

list($head_line_news, $end_flag) = getHeadLineNews($link, $page);
setcookie("end_flag", (string)$end_flag, 0, "/");
$str_article = '<div class="news_list_content" id="detail">';
$str_article .= '<ul class="new_list_content_listbox">';
foreach ($head_line_news as $each) {
    if ($each["imgNum"] <= 1) {
        $str_article .= '<li class="news_ltems news_list_lione">';
        if ($each["imgNum"] == 1)
            $str_article .= '<img src="'.$each["imgList"][0].'" alt="img">';
        $str_article .= '<div class="lione_r_box">';
        $str_article .= '<a href="'.$each["href"].'"><h3>'.$each["title"].'</h3></a>';
        $str_article .= '<a href="'.$each["href"].'"><p>'.$each["notes"].'</p></a>';
        $str_article .= '</div>';
        $str_article .= '<span class="critize right_b">'.$each["total_reply"].'评论</span>';
    } else {
        $str_article .= '<li class="news_ltems news_list_litwo">';
        $str_article .= '<a href="'.$each["href"].'"><h3>'.$each["title"].'</h3></a>';
        $str_article .= '<ul class="litwo_box">';
        for ($i = 0; $i < $each["imgNum"]-1; $i++)
            $str_article .= '<li><a href="'.$each["href"].'"><img src="'.$each["imgList"][$i].'" alt="img"></a></li>';

        $str_article .= '<li class="margin_right"><a href="'.$each["href"].'"><img src="'.$each["imgList"][$i].'" alt=""></a></li>';
        $str_article .= '</ul>';
        $str_article .= '<span class="critize right_t">'.$each["notes"].'评论</span>';
    }

    $str_article .= '</li>';
}


$str_article .= "</ul></div>";
//detail end
$str_article = mb_convert_encoding($str_article, "UTF-8", "GBK");
$all_arr["detail"] = $str_article;
if ($page == 1)
    echo json_encode($all_arr);
else
    echo json_encode(array("article"=>$str_article));
mysql_close($link);
?>
