<?php
$php_page_arr=array(
    "/mobile/forum/index.php"=>"首页"
    ,"b"=>"2"
    ,"c"=>"3"
);

$codeDecs=array(
    "Α",
    "Δ",
    "Η",
    "Κ",
    "Ν",
    "Π",
    "Τ",
    "Χ",
    "α",
    "δ",
    "η",
    "κ",
    "ν",
    "π",
    "σ",
    "φ",
    "ω",
    "?",
    "′",
    "?",
    "?",
    "←",
    "↓",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "∝",
    "∧",
    "∪",
    "?",
    "≠",
    "≥",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "¨",
    "?",
    "?",
    "±",
    "?",
    "<",
    "Γ",
    "Ζ",
    "Ι",
    "Μ",
    "Ο",
    "Σ",
    "Φ",
    "Ω",
    "γ",
    "ζ",
    "ι",
    "μ",
    "ο",
    "?",
    "υ",
    "ψ",
    "?",
    "…",
    "?",
    "?",
    "?",
    "→",
    "?",
    "?",
    "?",
    "?",
    "?",
    "∑",
    "√",
    "∠",
    "∩",
    "∴",
    "≈",
    "≤",
    "?",
    "?",
    "⊥",
    "?",
    "?",
    "?",
    "?",
    "¤",
    "§",
    "?",
    "",
    "°",
    "?",
    '"',
    "'",
    "Β",
    "Ε",
    "Θ",
    "Λ",
    "Ξ",
    "Ρ",
    "Υ",
    "Ψ",
    "β",
    "ε",
    "θ",
    "λ",
    "ξ",
    "ρ",
    "τ",
    "χ",
    "?",
    "?",
    "″",
    "?",
    "?",
    "↑",
    "?",
    "?",
    "?",
    "?",
    "∈",
    "∏",
    "?",
    "∞",
    "∨",
    "∫",
    "?",
    "≡",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    ">");

$codeEnts=array(
    "&Alpha;",
    "&Delta;",
    "&Eta;",
    "&Kappa;",
    "&Nu;",
    "&Pi;",
    "&Tau;",
    "&Chi;",
    "&alpha;",
    "&delta;",
    "&eta;",
    "&kappa;",
    "&nu;",
    "&pi;",
    "&sigma;",
    "&phi;",
    "&omega;",
    "&piv;",
    "&prime;",
    "&frasl;",
    "&real;",
    "&larr;",
    "&darr;",
    "&lArr;",
    "&dArr;",
    "&part;",
    "&nabla;",
    "&ni;",
    "&minus;",
    "&prop;",
    "&and;",
    "&cup;",
    "&sim;",
    "&ne;",
    "&ge;",
    "&nsub;",
    "&oplus;",
    "&sdot;",
    "&lfloor;",
    "&spades;",
    "&diams;",
    "&cent;",
    "&yen;",
    "&uml;",
    "&laquo;",
    "&reg;",
    "&plusmn;",
    "&acute;",
    "&lt;",
    "&Gamma;",
    "&Zeta;",
    "&Iota;",
    "&Mu;",
    "&Omicron;",
    "&Sigma;",
    "&Phi;",
    "&Omega;",
    "&gamma;",
    "&zeta;",
    "&iota;",
    "&mu;",
    "&omicron;",
    "&sigmaf;",
    "&upsilon;",
    "&psi;",
    "&upsih;",
    "&hellip;",
    "&oline;",
    "&image;",
    "&alefsym;",
    "&rarr;",
    "&crarr;",
    "&rArr;",
    "&forall;",
    "&empty;",
    "&notin;",
    "&sum;",
    "&radic;",
    "&ang;",
    "&cap;",
    "&there4;",
    "&asymp;",
    "&le;",
    "&sup;",
    "&supe;",
    "&perp;",
    "&rceil;",
    "&loz;",
    "&hearts;",
    "&iexcl;",
    "&curren;",
    "&sect;",
    "&ordf;",
    "&shy;",
    "&deg;",
    "&sup3;",
    "&quot;",
    "?",
    "&Beta;",
    "&Epsilon;",
    "&Theta;",
    "&Lambda;",
    "&Xi;",
    "&Rho;",
    "&Upsilon;",
    "&Psi;",
    "&beta;",
    "&epsilon;",
    "&theta;",
    "&lambda;",
    "&xi;",
    "&rho;",
    "&tau;",
    "&chi;",
    "&thetasym;",
    "&bull;",
    "&Prime;",
    "&weierp;",
    "&trade;",
    "&uarr;",
    "&harr;",
    "&uArr;",
    "&hArr;",
    "&exist;",
    "&isin;",
    "&prod;",
    "&lowast;",
    "&infin;",
    "&or;",
    "&int;",
    "&cong;",
    "&equiv;",
    "&sub;",
    "&sube;",
    "&otimes;",
    "&lceil;",
    "&rfloor;",
    "&clubs;",
    "&nbsp;",
    "&pound;",
    "&brvbar;",
    "&copy;",
    "&not;",
    "&macr;",
    "&sup2;",
    "&micro;",
    "&gt;");


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

