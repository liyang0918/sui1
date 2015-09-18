<?php
$php_page_arr=array(
    "/mobile/forum/index.php"=>"首页"
    ,"b"=>"2"
    ,"c"=>"3"
);
function get_row_count($board_id,$article_id,$conn){
    $sql = "SELECT COUNT(*) FROM dir_article_" . $board_id.
        " WHERE groupid=".$article_id;
    $ret=mysql_query($sql,$conn);
    $row=mysql_fetch_array($ret);
    mysql_free_result($ret);
    return $row[0];
}
function trans_content_html($str){
    $arr=array();

    $arr=explode("\n",$str);
    $img_html="";
    $content_html="";
    foreach ( $arr as $content) {
        if(strpos($content,"http://")!==false){
            $img_html.="<a target=\"_blank\" href=\"".$content."\">";
            $img_html.="\n";
            $img_html="<img src=\"".$content."\">";
            $img_html.="\n";
            $img_html.="</a>";
        }else{
            if($content_html!="")
               $content_html.="<br>";
            $content_html.=$content;
        }
    }
    return $content_html.$img_html;
}
function wap_read_article($filepath, $attach_link, $img_ago_str, $img_after_str, $attach_linkstr, $show_ad_line, $articlType = 1, $opflag = 0, $mailtype = 0, &$attachinfo) {
    define ("MAXATTACHMENTCOUNT", 20);
    $UBB_Control = chr(27); //0x1b
    $attach_start_pos = 0;
// echo $filepath."<hr />";
    $content_array = "";
    $content_attach = "";
    if (is_file($filepath)) {
        $content_tmp = file_get_contents($filepath);

        if ($attach_link && $articlType != 2) {
            $attach_start_pos = strpos($content_tmp, "\0\0\0\0\0\0\0\0");
            if ($attach_start_pos === false) {
                $content_array = explode("\n", $content_tmp);
            } else {
                $content_array = substr($content_tmp, 0, $attach_start_pos);
                $content_attach = substr($content_tmp, $attach_start_pos);
                $content_array = explode("\n", $content_array);
            }
        } else
            $content_array = explode("\n", $content_tmp);
    } else {
        //wglog("read_news    ".$filepath." not found the file");
    }

//分析文件的非附件部分
    $lines = count($content_array);
//wglog("read_news  lines is   $lines");
    $content_str = "";
    if ($articlType == 1) {
        $firsLine = 4;
    } else if ($articlType == 2 || $articlType == 3) {
        $firsLine = 4;
    }
    for ($j = $firsLine; $j < $lines; $j++) {
        if ($firsLine == 4 && $content_array[$j] == "--"&&$articlType!=1) {
            //结尾部分
            $tmp = mit_iconv($content_array[$j + 1]);
            $tmp1 = mit_iconv($content_array[$j + 2]);
            if (strpos($tmp, "来源") || strpos($tmp1, "来源") || strpos($tmp1, "修改") || strpos($tmp, "修改")) {
                if ($mailtype != 1) {
                    break;
                }
            }
            if ($articlType == 2) {
                break;
            }
        }

        if ($firsLine == 4 && strpos($content_array[$j], "※ ")) {
            if ($mailtype != 1||$articlType==1) {
                break;
            }
        }
        if ($articlType==1&&$content_array[$j]=="--")
            break;

        //UBB控制码处理
        $tmplen = strlen($content_array[$j]);
        $tmpstr = "";
        $UBB_Control_Flag = 0;
        for ($ii = 0; $ii < $tmplen; $ii++) {
            if ($UBB_Control_Flag) {
                if (($content_array[$j][$ii] >= 'a' && $content_array[$j][$ii] <= 'z') || ($content_array[$j][$ii] >= 'A' && $content_array[$j][$ii] <= 'Z'))
                    $UBB_Control_Flag = 0;
            } else {
                if ($content_array[$j][$ii] == $UBB_Control)
                    $UBB_Control_Flag = 1;
                else {
                    $tmpstr = $tmpstr . $content_array[$j][$ii];
                }
            }
        }
        $content_array[$j] = $tmpstr;
        /*
        *add by baibing at 20140513
        *code:baibing-0707
        */
        $pic_num=0;
        if (strcmp(substr($tmpstr, 0, 7), "http://") == 0) {
            ++$pic_num;
            $pic_list[] = $tmpstr;
        }
        /*
        *end add by baibing at 20140513
        *code:baibing-0707
        */

        if (($j > $firsLine && strlen($content_array[$j - 1]) == 76) //上一行为76个字符&&当前行以空格或者tab开头，补回换行
            && (substr_compare($content_array[$j], '  ', 1, 2) == 0
                || substr_compare($content_array[$j], "\t", 1, 1) == 0
                || substr_compare($content_array[$j], '  ', 1, 2) == 0)
        ) {
            $content_str = $content_str . "\n";
        }

        if (strlen($content_array[$j]) == 76) { //web系统处理时76个字符换一行，现在去掉
            $content_str = $content_str . $content_array[$j];
        } else {
            $content_str = $content_str . $content_array[$j] . "\n";
        }

        // $content_str = $content_str.$content_array[$j]."\n";
        //UBB处理end
        continue;
    }

//分析文件的附件部分
    if ($articlType != 2 && $attach_link) {
        $attach_count = 0;
        $search_start_pos = 0;

        while (1) {
            $currattach_start_pos = strpos($content_attach, "\0\0\0\0\0\0\0\0", $search_start_pos);
            if ($currattach_start_pos === false)
                break;

            if ($attach_count >= MAXATTACHMENTCOUNT)
                break;
            $tmp_i = strpos($content_attach, "\0", $currattach_start_pos + 8);
            if ($tmp_i === false)
                break;
            else {
                $tmp_array = unpack('Nsize', substr($content_attach, $tmp_i + 1, 4));
                $search_start_pos = $tmp_i + 4 + $tmp_array["size"];
                $attach_filename = substr($content_attach, $currattach_start_pos + 8, $tmp_i - $currattach_start_pos - 8);
                if ($attach_filename) {
                    $attach_pos = $attach_start_pos + $currattach_start_pos + 8;
                    $attachinfo["size"][] = $tmp_array["size"] + $tmp_i - ($currattach_start_pos + 8) + 5;
                    $attachinfo["pos"][] = $attach_pos;

                    if (is_pic_web($attach_filename)) //IMG
                    {
                        //$linkstr = $attach_linkstr. "_" . $attach_pos.getWebPicType($attach_filename);
                        $linkstr = $attach_linkstr . "_" . $attach_pos . ".jpg";

                        $content_str = $content_str . " \n$linkstr";
                        /*
                         *add by baibing at 20140513
                         *code:baibing-0707
                         */

//                            ++$pic_num;
//                            $pic_list[] = $linkstr;
                        /*
                        *end add by baibing at 20140513
                        *code:baibing-0707
                        */
                    } else //OTHERS
                    {
                        $para_array = explode("/", substr($attach_linkstr, 1));
                        if ($opflag)
                            $linkstr = "http://" . $_SERVER['HTTP_HOST'] . "/mitbbs_article.php?board=" . $para_array[1] . "&id=" . $para_array[2] . "&ap=" . $attach_pos . "&opflag=1";
                        else
                            $linkstr = "http://" . $_SERVER['HTTP_HOST'] . "/mitbbs_article.php?board=" . $para_array[1] . "&id=" . $para_array[2] . "&ap=" . $attach_pos;

                        $content_str = $content_str . $linkstr;
                        /*
                        *add by baibing at 20140513
                        *code:baibing-0707
                        */
//                        ++$pic_num;
//                        $pic_list[] = $linkstr;
                        /*
                        *end add by baibing at 20140513
                        *code:baibing-0707
                        */
                    }
                    $attach_count++;
                }
            }
        }
    }
    $ret_str[1] = $content_str;
    return $ret_str;
}
function get_file_content($filename,$att_flag,$board_name,$article_id,$article_type,&$att_arr){
    if(false==$filename){
        $ret_str="未名提示:由于某些不明原因,该文章未能正确读取,请稍后再刷新重试!";
        return $ret_str;
    }
    $attachlink_rewrite = "http://" . $_SERVER['HTTP_HOST'] . "/article2/" . $board_name. "/" . $article_id;
    $ret_str= wap_read_article(BBS_HOME . "/$filename", $att_flag, "", "", $attachlink_rewrite, 0, $article_type, 0, 0, $att_arr);
    return $ret_str;
}
function check_board_filename($board_name,$filename){
    $file=bbs_get_board_filename($board_name,$filename);
    if(is_file($file))
        return $file;
    else
        return false;

}
function get_user_img($user_id){
    $headimg = BBS_HOME . '/pic_home/home/' . strtoupper(substr($user_id, 0, 1)) . '/' . $user_id . '/headimg';
    if (!is_file($headimg)) {
        $url_img="";
    } else {
//                $newArticle["headimgURL"] = "http://".$_SERVER['SERVER_NAME']."/picture/".strtoupper(substr($newArticle["author"],0,1))."/".$newArticle["author"]."/headimg";
        $url_img= "http://" . $_SERVER['SERVER_NAME'] . "/picture/" . strtoupper(substr($user_id, 0, 1)) . "/" . $user_id . "/headimg";
    }
    return $url_img;
}
function get_page_title(){
    global $php_page_arr;
   if(array_key_exists($_SERVER["PHP_SELF"],$php_page_arr)){
       return $php_page_arr[$_SERVER["PHP_SELF"]];
   }else
       return "错误的页面对应关系";
}
function wap_error_quit($err_msg,$head=1){
global $country_name;
global $adult;
global $conn;
global $currentuser;
global $gamedomain;
html_init("gb2312");
//$pre_url=$_SERVER["HTTP_REFERER"];
$pre_url = $_SERVER['REQUEST_URI'];
@mysql_close();

?>
<div align="center">
    <table width="778" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" valign="middle" class="logo-bg">
                <div align="center">
                    <p>&nbsp;</p>
                    <?php if ($err_msg == WWW_NOLOGIN_MSG) { ?>
                        <p>你还没有登陆，或者你发呆时间过长被服务器清除。 请重新<a
                                href='/<?php if (1 == is_china()) echo "index_login.php"; else echo "mitbbs_login.php"; ?>?pre_url=<?php echo(urlencode($pre_url)); ?>'
                                class='top_red'>登录</a>!</p>
                    <?php } else if ($err_msg == WWW_NOLOGIN_ADULT_MSG) {
                        create_adult_herf($adult);
                        ?>
                        <p>你还没有登陆，或者你发呆时间过长被服务器清除。 请重新<a href="#"
                                                         onclick="javascript:myhref('/mitbbs_login.php?pre_url=<?php echo(urlencode($pre_url)); ?>');"
                                                         class='top_red'>登录</a>!</p>
                    <?php } else if ($err_msg == WWW_NOLOGIN_CN_MSG) { ?>
                        <p>您尚未登录或已经过期，不能访问该页,重新<a
                                href='/<?php if (strstr($_SERVER['SCRIPT_URL'], "/mobile/")) echo "/mobile/mlogin.php"; else echo "index_login.php"; ?>'
                                class='top_red'>登录</a>!</p>
                        <?php //pre_url=php echo(urlencode($_SERVER["SCRIPT_URL"])); 暂时先去掉 post 数据无法过去 有些页面会提示无法获取参数
                        ?>
                    <?php } else if ($err_msg == WWW_NOLOGIN_CN_GAME) { ?>
                        <p>
                            您的贡献率太低，不能浏览网站，但是可以玩游戏<?php if (!strstr($_SERVER['SCRIPT_URL'], "/mobile/")) echo "<a href=\"http://$gamedomain/game/mitbbs_game.php\" class='top_red'>进入游戏</a>"; ?></p>
                        <?php //pre_url=php echo(urlencode($_SERVER["SCRIPT_URL"])); 暂时先去掉 post 数据无法过去 有些页面会提示无法获取参数
                        ?>
                    <?php } else { ?>
                        <p><?php echo $err_msg; ?>!</p>
                    <?php } ?>
                    <br><br>
                    [<a href="javascript:history.go(-1)" class="headlink">快速返回</a>]
                    <p>&nbsp;</p>
                </div>
            </td>
        </tr>
    </table>
    <script type="text/javascript">if (top.location !== self.location) top.location = self.location;</script>
    <?php
    include("foot.php");
    exit;
}
function get_add_textarea_context ($filename,$user) {
    $ret_str="";
        if (file_exists($filename)) {
            $fp = fopen($filename, "r");
            if ($fp) {
                $lines = 0;
                $buf = fgets($fp, 256);       /* 取出第一行中 被引用文章的 作者信息 */
                $end = strrpos($buf, ")");
                $start = strpos($buf, ":");
                if ($start != FALSE && $end != FALSE)
                    $quser = substr($buf, $start + 2, $end - $start - 1);

                $ret_str="\n\n【 在 " . $user . " 的大作中提到: 】\n";
                for ($i = 0; $i < 3; $i++) {
                    if (($buf = fgets($fp, 500)) == FALSE)
                        break;
                }

                while (1) {
                    if (($buf = fgets($fp, 500)) == FALSE)
                        break;
                    if (strncmp($buf, "--\n", 3) == 0)
                        break;
                    if (strncmp($buf, "【", 2) == 0)
                        continue;
                    if (strncmp($buf, ": ", 2) == 0)
                        continue;
                    if (strncmp($buf, "\n", 1) == 0) {
                        continue;
                    }
                    if (++$lines > 10) {
                        $ret_str.=": ...................\n";
                        break;
                    }
                    if (stristr($buf, "</textarea>") == FALSE)  //filter </textarea> tag in the text
                        $buf = str_replace('<img src="', '', $buf);
                    $buf = str_replace('.gif">', '.gif', $buf);
                    $buf = str_replace('.jpg">', '.jpg', $buf);
                    $buf = str_replace('.png">', '.png', $buf);
                    $buf = str_replace('.jpeg">', '.jpeg', $buf);
                    $ret_str.="<br>: $buf";
                }
            }
            $ret_str.="<br>";
            fclose($fp);
        }
    return $ret_str;
}

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
            $filename_return = "clubimg";
        }

        $filename_return = "/clubimg/".strtoupper(substr($club_name, 0, 1))."/".$club_name."/$filename_return";
        closedir($handler);
    }
    return $filename_return;
}

