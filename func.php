<?php
$php_page_arr=array(
    "/mobile/forum/index.php"=>"��ҳ"
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

//�����ļ��ķǸ�������
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
            //��β����
            $tmp = mit_iconv($content_array[$j + 1]);
            $tmp1 = mit_iconv($content_array[$j + 2]);
            if (strpos($tmp, "��Դ") || strpos($tmp1, "��Դ") || strpos($tmp1, "�޸�") || strpos($tmp, "�޸�")) {
                if ($mailtype != 1) {
                    break;
                }
            }
            if ($articlType == 2) {
                break;
            }
        }

        if ($firsLine == 4 && strpos($content_array[$j], "�� ")) {
            if ($mailtype != 1||$articlType==1) {
                break;
            }
        }
        if ($articlType==1&&$content_array[$j]=="--")
            break;

        //UBB�����봦��
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

        if (($j > $firsLine && strlen($content_array[$j - 1]) == 76) //��һ��Ϊ76���ַ�&&��ǰ���Կո����tab��ͷ�����ػ���
            && (substr_compare($content_array[$j], '  ', 1, 2) == 0
                || substr_compare($content_array[$j], "\t", 1, 1) == 0
                || substr_compare($content_array[$j], '  ', 1, 2) == 0)
        ) {
            $content_str = $content_str . "\n";
        }

        if (strlen($content_array[$j]) == 76) { //webϵͳ����ʱ76���ַ���һ�У�����ȥ��
            $content_str = $content_str . $content_array[$j];
        } else {
            $content_str = $content_str . $content_array[$j] . "\n";
        }

        // $content_str = $content_str.$content_array[$j]."\n";
        //UBB����end
        continue;
    }

//�����ļ��ĸ�������
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
        $ret_str="δ����ʾ:����ĳЩ����ԭ��,������δ����ȷ��ȡ,���Ժ���ˢ������!";
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
       return "�����ҳ���Ӧ��ϵ";
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
                        <p>�㻹û�е�½�������㷢��ʱ������������������ ������<a
                                href='/<?php if (1 == is_china()) echo "index_login.php"; else echo "mitbbs_login.php"; ?>?pre_url=<?php echo(urlencode($pre_url)); ?>'
                                class='top_red'>��¼</a>!</p>
                    <?php } else if ($err_msg == WWW_NOLOGIN_ADULT_MSG) {
                        create_adult_herf($adult);
                        ?>
                        <p>�㻹û�е�½�������㷢��ʱ������������������ ������<a href="#"
                                                         onclick="javascript:myhref('/mitbbs_login.php?pre_url=<?php echo(urlencode($pre_url)); ?>');"
                                                         class='top_red'>��¼</a>!</p>
                    <?php } else if ($err_msg == WWW_NOLOGIN_CN_MSG) { ?>
                        <p>����δ��¼���Ѿ����ڣ����ܷ��ʸ�ҳ,����<a
                                href='/<?php if (strstr($_SERVER['SCRIPT_URL'], "/mobile/")) echo "/mobile/mlogin.php"; else echo "index_login.php"; ?>'
                                class='top_red'>��¼</a>!</p>
                        <?php //pre_url=php echo(urlencode($_SERVER["SCRIPT_URL"])); ��ʱ��ȥ�� post �����޷���ȥ ��Щҳ�����ʾ�޷���ȡ����
                        ?>
                    <?php } else if ($err_msg == WWW_NOLOGIN_CN_GAME) { ?>
                        <p>
                            ���Ĺ�����̫�ͣ����������վ�����ǿ�������Ϸ<?php if (!strstr($_SERVER['SCRIPT_URL'], "/mobile/")) echo "<a href=\"http://$gamedomain/game/mitbbs_game.php\" class='top_red'>������Ϸ</a>"; ?></p>
                        <?php //pre_url=php echo(urlencode($_SERVER["SCRIPT_URL"])); ��ʱ��ȥ�� post �����޷���ȥ ��Щҳ�����ʾ�޷���ȡ����
                        ?>
                    <?php } else { ?>
                        <p><?php echo $err_msg; ?>!</p>
                    <?php } ?>
                    <br><br>
                    [<a href="javascript:history.go(-1)" class="headlink">���ٷ���</a>]
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
                $buf = fgets($fp, 256);       /* ȡ����һ���� ���������µ� ������Ϣ */
                $end = strrpos($buf, ")");
                $start = strpos($buf, ":");
                if ($start != FALSE && $end != FALSE)
                    $quser = substr($buf, $start + 2, $end - $start - 1);

                $ret_str="\n\n�� �� " . $user . " �Ĵ������ᵽ: ��\n";
                for ($i = 0; $i < 3; $i++) {
                    if (($buf = fgets($fp, 500)) == FALSE)
                        break;
                }

                while (1) {
                    if (($buf = fgets($fp, 500)) == FALSE)
                        break;
                    if (strncmp($buf, "--\n", 3) == 0)
                        break;
                    if (strncmp($buf, "��", 2) == 0)
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
    ?>
