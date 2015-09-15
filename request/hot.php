<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

function getClubImg($club_name) {
    $filepath = BBS_HOME.'/pic_home/club/'.strtoupper(substr($club_name, 0, 1)).'/'.$club_name."/";
    $filename_return = "";
    if(is_dir($filepath)){
        $handler = opendir($filepath);
        while( ($filename = readdir($handler)) !== false )
        {
            if($filename != "." && $filename != ".." && $filename != "boardimg" && (strpos($filename,"boardimg") !== false))
            {
                $filename_return = $filename;
                break;
            }
        }
        if($filename_return == ""){
            $filename_return = "boardimg";
        }

        $filename_return = "/clubimg/".strtoupper(substr($club_name, 0, 1))."/".$club_name."/$filename_return";
        closedir($handler);
    }
    return $filename_return;
}

/* 获取十大热门话题 */
function getHotSubjects() {
    global $link;

    $is_china_flag = is_china();
    if($is_china_flag == 1)
        $country = "cn";
    else
        $country = "us";

    $ret = array();

    $brdarr = array();
    $xmlfile = BBS_HOME . "/xml/day_$country.xml";
    $results = read_xmlfile_content_web($xmlfile, 3);

    $count = 0;
    foreach ($results as $each) {
        if ($count >= 10)
            break;

        $data=array();
        $hot_title = $each['title'];
        $hot_author = $each['author'];
        $hot_board = $each['board'];
        $hot_groupid = $each['groupid'];


        //版面ID boardID 组ID groupID 文章ID articleID 文章标题 title 作者 author BoardsEngName  BoardsName
        $data["title"] = $hot_title;
        $data["author"] = $hot_author;

        $brdnum = bbs_getboard($hot_board, $brdarr);
        if ($brdnum == 0)
            continue;
        $data["boardsname"] = $brdarr["DESC"];
        $data["boardengname"] = $brdarr["NAME"];

        $sql = "select total_reply,read_num from dir_article_".$brdarr["BOARD_ID"]." where article_id=".$hot_groupid.";";
        $row = @mysql_fetch_array(mysql_query($sql, $link));
        if ($row)
            $data["popularity"] = $row["total_reply"].'/'.$row["read_num"];
        else
            $data["popularity"] = "0/0";

        $data["href"] = url_generate(3, array("board" => $brdarr["NAME"], "groupid" => $hot_groupid));

        $ret[] = $data;
        $count++;
    }

    return $ret;
}

/* 获取十大推荐文章 */
function getRecommendArticle() {

    global $link;
    if(is_china() == 1)
        $country="cn";
    else
        $country="us";

    $brdarr = array();
    $xmlfile = BBS_HOME . "/xml/commend_$country.xml";
    $results = read_xmlfile_content_web($xmlfile, 3);

    $ret=array();

    $count = 0;
    foreach ($results as $each) {
        if ($count >= 10)
            break;

        $data=array();
        $commend_title = $each['title'];
        $commend_author = $each['author'];
        $commend_board = $each['board'];
        $commend_groupid = $each['groupid'];


        //版面ID boardID 组ID groupID 文章ID articleID 文章标题 title 作者 author BoardsEngName  BoardsName
        $data["title"] = $commend_title;
        $data["author"] = $commend_author;

        $brdnum = bbs_getboard($commend_board, $brdarr);
        if ($brdnum == 0)
            continue;
        $data["boardsname"] = $brdarr["DESC"];
        $data["boardengname"] = $brdarr["NAME"];

        $sql = "select total_reply,read_num from dir_article_".$brdarr["BOARD_ID"]." where article_id=".$commend_groupid.";";
        $row = @mysql_fetch_array(mysql_query($sql, $link));
        if ($row)
            $data["popularity"] = $row["total_reply"].'/'.$row["read_num"];
        else
            $data["popularity"] = "0/0";

        $data["href"] = url_generate(3, array("board" => $brdarr["NAME"], "groupid" => $commend_groupid));

        $ret[] = $data;
        $count++;
    }

    return $ret;
}