function getBoardImg($board_name) {
    return "/boardimg/".$board_name."/boardimg";
}

$label_list = array(
    "index" => array("top", "hot", "classical", "classes"),
    "news" => array("mix", "military", "international", "sport", "recreation", "science", "finance"),
    "club" => array(),
    "immigration" => array(),
    "dianping" => array()
);

function is_own_label($label, $own="index") {
    global $label_list;
    if(!array_key_exists($own, $label_list))
        return false;

    if(in_array($label, $label_list[$own]))
        return true;
    else
        return false;
}

$forum_class_list = array(
    "1" => array("section_name"=>"新闻中心", "section_num"=>"1"),
    "2" => array("section_name"=>"海外生活", "section_num"=>"2"),
    "3" => array("section_name"=>"华人世界", "section_num"=>"3"),
    "4" => array("section_name"=>"体育健身", "section_num"=>"4"),
    "5" => array("section_name"=>"休闲娱乐", "section_num"=>"5"),
    "6" => array("section_name"=>"情感杂想", "section_num"=>"6"),
    "7" => array("section_name"=>"文学艺术", "section_num"=>"7"),
    "8" => array("section_name"=>"校友联谊", "section_num"=>"8"),
    "9" => array("section_name"=>"乡里乡情", "section_num"=>"9"),
    "10" => array("section_name"=>"电脑网络", "section_num"=>"a"),
    "11" => array("section_name"=>"学术学科", "section_num"=>"b"),
    "12" => array("section_name"=>"本站系统", "section_num"=>"0")
);