function wap_read_article2($filepath, $attach_link, $img_ago_str, $img_after_str, $attach_linkstr, $articlType = 1, $opflag = 0,&$imgList)
{
    $imgType = "jpg|jpeg|png|gif|bmp";
    define ("MAXATTACHMENTCOUNT", 20);
    $UBB_Control = chr(27); //0x1b
    $attach_start_pos = 0;

    $content_array = "";
    $content_attach = "";

    if (is_file($filepath)) {
        $content_tmp = file_get_contents($filepath);
        if ($attach_link && $articlType != 2) {
            $attach_start_pos = strpos($content_tmp, "\0\0\0\0\0\0\0\0");
            $content_array = substr($content_tmp, 0, $attach_start_pos);
            $content_attach = substr($content_tmp, $attach_start_pos);

            $content_array = explode("\n", $content_array);
        } else
            $content_array = explode("\n", $content_tmp);
    } else {
    }
    $lines = count($content_array);
    $content_str = "";
    $articlType  = 2;
    if ($articlType == 1) {
        $firsLine = 0;
    } else if ($articlType == 2 || $articlType == 3) {
        $firsLine = 4;
    }
    for ($j = $firsLine; $j < $lines; $j++) {
//        print_r($content_array[$j]);
        //结尾部分
        if ($firsLine == 4 && substr($content_array[$j], 0, 2) == "--") {
            //结尾部分
            $tmp = $content_array[$j+1];
            $tmp1 = $content_array[$j+2];
            if(strpos($tmp, "来源") || strpos($tmp1, "来源")||strpos($tmp1, "修改") ||strpos($tmp, "修改")){
                break;
            }
            if($articlType == 2){
                break;
            }
        }

        if ($firsLine == 4 && strpos($content_array[$j], "※ ")) {
            break;
        }

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
        if(strcmp(substr($tmpstr,0, 7), "http://") == 0){
            if(preg_match("/(".$imgType.")$/",$tmpstr)){
                $imgList[] = $tmpstr;
            }
        }
        $content_array[$j] = $tmpstr;
        if (($j > $firsLine && strlen($content_array[$j - 1]) == 76)
            && (substr_compare($content_array[$j], '  ', 1, 2) == 0
                || substr_compare($content_array[$j], "\t", 1, 1) == 0
                || substr_compare($content_array[$j], '  ', 1, 2) == 0)
        )
        {
            $content_str = $content_str . "\n";
        }

        if (strlen($content_array[$j]) == 76) {
            $content_str = $content_str . $content_array[$j]. "\n";
        } else {
            $content_str = $content_str . $content_array[$j] . "\n";
        }

        continue;
    }

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

                    if (is_pic_web($attach_filename)) //IMG
                    {
                        //$linkstr = $attach_linkstr. "_" . $attach_pos.getWebPicType($attach_filename);
                        $linkstr = $attach_linkstr . "_" . $attach_pos . ".jpg";
                        $imgList[] = $linkstr;
                        $linkstr = "$attach_filename<br/><br/><img style=\"width:228px;height:auto;\" src=".$linkstr."></img>";
//                        $content_str = $content_str . " <br/>$linkstr<br/>";
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
    <table width="240" border="0" cellspacing="0" cellpadding="0">
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

/* 获取十大热门话题 */
function getHotSubjects($link) {
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
function getRecommendArticle($link) {
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
function getHotBoards($link) {
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

//        var_dump($brdarr);
//        echo "<br /><br />";
        $t_element = array();
        $t_element["href"] = url_generate(2, array("board"=>$board_c));

        $t_element["img"] = getBoardImg($board_c);
        $t_element["des"] = $board_c;
        $t_element["name"] = $board_desc;
        $t_element["online"] = $brdarr["CURRENTUSERS"];
        /* 获取主题总数 */
        $t_element["total"] = getBoardGroupNum($brdarr["BOARD_ID"], $link);
        $ret[]=$t_element;
    }
    return $ret;
}

/* 获取热门俱乐部 */
function getHotClubs($link) {
    global $dir_modes;
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

    $club_sql = "select club_id,club_name,club_cname,club_description,onlines from club where approval_state=1 and club_type=1 {$notIn} ";
    if (is_china() == 1)
        $club_sql .= " and limit_flag=0 ";
    $club_sql .= " order by currentScore desc limit 10";

    $club_result = mysql_query($club_sql, $link);
    while($row = mysql_fetch_array($club_result)) {
        // 生成俱乐部对应的url
        $club_url = url_generate(2, array("club" => $row["club_name"]));
        // 俱乐部首页图片路径
        $club_img = getClubImg($row["club_name"]);
        // 俱乐部文章数
        $club_article_num = bbs_countarticles($row['club_name'], $dir_modes["ORIGIN"], 1);
        $ret[] = array(
            'href' => $club_url,
            'img' => $club_img,
            'name' => $row["club_cname"], //iconv("GBK", "UTF-8//IGNORE", urldecode($board_desc)),
            'des' => $row["club_description"],
            'online' => $row['onlines'],
            'article_num' => $club_article_num
        );
    }
    mysql_free_result($club_result);

    return $ret;
}

function getHostClub($link, $page, $group_id) {
    $china_flag = is_china();
    /*
    if($group_id != 'all'){
        $this_group = get_group_byid($group_id, $link);
        if (!$this_group)
            return null;
    }
*/
    $club_sql="select club_name,club_cname,club_description,modifytime,post_sum,member_sum,managerid,onlines,join_way,club_type,a.club_group_id,join_way,a.club_id,a.approval_state ";

    if($group_id != 0)
        $club_sql.=' from club a use index( index_score),users c where a.club_group_id='.$group_id.' and a.approval_state<=1 and a.managerid=c.numeral_user_id and a.flag&64<>64';
    else
        $club_sql.='from club a use index( index_score), club_group b,users c where a.club_group_id=b.club_group_id and a.approval_state<=1 and a.managerid=c.numeral_user_id and a.flag&64<>64';
    if($china_flag==1)
        $club_sql.=" and a.limit_flag=0 ";


    $club_sql .= " order by a.currentScore desc limit 10";
    $club_result = mysql_query($club_sql, $link);

    $ret = array();
    if ($club_result) {
        while ($clubrow = mysql_fetch_array($club_result)) {
//            $bm = getClubBM($clubrow["club_id"],$clubrow["managerid"],$link);
            $clubimg = getClubImg($clubrow["club_name"]);
            $href = url_generate(2, array("club"=>$clubrow["club_name"]));
            $club = array("clubId" => $clubrow["club_id"],
                "name" => $clubrow["club_name"],
                "cnName" => $clubrow["club_cname"],
                "href"=>$href,
                "description" => $clubrow["club_description"],
                "modifytime" => "".strtotime($clubrow["modifytime"]),
                "postSum" => $clubrow["post_sum"],
                "totalarticle" => $clubrow["post_sum"],
                "memberSum" => $clubrow["member_sum"],
                "joinWay" => $clubrow["join_way"],
                "club_type" => $clubrow["club_type"],
//                "managerUserId" => $bm,
                "managerUserNumId" => $clubrow["managerid"],
                "onlines" => $clubrow["onlines"],
                "clubimg"=>$clubimg,
                "club_approval_state"=>$clubrow["approval_state"]
            );
            /*
            if ($group_id != 'all') {
                $club["groupName"] = $this_group['group_name'];
                $club["groupId"] = $group_id;
            }else{
                $club["groupName"] = getGroupName($clubrow['club_group_id'], $link);
                $club["groupId"] = $clubrow["club_group_id"];
            }*/
            $ret[] = $club;
        }
    }

    return $ret;
}

function getClubCnName($club_id, $link) {
    $sql = 'select club_cname from club where club_id='.$club_id;
    $result = mysql_query($sql,$link);
    if($result&&$row = mysql_fetch_array($result)){
        return $row["club_cname"];
    }else{
        return '';
    }
}

function getArticleInfo($group_id,$club_id,$link){
    $table_id = ($club_id % 256);
    $arr = array();
    if ($table_id == 0)
        $table_id = 256;

    $sql = " select article_id,filename,club_name,filename,groupid,owner,read_num,reply_num,posttime,title from club_dir_article_$table_id
         WHERE club_id=$club_id  AND article_id=$group_id";
    $result = mysql_query($sql,$link);
    if($result && $row = mysql_fetch_array($result)){
        $arr["groupID"] = $row["groupid"];
        $arr["articleID"] = $row["article_id"];
        $arr["title"] =  $row["title"];
        $arr["author"] = $row["owner"];
        $arr["BoardsCnName"] = getClubCnName($club_id,$link);
        $arr["postTime"] = date('Y-m-d',strtotime($row["posttime"]));
        $arr["BoardsEngName"] = $row["club_name"];
        $arr["readnum"] = $row["read_num"];
        $arr["reply_num"] = $row["reply_num"];
        $arr["boardID"] = $club_id;
        $arr["href"] = url_generate(3, array("club"=>$row["club_name"], "groupid"=>$row["groupid"]));
        $filename = "/home/bbs/club/".strtoupper($arr["BoardsEngName"])."/".$arr["BoardsEngName"]."/".$row["filename"];
        $attachlink_rewrite = "http://" . $_SERVER['HTTP_HOST'] . "/clubarticle2/" . $arr["BoardsEngName"] . "/" . $arr["articleID"];
        $ret_str = wap_read_article2($filename,  $arr["attflag"], "", "", $attachlink_rewrite, 1, 1,$imgList);
        if (!empty($imgList))
            $arr["img"] = $imgList[0];

        $arr["content"] = $ret_str[1];
        $arr["content"] = mb_substr($arr["content"],0,140,"GBK").'...';
    }
    return $arr;
}

/* 获取俱乐部热门推荐文章 */
function getRecommendClubArticle($link, $page, $group_id){
    $return = array();
    $hot_time = 30;//推荐文章有效天数
    $pageSize = 30;
    $pageLimit = ((int)$page-1)*$pageSize.",".(int)$page*($pageSize);
    $dayTime = $hot_time*24*3600;
    $startTime = strtotime(date('Y-m-d',time()))-$dayTime;
    $endTime = $startTime+$dayTime+86400;
    $startTimeDate=date("Y-m-d H:i:s", $startTime);
    $endTimeDate=date("Y-m-d H:i:s", $endTime);


    $sql ='SELECT a.article_id,a.club_id, a.groupid FROM club_dir_article_memory a,club b where
    a.club_id=b.club_id and b.approval_state<=1 and b.flag&64<>64 and article_id=groupid and club_group_id='.$group_id.' and posttime between "'.$startTimeDate.'" and "'.$endTimeDate.'"  ORDER BY  a.posttime desc,reply_num desc limit '.$pageLimit;


    $result = mysql_query($sql,$link);
    $index = array();
    if (mysql_num_rows($result) < $pageSize)
        $end_flag = 1;
    else
        $end_flag = 0;

    while ($row = mysql_fetch_array($result)) {
        $table_id = $row["club_id"]%256;
        $query = "select owner from club_dir_article_$table_id where article_id='".$row["article_id"]."'";
        $ret = mysql_query($query,$link);
        $owner = mysql_fetch_array($ret);
        if($owner[0] == "deliver")
            continue;
        $index["{$row["club_id"]}"]++;
        if($index["{$row["club_id"]}"]>5)
            continue;
        $one["club_id"] = $row["club_id"];
        $one["groupid"] = $row["groupid"];
        $articleInfo = getArticleInfo($one["groupid"], $one["club_id"], $link);
        if ($articleInfo != NULL)
            $return[] = $articleInfo;

    }

    return array($return, $end_flag);
}

$label_list = array(
    // 置顶文章 热门推荐 论坛集粹 分类讨论
    "index" => array("top", "hot", "classical", "classes"),
    // 大杂烩 军事 国际 体育 娱乐 科技 财经
    "news" => array("news_mix", "news_military", "news_international", "news_sport", "news_recreation", "news_science", "news_finance"),
    // 精选 情感 女性 体育 游戏 娱乐 音乐
    "club" => array("club_handpick", "club_emotion", "club_woman", "club_sport", "club_game", "club_recreation", "club_music",
                    "club_hobby", "club_life", "club_finance", "club_schoolfellow", "club_hisfellow", "club_politics", "club_science",
                    "club_literature", "club_art", "club_other"),
    // 移民专栏 加盟律师 移民新闻 移民签证信息 讨论区
    "immigration" => array("i_column", "i_lawyer", "i_news", "i_visa", "i_discussion"),
    // 推荐 附近 搜索 排行
    "dianping" => array()
);

$club_class_list = array(
    "handpick" => array("id"=>"0", "name"=>"精选"),
    "emotion" => array("id"=>"1", "name"=>"情感类"),
    "woman" => array("id"=>"2", "name"=>"女性类"),
    "sport" => array("id"=>"3", "name"=>"体育类"),
    "game" => array("id"=>"4", "name"=>"游戏类"),
    "recreation" => array("id"=>"5", "name"=>"娱乐类"),
    "music" => array("id"=>"6", "name"=>"音乐类"),
    "hobby" => array("id"=>"7", "name"=>"爱好类"),
    "life" => array("id"=>"8", "name"=>"生活类"),
    "finance" => array("id"=>"9", "name"=>"财经类"),
    "schoolfellow" => array("id"=>"10", "name"=>"校友类"),
    "hisfellow" => array("id"=>"11", "name"=>"同乡类"),
    "politics" => array("id"=>"12", "name"=>"时政类"),
    "science" => array("id"=>"13", "name"=>"科技类"),
    "literature" => array("id"=>"14", "name"=>"文学类"),
    "art" => array("id"=>"15", "name"=>"艺术类"),
    "other" => array("id"=>"100", "name"=>"其他"),
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
     *          array("club"=>$clubname, "group"=>$group_id)
     *      4: DIY 传入action和args，返回url
     *          $data = {
     *              "action"=>"one_test.php"
     *              "args"=>{"type"=>"top", "groupid"=>"2"}
     *          }
     *          返回  one_test.php?type=top&groupid=2
     *
     * */
    $url = "/404.html";
    // 注: type可以为空
    switch($level) {
        case 1:
            if (isset($data["class"]))
                $url = 'one_class.php?type='.$data["type"].'&class='.$data["class"];
            else if(isset($data["club_class"]))
                $url = 'one_class_club.php?type='.$data["type"].'&club_class='.$data["club_class"];

            break;

        case 2:
            if (isset($data["board"]))
                $url = 'one_board.php?type='.$data["type"].'&board='.$data["board"];
            else if (isset($data["club"]))
                $url = 'one_club.php?type='.$data["type"].'&club='.$data["club"];

            break;
        case 3:
            if (isset($data["groupid"])) {
                if (isset($data["board"]))
                    // 论坛内文章的跳转地址
                    $url = 'one_group.php?type='.$data["type"].'&board='.$data["board"].'&group='.$data["groupid"];
                else if (isset($data["club"]))
                    // 俱乐部文章跳转地址
                    $url = 'one_group_club.php?type='.$data["type"].'&club='.$data["club"].'&group='.$data["groupid"];
            }

            break;
        case 4:
            if (isset($data["action"])) {
                $url = $data["action"].'?';
                foreach ($data["args"] as $key=>$value)
                    $url = $url.$key.'='.$value.'&';

                $len = strlen($url);
                if ($url[$len-1] == "?" or $url[$len-1] == "&")
                    $url = substr($url, 0 , $len-1);
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

$lawyer_list = array(
    "0" => array(
        "name"=>"谢正权",
        "link"=>"XieLaw",
        "alt"=>"美国乔治亚州注册律师；乔治亚州立大学法学博士；中国政法大学学士、法学硕士；美国密苏里大学司法学硕士；原美国圣路易大学法学院访问学者；原中国政法大学教师、在职法学博士生；原美国乔治亚州政府律师；美国律师协会会员；美国移民律师协会会员；美国乔治亚州律师协会会员。",
        "picname"=>"pic2.gif"
    ),

    "1" => array(
        "name"=>"张哲瑞",
        "link"=>"hooyou",
        "alt"=>"张哲瑞联合律师事务所是全美最大的移民律师事务所之一，擅长办理杰出技能人才(EB-1a)，杰出教授和研究人员(EB-1b)以及国家利益豁免移民(NIW)绿卡,PERM职业移民及H-1B等签证，已成功帮助数千客户获得绿卡。拥有25位美国执照律师，在硅谷、纽约、洛杉矶、芝加哥、休斯顿、奥斯汀和西雅图七个城市的商业中心区设有办公室，致力于为客户提供最佳的服务，成功率高。网址：www.hooyou.com; email：info@hooyou.com;tel：1-800-230-7040.",
        "picname"=>"hooyou.jpg"
    ),

    "2" => array(
        "name"=>"戚博雄",
        "link"=>"sqilaw",
        "alt"=>"J.D., Western State University, College of Law, California M.A., Journalism, University of Southern California B. A., World Economy, Fudan University, Shanghai, China.",
        "picname"=>"pic3.gif"
    ),

    "3" => array(
        "name"=>"精诚",
        "link"=>"jingchenglaw",
        "alt"=>"精诚联合律师事务所于1987年成立于加州洛杉矶市。为来自中国大 陆、台湾、香港及美国各州的华人客户提供全面的移民业务服务。此外，我们还为来自印度、韩国、俄罗斯、日本、加拿大、法国、英国、泰国、马来西亚和中美洲地区国家的人士办理各项移民及工作签证申请。",
        "picname"=>"pic4.gif"
    ),

    "4" => array(
        "name"=>"刘宗坤",
        "link"=>"LiuLaw",
        "alt"=>"A graduate of Peking University (Ph.D.) and Valparaiso University School of Law (J.D.), Dr. Liu is a licensed member of the Illinois State Bar. He was also admitted to practice law in the U.S. District Courts for Southern District of Texas and Northern District of Illinois. His practice includes employment-based immigrant and non-immigrant petitions, and administrative and judiciary appeals. Prior to practicing law as a licensed attorney, Dr. Liu interned as law clerk in the Law Division of Cook County Circuit Court, Chicago, Illinois, and served on the editorial board of Valparaiso University Law Review, Valparaiso, Indiana.",
        "picname"=>"pic7.gif"
    ),

    "5" => array(
        "name"=>"孙虹",
        "link"=>"SunLaw",
        "alt"=>"Alice H. Sun 律师，全美移民律师协会会员(AILA)，加州律师协会会员（1994）。美国法学博士（1994）原中国社会科学院法学硕士，法学所研究人员。办理职业移民杰出人才，教授及研究人员，国家利益豁免, PERM劳工审查尤为成功。为美国全国名校研究人员和博士学生及NIH, RAND等著名研究所研究人员申办绿卡及工作签证。律师事务所向本所客户提供寻找和协商高科技风险投资以及专利和知识产权全方位服务。我们的宗旨是帮助您在美国立足，在美国成功。网站信息：www.sunlawfirm.us.",
        "picname"=>"pic11.jpg"
    ),

    "6" => array(
        "name"=>"陈帆",
        "link"=>"ChenLaw",
        "alt"=>"陈帆联合律师事务所(Nguyen & Chen, LLP)专业受理各类民事、刑事诉讼。该综合律师事务所尤其擅长提供移民法、人事雇佣法、以及劳工法等领域的优质服务。
        作为该所创始人之一,陈帆律师具备扎实的法学基础和丰富的实践经验。陈帆律师曾在德克萨斯州哈里斯郡政府、州上诉法院实习工作，并在全美知名律师事务所供职多年。
        陈帆律师毕业于休斯敦大学法学院，是德克萨斯州执照律师、美国联邦地区法院出庭律师、美国移民律师协会会员。",
        "picname"=>"pic14.jpg"
    ),

    "7" => array(
        "name"=>"FYZ",
        "link"=>"fyzlaw",
        "alt"=>"FYZ律师事务所(FYZ Law Group LLP)是向各大教育/研究机构，私营企业及个人提供全方位移民法律服务的事务所。我们的律师拥有多年美国移民法律服务经验。我们专精科技职业移民申请和非移民工作签证申请，诸如：EB-1A， EB-1B， EB-1C, NIW, PERM, H-1B, L-1 and O-1. 我们在旧金山湾区，芝加哥和纽约设有办公室， 是为数不多的跨美职业移民律师事务所之一。网址: www.fyzlaw.com  Email: info@fyzlaw.com  Tel: 650-312-8668(CA); 630-577-9060(IL); 646-288-7129(NY)",
        "picname"=>"pic16.jpg"
    ),

    "8" => array(
        "name"=>"Annie杨",
        "link"=>"yanglaw",
        "alt"=>"Ms. Annie Yang 杨静宜律师是杨律师联合律师事务所(Yang and Associates, LLP)的创办人之一和主要律师。杨律师在美国移民法方面有着非常丰富的经验。她上十年高效率，高质量，高水平和全心投入的服务深得客户广泛好评。
        服务宗旨是提供个性化服务，做一个可以值得您信赖的律师。Immigration Attorneys You Can Trust!
        主要代理案件类型：杰出人才(EB-1a)，杰出教授和研究人员(EB-1b)，国家利益豁免(NIW)，劳工证 (PERM)EB-2/EB-3移民申请；H-1B, L-1, O, TN等工作签证申请; 投资移民(EB-5), J-1 Waiver, B延期, 家庭移民, I-485以及各种有关移民的疑难问题咨询。",
        "picname"=>"pic17.jpg"
    ),

    "9" => array(
        "name"=>"北美联合",
        "link"=>"WeGreened",
        "alt"=>"北美联合律师事务所(WeGreened.com)由美国TOP-10顶尖名校法学院法律博士(J.D.)组成，本所专精国家利益豁免绿卡(NIW)，第一优先杰出人才绿卡(EB1A)，和杰出教授研究人员移民(EB1B),由於对文件品质及人员素质的高标准要求, 和本事务所针对各种请愿案收集大量有助於论证的系统化资料库,使得本所一年有500个以上 EB1/NIW 大量成功案例，平均 EB1/NIW 案件成功率高达98.5%,同时在EB1/NIW申请领域提供全方位的服务(Letters/PL/RFE)和极具竞争力的品质保证方案(Approval or Refund Service),详情请参考我所大量成功经验和批准通知http://cn.wegreened.com/eb1_niw_approvals网址：cn.wegreened.com; 免费评估：law@wegreened.com; 中文热线：888.666.0969 ext.380(免费专线)",
        "picname"=>"WeGreened.png"
    )
);



function getMainPageLawyers($lawyer_list) {
    $ret = array();

    foreach ($lawyer_list as $each) {
        $href = url_generate(4, array(
            "action" => "/mobile/forum/i_board.php",
            "args" => array("board"=>$each["link"])
        ));
        $ret[] = array(
            "name"=>$each["name"],
            "href"=>$href,
            "img"=>'/yimin/images/'.$each["picname"]
        );
    }

    return $ret;
}

function getLawyerInfo($boardname, $lawyer_list) {
    foreach ($lawyer_list as $each) {
        if ($each["link"] == $boardname) {
            $ret = array(
                "name"=>$each["name"],
                "href"=>"",
                "img"=>'/yimin/images/'.$each["picname"],
                "desc"=>$each["alt"]
            );

            return $ret;
        }
    }

    return array();
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

$type_to_sql_data = array(
    "military"=>array("boardID"=>"249", "news_type"=>"[ZGPT]"),
    "international"=>array("boardID"=>"395", "news_type"=>"[HWPT]"),
    "sport"=>array("boardID"=>"81", "news_type"=>"[TYPT]"),
    "recreation"=>array("boardID"=>"114", "news_type"=>"[YLPT]"),
    "science"=>array("boardID"=>"146", "news_type"=>"[KJPT]"),
    "finance"=>array("boardID"=>"347", "news_type"=>"[CJPT]"),
    "immigration"=>array("boardID"=>"420", "news_type"=>"")
);

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

function getImmigrationNewsType($title){
    $arr = array();
    preg_match('/(\[美国\]|\[加拿大\]|\[其他地区\])/', $title, $arr);
    $return  = $arr[0];
    $return = str_replace(']','',$return);
    $return = str_replace('[','',$return);
    return $return;
}

function getNewsDataByType($link, $page, $newsTypeName) {
    global $codeEnts, $codeDecs;
    global $type_to_sql_data;
    $boardid = $type_to_sql_data[$newsTypeName]["boardID"];
    $news_type = $type_to_sql_data[$newsTypeName]["news_type"];

    $num = 40;
    $page = ($page-1)*$num;
    $is_china_flag = is_china();
    if ($is_china_flag == 1) {
        $str = "AND cn_read!='N' AND cn_read!='M' ";
    }

    if ($newsTypeName == "immigration") {
        $US = "[美国]";
        $CNA = "[加拿大]";
        $OTH = "[其他地区]";
        $str.="AND title REGEXP '($US|$CNA|$OTH)'";
    } else {
        $str .= "AND LEFT(title,".strlen($news_type)." )='$news_type'";
    }
    $sql = "SELECT a.article_id,a.boardname boardname,a.title,a.owner,a.o_id,a.groupid,a.o_groupid,a.o_bid ,UNIX_TIMESTAMP(action_time)
         ,a.filename,b.board_desc,a.type_flag,UNIX_TIMESTAMP(posttime) FROM digest_article a JOIN board b ON a.board_id=b.board_id
         WHERE a.board_id=$boardid $str ORDER BY a.action_time DESC, a.article_id DESC LIMIT {$page}, {$num}";
    $result = mysql_query($sql, $link);
    if (mysql_num_rows($result) < $num)
        $end_flag = "1";
    else
        $end_flag = "0";

    while ($row = mysql_fetch_array($result)) {
        $aNew["groupID"] = $row["groupid"];
        $aNew["articleID"] = $row["article_id"];
        $aNew["author"] = $row["owner"];
        $aNew["title"] = $row["title"];
        $aNew["title"] = str_replace($codeEnts, $codeDecs, $aNew["title"]);
        if($newsTypeName  == "immigration"){
            $aNew["newType"] = getImmigrationNewsType($aNew["title"]);
        }
        $aNew["title"] = substr(strchr($aNew["title"],"]"),1);
        if ($aNew["title"] == null) {
            $aNew["title"] = "";
        }
        $boardName = $row["boardname"];
        $boardCnName = $row["board_desc"];
        $aNew["BoardsName"] = trim(substr($boardCnName, strpos($boardCnName,']')+1));
        $aNew["BoardsEngName"] = $boardName;
        $aNew["boardID"] = $boardid;
        if ($newsTypeName != "immigration") {
            $aNew["href"] = url_generate(3, array("board"=>$boardName, "groupid"=>$row["groupid"]));
        } else {
            $aNew["href"] = url_generate(4, array(
                "action"=>"/mobile/forum/i_article.php",
                "args"=>array("reqtype"=>"news", "board"=>$boardName, "groupid"=>$row["groupid"])
            ));
        }
        $aNew["actiontime"] = "".$row["UNIX_TIMESTAMP(action_time)"];

        $filepath = BBS_HOME."/boards/".$aNew["BoardsEngName"]."/".$row["filename"];
        $filehandle = fopen($filepath, "r");
        if ($filehandle) {
            for ($i = 0; $i < 4; $i++)
                fgets($filehandle);
            while ($notes = fgets($filehandle)) {
                if (!strncmp($notes, "--", 2)) {
                    $notes = "1";//end
                    break;
                } else if (!strncmp($notes,"http://",7) or $notes == "\n") {
                    continue;
                } else if(strpos(iconv("GBK", "UTF-8//IGNORE", $notes),"编者按")!==false) {
                    if (strpos($notes,"。") !== false) {
                        break;//ok,it is
                    } else {
                        while ($notes1 = fgets($filehandle)) {
                            if ($i = strpos(iconv("GBK", "UTF-8//IGNORE", $notes1), "。")) {
                                $notes .= $notes1;
                                break;
                            } else if (!strncmp($notes1, "--", 2) or !strncmp($notes1, "http://", 7)) {
                                break;
                            } else {
                                $notes .= $notes1;
                            }
                        }
                        break;
                    }
                }else {
                    if(!isset($notestmp)){
                        $notestmp = $notes;
                    }
                }
            }
        }else{
            $notes = "0";
        }
        if($notes == 1)
            $notes = $notestmp;
        else
            $notes = "";

        unset($notestmp);
        $aNew["filename"] = $row["filename"];
        $arr=explode("。",  $notes);
        $aNew["notes"] = str_replace("编者按：","",$arr[0]);
        $postdate = "".$row["UNIX_TIMESTAMP(posttime)"];
        $nowdate = strtotime(date('Y/m/d'));

        $aNew["postTime"] = $postdate;
        $sql = "SELECT new_url FROM article_image_list WHERE article_id={$aNew["articleID"]} and board_id='{$aNew["boardID"]}'";
        $num1 = 0;
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
        $aNew["source"] = "未名空间";
        $sql = "select source,read_num,total_reply from  dir_article_{$aNew["boardID"]} where article_id={$aNew["articleID"]}";
        $result1 = mysql_query($sql, $link);
        if ($row1 = mysql_fetch_array($result1)) {
            if($row1["source"] != null)
                $aNew["source"] = $row1["source"];
            else
                $aNew["source"] = "未名空间";
            $aNew["read_num"] = $row1["read_num"];
            $aNew["total_reply"] = $row1["total_reply"];
        } else {
            $aNew["source"] = "未名空间";
            $aNew["read_num"] = "0";
            $aNew["total_reply"] = "0";
        }
        $aNew["total_reply"] = getNewsReply($link, $aNew["boardID"], $aNew["articleID"]);
        $ret[] = $aNew;
    }
    usort($ret, 'sortByActionTime');

    return array($ret, $end_flag);
}

function getNewsReply($link, $board_id, $group_id){
    $sql = "SELECT count(*) as count_num FROM dir_article_".$board_id." WHERE groupid=".$group_id." and article_id!=".$group_id;
    $result = mysql_query($sql,$link);
    $row = mysql_fetch_array($result);
    mysql_free_result($result);
    return $row["count_num"];
}

function getNews($link, $page, $type) {
    if ($type == "mix")
        return getHeadLineNews($link, $page);
    else
        return getNewsDataByType($link, $page, $type);
}

function getImmigrationVisa($link) {
    global $codeDecs, $codeEnts;
    $data = read_xmlfile_content_web(BBS_HOME."/xml/newscenter/yimininfo.xml", 3);
    $ret = array();
    for($i=0; $i<count($data); $i++)
    {
        $brdarr = array();
        $record["groupID"] = $data[$i]["groupid"];
        $record["articleID"] = $data[$i]["article_id"];
        $record["title"] = $data[$i]["title"];
        $record["title"] = substr(str_replace($codeEnts, $codeDecs, $record["title"]),4);
        $brdnum = bbs_getboard($data[$i]['board'], $brdarr);
        $postdate =  "".$data[$i]['time'];

        $record["posttime"] = $postdate;
        $record["BoardsName"] = $brdarr["DESC"];
        $record["BoardsEngName"] = $brdarr["NAME"];
        $record["boardID"] = $brdarr["BOARD_ID"];
        $record["href"] = url_generate(4, array(
                "action"=>"/mobile/forum/i_article.php",
                "args"=>array("reqtype"=>"visa", "board"=>$brdarr["NAME"], "groupid"=>$data[$i]["groupid"])
            ));

        $sql = "select read_num,reply_num,owner from dir_article_".$brdarr["BOARD_ID"]." where article_id='{$record["groupID"]}'";
        $result = mysql_query($sql, $link);
        if($row = mysql_fetch_array($result)) {
            $record["readNum"] = $row[0];
            $record["relyNum"] = $row[1];
            $record["author"] = $row[2];
        }else{
            $record["readNum"] = "0";
            $record["relyNum"] = "0";
            $record["author"] = "";
        }
        mysql_free_result($result);

        $ret[] = $record;
    }

    return $ret;
}

?>