/* 获取热门版面 */
function getHotBoards() {
    $ret=array();

    $xmlfile = BBS_HOME . '/xml/hot_board.xml';
    $results = read_xmlfile_content_web($xmlfile, 3);
    foreach($results as $each) {
        $oneboard=array();
        $board_c = $each['board'];
        $board_desc = $each['board_desc'];
        $oneboard["BoardsName"] = iconv("GBK","UTF-8//IGNORE",urldecode($board_desc));
        $oneboard["BoardsEngName"] = $board_c;
        $brdarr = array();
        bbs_getboard($board_c, $brdarr);

        $t_element = array();
        $t_element["href"] = url_generate(2, array("board"=>$board_c));

        $t_element["img"] = "";
        $t_element["des"] = $board_c;
        $t_element["name"] = $board_desc;
        $t_element["online"] = $brdarr["CURRENTUSERS"];
        $t_element["total"] = $brdarr["TOTAL"];
        $ret[]=$t_element;
    }
    //$msg["data"]=$ret;
    return $ret;
}

/* 获取热门俱乐部 */
function getHotClubs() {
    global $link;
    $ret = array();
    $denyclubfilename="/home/bbs/etc/denyclub";
    $fp = @fopen($denyclubfilename, "r");
    $notInArray = array();
    if ($fp != false) {
        while (!feof($fp)) {
            $buffer = trim(fgets($fp, 300));
            if(strlen($buffer) > 0) {
                $notInArray[] = $buffer;
            }
        }
        fclose($fp);
    }

    $notIn="";
    if(count($notInArray) > 0) {
        $notIn=" and club_name not in ( ";
        $i=0;
        foreach($notInArray as $item) {
            if($i == 0)
                $notIn = $notIn."'".$item."'";
            else
                $notIn = $notIn.",'".$item."'";

            $i++;
        }
        $notIn = $notIn.")";
    }

    $club_sql = "select club_id,club_name,club_cname,club_description,post_sum,onlines from club where approval_state=1 and club_type=1 {$notIn} ";
    if (is_china() == 1)
        $club_sql .= " and limit_flag=0 ";
    $club_sql .= " order by currentScore desc limit 10";

    $club_result = mysql_query($club_sql, $link);
    while($row = mysql_fetch_array($club_result)) {
        // 生成俱乐部对应的url
        $club_url = url_generate(2, array("club" => $row["club_id"]));

        // 俱乐部首页图片路径
        $club_img = getClubImg($row["club_name"]);
        $ret[] = array(
            'href' => $club_url,
            'img' => $club_img,
            'name' => $row["club_cname"], //iconv("GBK", "UTF-8//IGNORE", urldecode($board_desc)),
            'des' => $row["club_description"],
            'online' => $row['onlines'],
            'post_sum' => $row["post_sum"]
        );
    }
    mysql_free_result($club_result);

//    var_dump($ret);
    return $ret;
}

$all_arr=array();
$type="hot";
$str_content = '<div id="detail"><ul class="hot_recommend">';

/* 十大热门话题 */
$t_data = array();
$t_data = getHotSubjects();

$str_content .= '<li class="hot_li">';
$str_content .= '<h3 class="hot_title border_bottom">十大热门话题<span class="hot_trigle"></span></h3>';
$str_content .= '<div class="hot_list_wrap border_bottom">';

$topic_num = count($t_data);
if ($topic_num > 0) {
    if ($topic_num > 1) {
        for ($i = 0; $i < $topic_num-1; $i++) {
            $str_content .= '<div class="content_list nopic padding10">';
            $str_content .= '<h4><a href="'.$t_data[$i]["href"].'">'.$t_data[$i]["title"].'</a></h4>';
            $str_content .= '<p class="commen_p padding-bottom border_bottom">';
            $str_content .= '<span>'.$t_data[$i]["author"].'</span>';
            $str_content .= '<span class="commen_margin">'.$t_data[$i]["boardsname"].'</span>';
            $str_content .= '<span class="commen_right ">'.$t_data[$i]["popularity"].'</span>';
            $str_content .= '</p></div>';
        }
    }
    $str_content .= '<div class="content_list nopic padding10 noborder_bottom">';
    $str_content .= '<h4><a href="'.$t_data[$topic_num-1]["href"].'">'.$t_data[$topic_num-1]["title"].'</a></h4>';
    $str_content .= '<p class="commen_p padding-bottom">';
    $str_content .= '<span>'.$t_data[$topic_num-1]["author"].'</span>';
    $str_content .= '<span class="commen_margin">'.$t_data[$topic_num-1]["boardsname"].'</span>';
    $str_content .= '<span class="commen_right ">'.$t_data[$topic_num-1]["popularity"].'</span>';
    $str_content .= '</p></div>';
}

/* 十大推荐文章 */
$t_data = array();
$t_data = getRecommendArticle();