function getClassName($class) {
    global $forum_class_list;
    switch($class) {
        case "1":
        case "2":
        case "3":
        case "4":
        case "5":
        case "6":
        case "7":
        case "8":
        case "9":
            return $forum_class_list[$class]["section_name"];
        case "a":
            return $forum_class_list["10"]["section_name"];
        case "b":
            return $forum_class_list["11"]["section_name"];
        case "0":
            return $forum_class_list["12"]["section_name"];
        default:
            return "";
    }
}

function url_generate($level, $data) {
    /*  level 分为4级
     *      1: 分类
     *          array("class"=>$class_num)
     *      2: 版面/俱乐部
     *          版面：array("board"=>$board_name)
     *          俱乐部：array("club"=>$club_name)
     *      3: 分组
     *          array("board"=>$board_name, "group"=>$group_id)
     *      4: 保留
     * */
    $url = "/404.html";
    // 注: type可以为空
    switch($level) {
        case 1:
            if (isset($data["class"]))
                $url = 'one_class.php?type='.$data["type"].'&class='.$data["class"];
                break;
        case 2:
            if (isset($data["board"]))
                $url = 'one_board.php?type='.$data["type"].'&board='.$data["board"];
            else if (isset($data["club"]))
                $url = 'one_club.php?type='.$data["type"].'&club='.$data["club"];

            break;
        case 3:
            if(isset($data["groupid"])) {
                if (isset($data["board"]))
                    // 论坛内文章的跳转地址
                    $url = 'one_group.php?type='.$data["type"].'&board='.$data["board"].'&group='.$data["groupid"];
                else if (isset($data["club"]))
                    // 俱乐部文章跳转地址
                    $url = 'one_group_club.php?type='.$data["type"].'&club='.$data["club"].'&group='.$data["groupid"];
            }

            break;
        default:
            break;
    }

    return $url;
}

/*
// 页码太多
function page_partition($total_row, $page, $per_page=10, $show_page=3) {
    $total_page = intval($total_row/$per_page)+1;
    $frt_page = 1;
    $end_page = $total_page;
?>
    <div id="page_part" class="pages_box margin-bottom">
        <?php if ($page != 1) {?>
        <a id="pre_page" href="">上一页</a>
        <a id="frt_page" href=""><?php echo $frt_page;?></a>
    <?php }else{?>
        <a id="page_now" href=""><?php echo $frt_page;?></a>
    <?php }?>

    <?php
    if($page-$frt_page > $show_page+1){
        echo "<span>...</span>";
    }?>

    <?php $i = 1;
    while ($i <= $show_page) {
        if ($page-$show_page+$i-1 > 1) {
            ?>
            <a href=""><?php echo $page-$show_page+$i-1;?></a>
        <?php }$i++;}?>

    <?php if ($page!=1 && $page!=$end_page) {?>
        <a id="page_now" href=""><?php echo $page;?></a>
    <?php }?>

    <?php $j = 1;
    while($j<= $show_page) {
        if($page+$j < $end_page){
            ?>
            <a href=""><?php echo $page+$j;?></a>
        <?php }$j++;}
    if($end_page-$page > $show_page+1){
        echo "<span>...</span>";
    }?>

    <?php if($page != $end_page){?>
        <a id="end_page" href=""><?php echo $end_page;?></a>
        <a id="sub_page" href="">下一页</a>
    <?php }elseif($frt_page != $end_page){?>
        <a id="page_now" href=""><?php echo $end_page;?></a>
    <?php }?>

    </div><!-- End pages_box   每页显示20篇-->
<?php
}
*/