$str_content .= '<li class="hot_li">';
$str_content .= '<h3 class="hot_title border_bottom">十大推荐文章<span class="hot_trigle"></span></h3>';
$str_content .= '<div class="hot_list_wrap border_bottom">';

$topic_num = count($t_data);
if ($topic_num > 0) {
    if ($topic_num > 1) {
        for ($i = 0; $i < $topic_num-1; $i++) {
            $str_content .= '<div class="content_list nopic padding10">';
            $str_content .= '<h4><a href="'.$t_data[$i]["href"].'">'.$t_data[$i]["title"].'</a></h4>';
            $str_content .= '<p class="commen_p padding-bottom border_bottom">';
            $str_content .= '<span>'.$t_data[$i]["author"].'</span>';
            $str_content .= '<span class="commen_margin">'.$t_data[$i]["boardsname"].'</span>';
            $str_content .= '<span class="commen_right ">'.$t_data[$i]["popularity"].'</span>';
            $str_content .= '</p></div>';
        }
    }
    $str_content .= '<div class="content_list nopic padding10 noborder_bottom">';
    $str_content .= '<h4><a href="'.$t_data[$topic_num-1]["href"].'">'.$t_data[$topic_num-1]["title"].'</a></h4>';
    $str_content .= '<p class="commen_p padding-bottom">';
    $str_content .= '<span>'.$t_data[$topic_num-1]["author"].'</span>';
    $str_content .= '<span class="commen_margin">'.$t_data[$topic_num-1]["boardsname"].'</span>';
    $str_content .= '<span class="commen_right ">'.$t_data[$topic_num-1]["popularity"].'</span>';
    $str_content .= '</p></div>';
}

/* 当前热门版面 */
$t_data = getHotBoards();

$str_content .= '<li class="hot_li">';
$str_content .= '<h3 class="hot_title border_bottom">当前热门版面<span class="hot_trigle"></span></h3>';
$str_content .= '<div class="hot_list_wrap border_bottom">';

$topic_num = count($t_data);
if ($topic_num > 0) {
    for ($i = 0; $i < $topic_num; $i++) {
        $str_content .= '<div class="content_list_wrap padding10 border_bottom padding-bottom">';
        $str_content .= '<a href="'.$t_data[$i]["href"].'">';
        $str_content .= '<img class="hot_li_img" src="'.$t_data[$i]["img"].'" alt="board.png"/>';
        $str_content .= '<div class="hot_content">';
        $str_content .= '<h3 class="hot_name">'.$t_data[$i]["name"].'</h3>';
        $str_content .= '<p class="hot_des">'.$t_data[$i]["des"].'</p>';
        $str_content .= '<p class="hot_count"><span class="hot_left">当前在线 : '.$t_data[$i]["online"].'人</span><span class="hot_right">文章总数 : '.$t_data[$i]["total"].'</span></p>';
        $str_content .= '</div></a></div>';
    }
}

/* 当前热门俱乐部 */
// getHotClubs();
$t_data = array();

$t_data = getHotClubs();

$str_content .= '<li class="hot_li">';
$str_content .= '<h3 class="hot_title border_bottom">当前热门俱乐部<span class="hot_trigle"></span></h3>';
$str_content .= '<div class="hot_list_wrap border_bottom">';

$topic_num = count($t_data);
if ($topic_num > 0) {
    for ($i = 0; $i < $topic_num; $i++) {
        $str_content .= '<div class="content_list_wrap padding10 border_bottom padding-bottom">';
        $str_content .= '<a href="'.$t_data[$i]["href"].'">';
        $str_content .= '<img class="hot_li_img" src="'.$t_data[$i]["img"].'" alt="club.png"/>';
        $str_content .= '<div class="hot_content">';
        $str_content .= '<h3 class="hot_name">'.$t_data[$i]["name"].'</h3>';
        $str_content .= '<p class="hot_des">'.$t_data[$i]["des"].'</p>';
        $str_content .= '<p class="hot_count"><span class="hot_left">当前在线 : '.$t_data[$i]["online"].'人</span><span class="hot_right">文章总数 : '.$t_data[$i]["post_sum"].'</span></p>';
        $str_content .= '</div></a></div>';
    }
}

$str_content .= '</div>';
// json_encode()转换的字符串含的中文必须为UTF-8编码
$all_arr["detail"] = iconv('GBK', 'UTF-8', $str_content);
echo json_encode($all_arr);
mysql_close($link);
?>