/* 只保留3个按钮   ... 5 6 7 ...
 *               当前页 ^
*/
function page_partition($total_row, $page, $per_page=10, $show_page=1) {
    $total_page = intval($total_row/$per_page)+1;
    $frt_page = 1;
    $end_page = $total_page;
    $page_str = '<div id="page_part" class="pages_box margin-bottom">';
    if ($page != 1) {
        $page_str .= '<a id="pre_page" href="">上一页</a>';
    }

    if($page-$frt_page > $show_page){
        $page_str .= "<span>...</span>";
    }

    $page_str .= '<a id="page_now" href="">'.$page.'</a>';

    if($end_page-$page > $show_page){
        $page_str .= "<span>...</span>";
        }

        if($page != $end_page){
            $page_str .= '<a id="sub_page" href="">下一页</a>';
        }

    $page_str .= '</div>';
    return $page_str;

}


function getBoardGroupNum($board_id, $link) {
    $sql = "select count(*) as count from dir_article_".$board_id." where groupid=article_id and reid=0;";
    $result = mysql_query($sql, $link);
    if($result)
        return mysql_fetch_array($result)["count"];
    else
        return 0;
}

function in_array_list($array,$array_list){
    foreach($array_list as $list){
        if($array["article_id"] == $list["article_id"] && $array["board_id"] == $list["board_id"]){
            return 1;
        }
    }
    return 0;
}

/*
function getNewsData(&$msg)
{

    $ret = array();
    $typeToBoardid= array(
        "0300" => "365",
        "0301" => "249",
        "0302" => "395",
        "0303" => "81",
        "0304" => "114",
        "0305" => "146",
        "0306" => "347",
        "0307" => "420"
    );
    $typeToSqlStr = array(
//        "0300" => "[ZGPT]",
        "0301" => "[ZGPT]",
        "0302" => "[HWPT]",
        "0303" => "[TYPT]",
        "0304" => "[YLPT]",
        "0305" => "[KJPT]",
        "0306" => "[CJPT]",
        "0307" => ""
    );

    if($msg["reqType"] == "0300"){
        return getHeadlineNews($msg);
    }
    if($msg["reqType"] != "0300"){
        $data_tmp = array();
        $type = $msg["reqType"];
        $boardid = $typeToBoardid["$type"];
        $mysql_str = $typeToSqlStr["$type"];
        $msg["boardid"] = $boardid;
        $msg["news_type"] = $mysql_str;
        $data_tmp = getNewsforPage($msg);

        $msg["result"] = "1";
        if($data_tmp == null){
            $data_tmp = array();
        }
        return  $data_tmp;
    }

}
*/
function getNewsReply($link, $board_id, $group_id){
    $sql = "SELECT count(*) as count_num FROM dir_article_".$board_id." WHERE groupid=".$group_id." and article_id!=".$group_id;
    $result = mysql_query($sql,$link);
    $row = mysql_fetch_array($result);
    mysql_free_result($result);
    return $row["count_num"];
}

?>
