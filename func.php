<?php
//==================================================================================================================
// for debug start
function log2file($str) {
    $str = (string)$str."\n";
    $fp = fopen("/home/bbs/ly_mitbbs.log", "ab+");
    fputs($fp, $str);
    fclose($fp);
}

function get_table_content($link, $table_name, $condition="", $row_num=20, $show_type="web") {
    $all_flag = 0;
    if ($row_num == "all")
        $all_flag = 1;
    elseif (!is_numeric($row_num) or $row_num < 0)
        $row_num = 20;

    $sep = "";
    switch ($show_type) {
        case "web":
            $sep = "<br />";
            break;
        case "doc":
            $sep = "\n\r";
            break;
        default:
            $sep = "\n\r";
    }

    $sql = "SELECT * FROM $table_name $condition";

    if ($all_flag == 0)
        $sql .= " LIMIT $row_num";

    $result = mysql_query($sql, $link);
    $ret = "";
    while ($row = mysql_fetch_array($result)) {
        foreach ($row as $key=>$value) {
            if (is_numeric($key))
                continue;

            $tmp = iconv("UTF-8", "GBK//IGNORE", $value);
            if (!empty($tmp))
                $value = $tmp;
            $ret .= $key." => ".$value.$sep;
        }
        $ret .= $sep;
    }

    mysql_free_result($result);
    return $ret;
}

function show_result($arr) {
    foreach ($arr as $i=>$each) {
        echo "<br />[$i] =><br />";
        if (is_array($each)) {
            foreach ($each as $key=>$value) {
                if (is_array($value)) {
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;$key => ";
                    var_dump($value);
                    echo "<br />";
                } else
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;$key => $value<br />";
            }
        } else
            echo $each."<br />";
    }
}
// for debug end
//===================================================================================================================
$php_page_arr=array(
    "/mobile/forum/index.php"=>"��ҳ"
    ,"b"=>"2"
    ,"c"=>"3"
);

$codeDecs=array(
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "?",
    "��",
    "?",
    "?",
    "��",
    "��",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "��",
    "��",
    "��",
    "?",
    "��",
    "��",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "?",
    "��",
    "?",
    "?",
    "��",
    "?",
    "<",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "?",
    "��",
    "��",
    "?",
    "��",
    "?",
    "?",
    "?",
    "��",
    "?",
    "?",
    "?",
    "?",
    "?",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "?",
    "?",
    "��",
    "?",
    "?",
    "?",
    "?",
    "��",
    "��",
    "?",
    "",
    "��",
    "?",
    '"',
    "'",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "��",
    "?",
    "?",
    "��",
    "?",
    "?",
    "��",
    "?",
    "?",
    "?",
    "?",
    "��",
    "��",
    "?",
    "��",
    "��",
    "��",
    "?",
    "��",
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

// ֧�� ASCII",'UTF-8��,"GB2312��,"GBK",'BIG5��
function stringConvertToGbk($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $k => $v) {
            if (is_array($v)) {
                $mixed[$k] = charsetToGBK($v);
            } else {
                $encode = mb_detect_encoding($v, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
                if ($encode == 'UTF-8') {
                    $mixed[$k] = iconv('UTF-8', 'GBK', $v);
                }
            }
        }
    } else {
        $encode = mb_detect_encoding($mixed, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
        //var_dump($encode);
        if ($encode == 'UTF-8') {
            $mixed = iconv('UTF-8', 'GBK', $mixed);
        }
    }
    return $mixed;
}

/**
 * @desc ���������ľ�γ�ȼ������
 * @param float $lat γ��ֵ
 * @param float $lng ����ֵ
 * lat1 lng1 �Լ���λ��, lat2,lng2 �Է���λ��
 * @reutrn ����֮��Ĺ�����
 */
function getDistance($lat1, $lng1, $lat2, $lng2)
{
    return (2 * 6378.137* ASIN(sqrt(pow(sin(pi()*($lat1-$lat2)/360),2)+cos(pi()*$lng1/180)*cos($lat2 * pi()/180)*pow(sin(pi()*($lng1-$lng2)/360),2))));
}

/**
 * ��ά��������
 * @desc: ����ָ���������е�ĳһ���� ����������
 * @param: arr ����������
 * @param: base �������ݵ��ֶ�
 * @param: direction ������,0����,1����
 */
function dyadic_array_sort(&$arr, $base, $direction=0) {
    $sort_arr = array();

    // �����ݵ��ֶε�����ȡΪһά����,��������key��ԭ����key��Ӧ
    foreach ($arr as $key=>$value) {
        $sort_arr[$key] = $value[$base];
    }

    if ($direction)
        $direction = SORT_DESC;
    else
        $direction = SORT_ASC;

    array_multisort($sort_arr, $direction, $arr);
}

/* ��ȡ����ƴ������ĸ */
function getSpellInitial($str)
{
    if (preg_match('/^[a-z]/i', $str)) {
        return strtoupper(substr($str, 0, 1));
    } else if (preg_match("/^[\x7f-\xff]/", $str)) {
        $fchar=ord($str{0});
        if($fchar>=ord("A") and $fchar<=ord("z") )return strtoupper($str{0});
            $a = $str;
        $val=ord($a{0})*256+ord($a{1})-65536;
        if($val>=-20319 and $val<=-20284)return "A";
        if($val>=-20283 and $val<=-19776)return "B";
        if($val>=-19775 and $val<=-19219)return "C";
        if($val>=-19218 and $val<=-18711)return "D";
        if($val>=-18710 and $val<=-18527)return "E";
        if($val>=-18526 and $val<=-18240)return "F";
        if($val>=-18239 and $val<=-17923)return "G";
        if($val>=-17922 and $val<=-17418)return "H";
        if($val>=-17417 and $val<=-16475)return "J";
        if($val>=-16474 and $val<=-16213)return "K";
        if($val>=-16212 and $val<=-15641)return "L";
        if($val>=-15640 and $val<=-15166)return "M";
        if($val>=-15165 and $val<=-14923)return "N";
        if($val>=-14922 and $val<=-14915)return "O";
        if($val>=-14914 and $val<=-14631)return "P";
        if($val>=-14630 and $val<=-14150)return "Q";
        if($val>=-14149 and $val<=-14091)return "R";
        if($val>=-14090 and $val<=-13319)return "S";
        if($val>=-13318 and $val<=-12839)return "T";
        if($val>=-12838 and $val<=-12557)return "W";
        if($val>=-12556 and $val<=-11848)return "X";
        if($val>=-11847 and $val<=-11056)return "Y";
        if($val>=-11055 and $val<=-10247)return "Z";
    } else {
        return "Other";
    }
}

/* ��ȡ��ǰҳ���URL */
function curPageURL()
{
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on")
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    $this_page = $_SERVER["REQUEST_URI"];

    // ֻȡ ? ǰ�������
    if (strpos($this_page, "?") !== false)
        $this_page = reset(explode("?", $this_page));

    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $this_page;
    }
    else
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $this_page;
    }
    return $pageURL;
}

function get_row_count($board_id, $article_id, $link, $type=1){
    if ($type == 1) {
        // type == 1 Ϊ����
        $sql = "SELECT COUNT(*) AS count FROM dir_article_{$board_id} WHERE groupid=$article_id";
    } elseif ($type == 2) {
        // type == 2 Ϊ���ֲ�
        $table_id = $board_id%256;
        if ($table_id == 0)
            $table_id = 256;

        $sql = "SELECT COUNT(*) AS count FROM club_dir_article_{$table_id} WHERE groupid=$article_id";
    } else {
        return 0;
    }
    $ret = mysql_query($sql, $link);
    $row = mysql_fetch_array($ret);
    mysql_free_result($ret);
    return $row["count"];
}

function trans_content_html($str){
    $arr = array();

    $arr=explode("\n",$str);
    $img_html="";
    $content_html="";
    foreach ( $arr as $content) {
        if (preg_match("/^http:\/\//", $content)) {
            $img_html .= "<a target=\"_blank\" href=\"".$content."\">";
            $img_html .= "\n";
            $img_html .= "<img src=\"".$content."\">";
            $img_html .= "\n";
            $img_html .= "</a>";
        }else{
            if($content_html != "")
               $content_html .= "<br>";
            $content_html .= $content;
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
            $tmp = iconv("GBK","UTF-8//IGNORE",$content_array[$j + 1]);

            $tmp1 = iconv("GBK","UTF-8//IGNORE",$content_array[$j + 2]);
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

        $pic_num=0;

        $content_array[$j] = $tmpstr;
        if (strcmp(substr($tmpstr, 0, 7), "http://") == 0) {
            ++$pic_num;
            $pic_list[] = $tmpstr;
        }
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
        //��β����
        if ($firsLine == 4 && substr($content_array[$j], 0, 2) == "--") {
            //��β����
            $tmp = $content_array[$j+1];
            $tmp1 = $content_array[$j+2];
            if(strpos($tmp, "��Դ") || strpos($tmp1, "��Դ")||strpos($tmp1, "�޸�") ||strpos($tmp, "�޸�")){
                break;
            }
            if($articlType == 2){
                break;
            }
        }

        if ($firsLine == 4 && strpos($content_array[$j], "�� ")) {
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

function get_file_content($filename,$att_flag,$board_name,$article_id,$article_type,&$att_arr,$op_flag=0){
    if(false==$filename){
        $ret_str[1]="δ����ʾ:����ĳЩ����ԭ��,������δ����ȷ��ȡ,���Ժ���ˢ������!";
        return $ret_str;
    }

    // �������� op_flag=0  ���ֲ����� op_flag=1
    if ($op_flag == 0) {
        $attachlink_rewrite = "http://" . $_SERVER['HTTP_HOST'] . "/article2/" . $board_name. "/" . $article_id;
    } else {
        $attachlink_rewrite = "http://" . $_SERVER['HTTP_HOST'] . "/clubarticle2/" . $board_name. "/" . $article_id;
    }
    $ret_str= wap_read_article(BBS_HOME . "/$filename", $att_flag, "", "", $attachlink_rewrite, $op_flag, $article_type, 0, 0, $att_arr);
    return $ret_str;
}
function check_board_filename($board_name,$filename){
    $file=bbs_get_board_filename($board_name,$filename);
    if(is_file($file))
        return $file;
    else
        return false;
}

function check_club_filename($club_name, $file_name) {
    $filepath = 'club/'.strtoupper(substr($club_name,0,1))."/$club_name/$file_name";
    if (file_exists($filepath))
        return $filepath;
    else
        return "";
}

function get_user_img($user_id){
    $headimg = BBS_HOME . '/pic_home/home/' . strtoupper(substr($user_id, 0, 1)) . '/' . $user_id . '/headimg';
    if (!is_file($headimg)) {
        $url_img="/mobile/forum/images/headimg.png";
    } else {
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
    <table width="240" border="0" cellspacing="0" cellpadding="0">
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

function getExtraValue($str) {
    $arr = explode("|", $str);
    $ret = array();
    // 0 mode  1 name
    $ret[0] = $arr[0];
    $ret[1] = $arr[1];

    return $ret;
}

function getDpCityCname($city) {
    if ($city == "all")
        return $city;
    $link = db_connect_web();
    $sql = "select city_name from city_type where city_concise=\"{$city}\"";
    $result = mysql_query($sql, $link);
    if ($row = mysql_fetch_array($result))
        $cityCname = $row["city_name"];
    else
        $cityCname = $city;
    mysql_free_result($result);
    mysql_close($link);

    return $cityCname;
}

function getDpCityList($link) {
    $sql = "SELECT city_name,city_concise FROM city_type ORDER BY city_name;";
    $result = mysql_query($sql, $link);
    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        $ret[] = $row;
    }
    mysql_free_result($result);

    return $ret;
}

function getDpCityListGroupByName($link) {
    $A = ord("A");
    $ret = array();
    for ($i=0; $i<26; $i++) {
        $ret[chr($A+$i)] = array();
    }
    $ret["#"] = array();

    $result = getDpCityList($link);
    foreach ($result as $row) {
        $index = getSpellInitial($row["city_name"]);

        $ret[$index][] = $row;
    }

//    foreach ($ret as $m=>$each) {
//        echo $m."<br/>";
//        foreach ($each as $l) {
//            var_dump($l);
//            echo "<br />";
//        }
//    }

    return $ret;

}

function getShopTopImg($shop_id) {
    $file_path = "/home/bbs/pic_home/comment";
    $file_path_rewrite = "/commentimg";
    // ���·��
    $op_path = "/$shop_id/topimg";
    if (file_exists($file_path.$op_path))
        $img = $file_path_rewrite.$op_path;
    else
        $img = "img/img_error.jpg";

    return $img;
}

function getShopImg($shop_id, $dir, $img_name) {
    $file_path = "/home/bbs/pic_home/comment";
    $file_path_rewrite = "/commentimg";
    // ���·��
    $op_path = "/$shop_id/$dir/{$img_name}_nail";
    if (file_exists($file_path.$op_path))
        $img = $file_path_rewrite.$op_path;
    else
        $img = "img/img_error.jpg";

    return $img;
}

function getShopCommentImg($shop_id, $img_name) {
    $file_path = "/home/bbs/pic_home/comment";
    $file_path_rewrite = "/commentimg";
    // ���·��
    $op_path = "/$shop_id/comment_pic/{$img_name}_nail";
    if (file_exists($file_path.$op_path))
        $img = $file_path_rewrite.$op_path;
    else
        $img = "img/img_error.jpg";

    return $img;
}

function getShopCommentImgList($shop_id, $photos) {
    if (!$photos) {
        return null;
    } else {
        $photoList = explode(",", $photos);
        $photoUrl = array();
        foreach ($photoList as $img_name) {
            $photoUrl[] = getShopCommentImg($shop_id, $img_name);
        }
        return $photoUrl;
    }
}

function getDpRecommendShops($link, $city) {
    // �Ƽ����� Ҫ�� rank < 10 �� ��ǰʱ����start_time~end_time֮��,���̵� review_result != 4
    if ($city == "all") {
        $sql = "SELECT s1.shop_id AS shop_id,rank,end_time,cnName,location,contact,avg_pay,s1.add_reason AS add_reason FROM recommend_shop s1,shop_info s2 WHERE
            rank<=10 AND rank>=1 AND review_result<>4 AND start_time<=UNIX_TIMESTAMP(now()) AND end_time>=UNIX_TIMESTAMP(now()) AND s2.shop_id=s1.shop_id ORDER BY rank";
    } else {
        $sql = "SELECT s1.shop_id AS shop_id,rank,end_time,cnName,location,contact,avg_pay,s1.add_reason AS add_reason FROM recommend_shop s1,shop_info s2 WHERE
            rank<=10 AND rank>=1 AND review_result<>4 AND s1.city_type='$city' AND start_time<=UNIX_TIMESTAMP(now()) AND end_time>=UNIX_TIMESTAMP(now()) AND s2.shop_id=s1.shop_id ORDER BY rank";
    }

    $result = mysql_query($sql, $link);
    $ret = array();
    $rank = 1;
    while ($row = mysql_fetch_array($result)) {
        $tmp = array();
        $tmp["rank"] = $rank;
        $tmp["shop_id"] = $row["shop_id"];
        $tmp["addr"] = $row["location"];
        $tmp["shop_name"] = $row["cnName"];
        $tmp["telephone"] = $row["contact"];
        $tmp["avg_pay"] = $row["avg_pay"];
        $tmp["add_reason"] = $row["add_reason"];
        $tmp["img"] = getShopTopImg($tmp["shop_id"]);
        $tmp["href"] = "one_shopinfo.php?shop_id=".$tmp["shop_id"];

        $ret[] = $tmp;
        $rank++;
    }

    mysql_free_result($result);
    return $ret;
}

function getShopInfoById($link, $shop_id) {
    $shop_id = intval($shop_id);
    $sql = "SELECT cnName,avg_score,avg_pay,taste_score,env_score,sev_score,location,contact,business_time FROM shop_info WHERE
            shop_id=$shop_id;";
    $sql_img = "SELECT img_name,type as dir FROM comment_img WHERE shop_id=$shop_id";
    $result = mysql_query($sql, $link);
    $result_img = mysql_query($sql_img, $link);

    $shop_info = array();
    if ($row = mysql_fetch_array($result)) {
        $shop_info = $row;
        if ($row_img = mysql_fetch_array($result_img)) {
            $shop_info["img"] = getShopImg($shop_id, $row["dir"], $row_img["img_name"]);
        }
    }

    mysql_free_result($result);
    return $shop_info;
}

/*
 * $pageΪ������ʼҳ,$numΪÿҳ������
 * */
function getShopComment($link, $shop_id, $page, $num=10) {
    $page = ($page-1)*$num;
    // display < 2 �ſ�����ʾ
    $sql = "SELECT user_id,user_name,avg_score,consume,content,photos FROM user_comment WHERE
            shop_id=$shop_id AND display<2 ORDER BY create_time DESC LIMIT $page,$num;";

    $result = mysql_query($sql, $link);
    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        if ($row["photos"]) {
            $row["img_list"] = getShopCommentImgList($shop_id, $row["photos"]);
        }

        $ret[] = $row;
    }

    mysql_free_result($result);
    return $ret;
}

function getShopCommentTotal($link, $shop_id) {
    $sql = "SELECT COUNT(*) AS count FROM user_comment WHERE shop_id=$shop_id AND display<2;";

    $result = mysql_query($sql, $link);
    $count = 0;
    if ($row = mysql_fetch_array($result)) {
        $count = $row["count"];
    }

    return $count;
}

// distance ��λǧ��
function getDistanceString($distance) {
    $str = "";
    if ($distance < 0.1) {
        $str = "<100m";
    } else if ($distance < 1) {
        $str = round($distance*1000, 0)."m";
    } else if ($distance < 10) {
        $str = round($distance, 1)."km";
    } else if ($distance < 50) {
        $str = round($distance, 0)."km";
    } else {
        $str = ">50km";
    }

    return $str;
}

/**
 * ���վ�������
 */
function getShopNearBy($link, $city, $cond, $pos, $page, $num=20) {
    $distance_null_num = 0;
    $page = ($page-1)*$num;
    $having1 = "";
    $having2 = "";
    // ȫ��,�Ȳ�ѯ���벻ΪNULL�Ľ��,����$num��ʱ�ٲ�ѯ����ΪNULL�Ľ��

    if (isset($cond["distance_null_num"]) AND $cond["distance_null_num"] > 0) {
        $distance_null_num = $cond["distance_null_num"];
    }


    // ���� cond ������������ȡ������Ϣ
    $sql = "SELECT shop_id,cnName,avg_score,avg_pay,type_set,(2 * 6378.137* ASIN(SQRT(POW(SIN(PI()*({$pos["lat"]}-lat)/360),2)+COS(PI()*{$pos["lon"]}/180)*COS(lat * PI()/180)
           *POW(SIN(PI()*({$pos["lon"]}-lng)/360),2)))) as distance FROM shop_info WHERE review_result<>4 ";
    /* $sql_standby ����:
     *  ��һ���Բ�ѯȫ�����,�������л�ʹ distance=NULL ����ǰ��,���Խ���ѯ��Ϊ����:
     *      1.�Ȳ�ѯ distance is not null �Ľ��,ʹ�� $sql
     *      2.����ѯ������� $num ʱ,�ٲ�ѯ distance is null �Ľ��,ʹ�� $sql_standby
     */
    $sql_standby = $sql;

    $condition = "";
    if ($city != "all") {
        $condition .= "AND city_type='$city' ";
    }

    if ($cond["food_class_type"] != "all") {
        $condition .= "AND type_set='{$cond["food_class_type"]}' ";
    }

    if (isset($cond["search"])) {
        if ($cond["search"]["fuzzy"])
            $condition .= "AND cnName LIKE BINARY '%{$cond["search"]["name"]}%' ";
        else
            $condition .= "AND cnName='{$cond["search"]["name"]}' ";
    }

    $having1 = "HAVING distance IS NOT NULL ORDER BY distance ASC ";
    $having2 = "HAVING distance IS NULL ";


    $sql = $sql.$condition.$having1."LIMIT $page,$num;";
//    $sql = $sql.$condition;
    $result = mysql_query($sql, $link);
    $result_num = mysql_num_rows($result);

    $ret = array();
    $end_flag = 0;
    // distance_null_num == 0 ��ʾ�����NULL�����ݻ�û�в���
    if ($distance_null_num == 0) {
        while ($row = mysql_fetch_array($result)) {
            if ($pos["locate_flag"])
                $row["distance_str"] = getDistanceString($row["distance"]);
            else
                $row["distance_str"] = "��λʧ��";


            if (!isset($row["avg_pay"]))
                $row["avg_pay"] = 0.0;
            if (!isset($row["avg_score"]))
                $row["avg_score"] = 0.0;

            $row["img"] = getShopTopImg($row["shop_id"]);
            $row["href"] = "one_shopinfo.php?shop_id=".$row["shop_id"];
            $ret[] = $row;
        }
        mysql_free_result($result);

        // ��ѯ������� $num ��ʱ
        if ($result_num < $num) {
            // ��distance IS NULL �Ľ���в�ѯ���� $num ��ʣ�ಿ��
            $sql_standby = $sql_standby.$condition.$having2."LIMIT 0,".($num-$result_num);
            $result_standby = mysql_query($sql_standby, $link);
            if (mysql_num_rows($result_standby) < ($num-$result_num))
                $end_flag = 1;
            else
                $end_flag = 0;

            while ($row = mysql_fetch_array($result_standby)) {
                $row["distance_str"] = "��λʧ��";
                if (!isset($row["avg_pay"]))
                    $row["avg_pay"] = 0.0;
                if (!isset($row["avg_score"]))
                    $row["avg_score"] = 0.0;

                $row["img"] = getShopTopImg($row["shop_id"]);
                $row["href"] = "one_shopinfo.php?shop_id=".$row["shop_id"];
                $ret[] = $row;
            }

            $distance_null_num = $num-$result_num;
            mysql_free_result($result_standby);
        }
    } else {
        // ��disance_null_num ��0 ʱֱ�Ӳ�ѯ distance IS NULL �Ľ��
        $sql_standby = $sql_standby.$condition.$having2."LIMIT $distance_null_num,$num";
        $result_standby = mysql_query($sql_standby, $link);
        $result_num = mysql_num_rows($result_standby);
        if ($result_num < $num)
            $end_flag = 1;
        else
            $end_flag = 0;

        while ($row = mysql_fetch_array($result_standby)) {
            $row["distance_str"] = "��λʧ��";
            if (!isset($row["avg_pay"]))
                $row["avg_pay"] = 0.0;
            if (!isset($row["avg_score"]))
                $row["avg_score"] = 0.0;

            $row["img"] = getShopTopImg($row["shop_id"]);
            $row["href"] = "one_shopinfo.php?shop_id=".$row["shop_id"];
            $ret[] = $row;
        }

        $distance_null_num += $result_num;
        mysql_free_result($result_standby);
    } // end if $distance_null_num == 0
    return array($end_flag, $distance_null_num, $ret);
}

/*���ո�����������ѯ�����г��У�������"����������"
 * @param: city ѡ��鿴�ĳ���
 * @param: cond ��ѯ����
 * @param: pos �û���ǰ�ĵ���λ��
 * */
function getShopByCondition($link, $city, $cond, $pos, $page, $num=20) {
    $page = ($page-1)*$num;
    // ���� cond ������������ȡ������Ϣ

    /* �����ݿ��л�ȡһ�������ĵ�����Ϣ */
    $sql = "SELECT shop_id,cnName,avg_score,avg_pay,type_set,(2 * 6378.137* ASIN(SQRT(POW(SIN(PI()*({$pos["lat"]}-lat)/360),2)+COS(PI()*{$pos["lon"]}/180)*COS(lat * PI()/180)
               *POW(SIN(PI()*({$pos["lon"]}-lng)/360),2)))) as distance FROM shop_info WHERE review_result<>4 ";

    $condition = "";
    if ($city != "all") {
        $condition .= "AND city_type='$city' ";
    }

    if ($cond["food_class_type"] != "all") {
        $condition .= "AND type_set='{$cond["food_class_type"]}' ";
    }

    if (isset($cond["search"])) {
        if ($cond["search"]["fuzzy"])
            $condition .= "AND cnName LIKE BINARY '%{$cond["search"]["name"]}%' ";
        else
            $condition .= "AND cnName='{$cond["search"]["name"]}' ";
    }

    switch ($cond["near_type"]) {
        case 1:
            // <200m
            $condition .= "HAVING distance>0 AND distance<=0.2 ";
            break;
        case 2:
            // 500m
            $condition .= "HAVING distance>0 AND distance<=0.5 ";
            break;
        case 3:
            // 1km
            $condition .= "HAVING distance>0 AND distance<=1 ";
            break;
        case 4:
            // 2km
            $condition .= "HAVING distance>0 AND distance<=2 ";
            break;
        case 5:
            // 5km
            $condition .= "HAVING distance>0 AND distance<=5 ";
            break;
        default:
            break;
    }

    switch ($cond["order_type"]) {
        case 0:
           $condition .= "ORDER BY distance ASC ";
            break;
        case 1:
            // �������
            $condition .= "ORDER BY comment_num DESC ";
            break;
        case 2:
            // �������
            $condition .= "ORDER BY avg_score DESC ";
            break;
        case 3:
            // �������(���������)
            $condition .= "ORDER BY visit_num DESC ";
            break;
        case 4:
            // ��ζ���
            $condition .= "ORDER BY taste_score DESC ";
            break;
        case 5:
            // �������
            $condition .= "ORDER BY env_score DESC ";
            break;
        case 6:
            // �������
            $condition .= "ORDER BY sev_score DESC ";
            break;
        case 7:
            // �˾��������
            $condition .= "ORDER BY avg_pay DESC ";
            break;
        case 8:
            // �˾��������
            $condition .= "ORDER BY avg_pay ASC ";
            break;
    }

    $sql = $sql.$condition."LIMIT $page,$num;";
//    $sql = $sql.$condition;
    $result = mysql_query($sql, $link);
    if (mysql_num_rows($result) < $num)
        $end_flag = 1;
    else
        $end_flag = 0;

    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        if ($pos["locate_flag"] and isset($row["distance"])) {
           $row["distance_str"] = getDistanceString($row["distance"]) ;
        } else {
            $row["distance_str"] = "��λʧ��";
        }

        if (!isset($row["avg_pay"]))
            $row["avg_pay"] = 0.0;
        if (!isset($row["avg_score"]))
            $row["avg_score"] = 0.0;

        $row["img"] = getShopTopImg($row["shop_id"]);
        $row["href"] = "one_shopinfo.php?shop_id=".$row["shop_id"];
        $ret[] = $row;
    }
    mysql_free_result($result);

    return array($end_flag, $ret);
}

function insertShop($link, $shopinfo){
    $sql = 'insert into shop_info(cnName,city_type,type_set,enName,location,contact,business_time,description,lng,lat,add_time,review_result,add_user) values
        ("'.$shopinfo["cnName"].'","'.$shopinfo["city_type"].'","'.$shopinfo["type_set"].'","'.$shopinfo["enName"].'","'.$shopinfo["location"].'","'.$shopinfo["contact"].'",
            "'.$shopinfo["business_time"].'","'.$shopinfo["descrption"].'",'.$shopinfo["lng"].','.$shopinfo["lat"].','.time().',0,"'.$shopinfo["add_user"].'")';

    $ret = mysql_query($sql, $link);
    if ($ret) {
        return true;
    } else {
        return false;
    }
}

// $reason Ϊ top10 ������
// $reason: hot ��������,best �������,taste ��ζ���,env �������,sev �������
function getShopTop10($link, $reason, $city, $pos) {
    $week = date('w');
    $createTime = strtotime(date("Y M D",strtotime( '+'. 1-$week .' days' )));

    switch ($reason){
        case "hot":
            $sql = 'select r.shop_id as shop_id,type_set,cnName,location,contact,comment_num,s.lat as lat,s.lng as lng,s.avg_pay as avg_pay,s.avg_score as avg_score,s.taste_score as taste_score,s.env_score as env_sore,s.sev_score as sev_score from user_comment as r,shop_info as s
                where city_type="'.$city.'" and display<2 and create_time>'.$createTime.' and display<2 and r.shop_id=s.shop_id group by shop_id order by count(*) desc limit 10';
            break;
        case "best":
            $sql = 'select shop_id,type_set,cnName,location,contact,comment_num,lat,lng,avg_pay,avg_score,taste_score,env_score,sev_score from shop_info
                where city_type="'.$city.'" and review_result<>4 order by avg_score desc limit 10';
            break;
        case "taste":
            $sql = 'select shop_id,type_set,cnName,location,contact,comment_num,lat,lng,avg_pay,avg_score,taste_score,env_score,sev_score from shop_info
                 where city_type="'.$city.'" and review_result<>4 order by taste_score desc limit 10';
            break;
        case "env":
            $sql = 'select shop_id,type_set,cnName,location,contact,comment_num,lat,lng,avg_pay,avg_score,taste_score,env_score,sev_score from shop_info
                where city_type="'.$city.'" and review_result<>4 order by env_score desc limit 10';
            break;
        case "sev":
            $sql = 'select shop_id,type_set,cnName,sev_score,location,contact,comment_num,lat,lng,avg_pay,avg_score,taste_score,env_score,sev_score from shop_info
                where city_type="'.$city.'" and review_result<>4 order by sev_score desc limit 10';
            break;
        default:
            return array();
    }
    log2file($sql);

    $result = mysql_query($sql, $link);

    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        if ($pos["locate_flag"] == false) {
            $row["distance_str"] = "��λʧ��";
        } else {
            if ($row["lat"] == 0 and $row["lng"] == 0) {
                $row["distance_str"] = "��λʧ��";
            } else {
                $distance = getDistance($pos["lat"], $pos["lng"], $row["lat"], $row["lng"]);
                $row["distance_str"] = getDistanceString($distance);
            }
        }

        if (!isset($row["avg_pay"]))
            $row["avg_pay"] = 0.0;
        if (!isset($row["avg_score"]))
            $row["avg_score"] = 0.0;

        $row["img"] = getShopTopImg($row["shop_id"]);
        $row["href"] = "one_shopinfo.php?shop_id=".$row["shop_id"];

        $ret[] = $row;
    }

    return $ret;
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
        closedir($handler);
    }
    if($filename_return == ""){
        $filename_return = "/mobile/forum/img/club.png";
        return $filename_return;
    }

    $filename_return = "/clubimg/".strtoupper(substr($club_name, 0, 1))."/".$club_name."/$filename_return";
    return $filename_return;
}

function getBoardImg($board_name) {
    $filepath = BBS_HOME.'/pic_home/boards/'.$board_name.'/boardimg';
    $filename_return = "";
    if (file_exists($filepath))
        $filename_return = "boardimg";
    if ($filename_return == "") {
        $filename_return = "/mobile/forum/img/jy_f_img.png";
        return $filename_return;
    }

    $filename_return = "/boardimg/".$board_name."/$filename_return";
    return $filename_return;
}

/* ��ȡʮ�����Ż��� */
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


        //����ID boardID ��ID groupID ����ID articleID ���±��� title ���� author BoardsEngName  BoardsName
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

/* ��ȡʮ���Ƽ����� */
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


        //����ID boardID ��ID groupID ����ID articleID ���±��� title ���� author BoardsEngName  BoardsName
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

/* ��ȡ���Ű��� */
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
        /* ��ȡ�������� */
        $t_element["total"] = getBoardGroupNum($brdarr["BOARD_ID"], $link);
        $ret[]=$t_element;
    }
    return $ret;
}

/* ��ȡ���ž��ֲ� */
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
        // ���ɾ��ֲ���Ӧ��url
        $club_url = url_generate(2, array("club" => $row["club_name"]));
        // ���ֲ���ҳͼƬ·��
        $club_img = getClubImg($row["club_name"]);
        // ���ֲ�������
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

    $sql = " select article_id,filename,club_name,filename,groupid,owner,read_num,total_reply as reply_num,posttime,title from club_dir_article_$table_id
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

/* ��ȡ���ֲ������Ƽ����� */
function getRecommendClubArticle($link, $page, $group_id){
    $return = array();
    $hot_time = 30;//�Ƽ�������Ч����
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
    // �ö����� �����Ƽ� ��̳���� ��������
    "index" => array("top", "hot", "classical", "classes"),
    // ���ӻ� ���� ���� ���� ���� �Ƽ� �ƾ�
    "news" => array("news_mix", "news_military", "news_international", "news_sport", "news_recreation", "news_science", "news_finance"),
    // ��ѡ ��� Ů�� ���� ��Ϸ ���� ����
    "club" => array("club_handpick", "club_emotion", "club_woman", "club_sport", "club_game", "club_recreation", "club_music",
                    "club_hobby", "club_life", "club_finance", "club_schoolfellow", "club_hisfellow", "club_politics", "club_science",
                    "club_literature", "club_art", "club_other"),
    // ����ר�� ������ʦ �������� ����ǩ֤��Ϣ ������
    "immigration" => array("i_column", "i_lawyer", "i_news", "i_visa", "i_discussion"),
    // ѡ����� �Ƽ� ���� ���� ����
    "dianping" => array("dp_recommend", "dp_near", "dp_search", "dp_rank"),
    // ��ҳ ��ע ��˿ �ҵ������� �ҵľ��ֲ� �ҵĵ��� �ҵ����� �ҵ��ղ� �ҵĺ��� �ҵĺ����� �ҵ���Ϣ �ҵ��ʼ�
    "jiaye" => array("jiaye", "focus", "fans", "discuss", "club", "dianping", "article", "collect", "friend", "black", "message", "email")
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

$club_class_list = array(
    "handpick" => array("id"=>"0", "name"=>"��ѡ"),
    "emotion" => array("id"=>"1", "name"=>"�����"),
    "woman" => array("id"=>"2", "name"=>"Ů����"),
    "sport" => array("id"=>"3", "name"=>"������"),
    "game" => array("id"=>"4", "name"=>"��Ϸ��"),
    "recreation" => array("id"=>"5", "name"=>"������"),
    "music" => array("id"=>"6", "name"=>"������"),
    "hobby" => array("id"=>"7", "name"=>"������"),
    "life" => array("id"=>"8", "name"=>"������"),
    "finance" => array("id"=>"9", "name"=>"�ƾ���"),
    "schoolfellow" => array("id"=>"10", "name"=>"У����"),
    "hisfellow" => array("id"=>"11", "name"=>"ͬ����"),
    "politics" => array("id"=>"12", "name"=>"ʱ����"),
    "science" => array("id"=>"13", "name"=>"�Ƽ���"),
    "literature" => array("id"=>"14", "name"=>"��ѧ��"),
    "art" => array("id"=>"15", "name"=>"������"),
    "other" => array("id"=>"100", "name"=>"����"),
);

$forum_class_list = array(
    "1" => array("section_name"=>"��������", "section_num"=>"1"),
    "2" => array("section_name"=>"��������", "section_num"=>"2"),
    "3" => array("section_name"=>"��������", "section_num"=>"3"),
    "4" => array("section_name"=>"��������", "section_num"=>"4"),
    "5" => array("section_name"=>"��������", "section_num"=>"5"),
    "6" => array("section_name"=>"�������", "section_num"=>"6"),
    "7" => array("section_name"=>"��ѧ����", "section_num"=>"7"),
    "8" => array("section_name"=>"У������", "section_num"=>"8"),
    "9" => array("section_name"=>"��������", "section_num"=>"9"),
    "10" => array("section_name"=>"��������", "section_num"=>"a"),
    "11" => array("section_name"=>"ѧ��ѧ��", "section_num"=>"b"),
    "12" => array("section_name"=>"��վϵͳ", "section_num"=>"0")
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

$near_list = array(
    0 => array("type"=>"0", "name"=>"ȫ��"),
    1 => array("type"=>"1", "name"=>"<200m"),
    2 => array("type"=>"2", "name"=>"500m"),
    3 => array("type"=>"3", "name"=>"1km"),
    4 => array("type"=>"4", "name"=>"2km"),
    5 => array("type"=>"5", "name"=>"5km")
);

$food_class_list = array(
    0 => array("type"=>"all", "name"=>"ȫ����ʳ"),
    1 => array("type"=>"zc", "name"=>"�в�"),
    2 => array("type"=>"wm", "name"=>"����"),
    3 => array("type"=>"hg", "name"=>"���"),
    4 => array("type"=>"zzc", "name"=>"������"),
    5 => array("type"=>"sk", "name"=>"�տ�"),
    6 => array("type"=>"kc", "name"=>"���"),
    7 => array("type"=>"tp", "name"=>"��Ʒ"),
    8 => array("type"=>"qt", "name"=>"����"),
);

$order_list = array(
    0 => array("type"=>"0", "name"=>"�������"),
    1 => array("type"=>"1", "name"=>"�������"),
    2 => array("type"=>"2", "name"=>"�������"),
    3 => array("type"=>"3", "name"=>"�������"),
    4 => array("type"=>"4", "name"=>"��ζ���"),
    5 => array("type"=>"5", "name"=>"�������"),
    6 => array("type"=>"6", "name"=>"�������"),
    7 => array("type"=>"7", "name"=>"�˾����"),
    8 => array("type"=>"8", "name"=>"�˾����")
);

$rank_list = array(
    0 => array("type"=>"hot", "name"=>"��������"),
    1 => array("type"=>"best", "name"=>"��Ѳ���"),
    2 => array("type"=>"taste", "name"=>"��ζ���"),
    3 => array("type"=>"env", "name"=>"�������"),
    4 => array("type"=>"sev", "name"=>"�������")
);

function initFoodClassList($link) {
    global $food_class_list;
    $sql = "SELECT type_name,type_concise FROM shop_type;";
    $result = mysql_query($sql, $link);

    $ret[0] = array("type"=>"all", "name"=>"ȫ����ʳ");
    while ($row = mysql_fetch_array($result)) {
        $tmp["type"] = $row["type_concise"];
        $tmp["name"] = $row["type_name"];

        $ret[] = $tmp;
    }
    mysql_free_result($result);

    if (sizeof($ret) == 1)
        $ret = $food_class_list;

    return $ret;
}


function foodType2String($type) {
    global $food_class_list;

    $ret = "";
    foreach ($food_class_list as $each) {
        if ($each["type"] == $type)
            $ret = $each["name"];
    }

    return $ret;
}

function url_generate($level, $data) {
    /*  level ��Ϊ4��, $data��ʽ����
     *      1: ����
     *          array("class"=>$class_num)
     *      2: ����/���ֲ�
     *          ���棺array("board"=>$board_name)
     *          ���ֲ���array("club"=>$club_name)
     *      3: ����
     *          array("board"=>$board_name, "group"=>$group_id)
     *          array("club"=>$clubname, "group"=>$group_id)
     *          array("news"=>$newsboardname, "group"=>$group_id)
     *      4: DIY ����action��args������url
     *          $data = {
     *              "action"=>"one_test.php"
     *              "args"=>{"type"=>"top", "groupid"=>"2"}
     *          }
     *          ����  one_test.php?type=top&groupid=2
     *
     * */
    $url = "/404.html";
    // ע: type����Ϊ��
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
                    // ��̳�����µ���ת��ַ
                    $url = 'one_group.php?type='.$data["type"].'&board='.$data["board"].'&group='.$data["groupid"];
                else if (isset($data["club"]))
                    // ���ֲ�������ת��ַ
                    $url = 'one_group_club.php?type='.$data["type"].'&club='.$data["club"].'&group='.$data["groupid"];
                else if (isset($data["news"]))
                    // ����������ת��ַ
                    $url = 'one_group_news.php?type='.$data["type"].'&news='.$data["news"].'&group='.$data["groupid"];
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
// ҳ��̫��
function page_partition($total_row, $page, $per_page=10, $show_page=3) {
    $total_page = intval($total_row/$per_page)+1;
    $frt_page = 1;
    $end_page = $total_page;
?>
    <div id="page_part" class="pages_box margin-bottom">
        <?php if ($page != 1) {?>
        <a id="pre_page" href="">��һҳ</a>
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
        <a id="sub_page" href="">��һҳ</a>
    <?php }elseif($frt_page != $end_page){?>
        <a id="page_now" href=""><?php echo $end_page;?></a>
    <?php }?>

    </div><!-- End pages_box   ÿҳ��ʾ20ƪ-->
<?php
}
*/

/* ֻ����3����ť   ... 5 6 7 ...
 *               ��ǰҳ ^
*/
function page_partition($total_row, $page, $per_page=10, $show_page=1) {
    if ($total_row <= $per_page)
        return false;
    $total_page = intval($total_row/$per_page)+1;
    $frt_page = 1;
    $end_page = $total_page;
    $page_str = '<div id="page_part" class="pages_box margin-bottom">';
    if ($page != 1) {
        $page_str .= '<a id="pre_page" href="">��һҳ</a>';
    }

    if($page-$frt_page > $show_page){
        $page_str .= "<span>...</span>";
    }

    $page_str .= '<a id="page_now" href="">'.$page.'</a>';

    if($end_page-$page > $show_page){
        $page_str .= "<span>...</span>";
        }

        if($page != $end_page){
            $page_str .= '<a id="sub_page" href="">��һҳ</a>';
        }

    $page_str .= '</div>';
    return $page_str;

}

function getLawyerHeadImg($creator) {
    return "/picture/".strtoupper(substr($creator, 0, 1))."/$creator/lawyerimg";
}

function getMainPageLawyers($link) {
    $sql = 'select lawyer_name,creator from lawyer where identity_flag="S" limit 10;';
    $result = mysql_query($sql, $link);
    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        $href = url_generate(4, array(
            "action" => "/mobile/forum/i_board.php",
            "args" => array("board"=>$row["creator"])
        ));
        $ret[] = array(
            "name" => $row["lawyer_name"],
            "href" => $href,
            "img" => getLawyerHeadImg($row["creator"])
        );
    }
    mysql_free_result($result);

    return $ret;
}

function getLawyerInfo($link, $boardname) {
    $sql = "select lawyer_name,introduction from lawyer where creator='$boardname';";
    $result = mysql_query($sql, $link);
    $ret = array();
    if ($row = mysql_fetch_array($result)) {
            $href = url_generate(4, array(
                "action" => "/mobile/forum/i_lawyerinfo.php",
                "args" => array("board"=>$boardname)
            ));
            $ret = array(
                "name" => $row["lawyer_name"],
                "href" => $href,
                "img" => getLawyerHeadImg($boardname),
                "desc" => $row["introduction"]
            );
    }

    return $ret;
}

function getLawyerGroupByName($link, $city) {
    $A = ord("A");
    $ret = array();
    for ($i=0; $i<26; $i++) {
        $ret[chr($A+$i)] = array();
    }
    $ret["Other"] = array();
    if ($city == "all")
        $sql = "select lawyer_name,identity_flag,creator from lawyer order by lawyer_name";
    else
        $sql = "select lawyer_name,identity_flag,creator from lawyer where city=\"{$city}\" order by lawyer_name";
    $result = mysql_query($sql, $link);
    while ($row = mysql_fetch_array($result)) {
        $name_tmp = iconv("UTF8", "GBK//IGNORE", $row["lawyer_name"]);
        if (!empty($name_tmp))
            $row["lawyer_name"] = $name_tmp;
        $index = getSpellInitial($row["lawyer_name"]);
        $row["href"] = url_generate(4, array(
            "action" => "/mobile/forum/i_lawyerinfo.php",
            "args" => array("board"=>$row["creator"])
        ));
        $row["img"] = getLawyerHeadImg($row["creator"]);
        $ret[$index][] = $row;
    }
    mysql_free_result($result);
    /*
    foreach ($ret as $m=>$each) {
        echo $m."<br/>";
        foreach ($each as $l) {
            var_dump($l);
            echo "<br />";
        }
    }*/

    return $ret;
}

function getLawyerGroupByArea($link) {
    $A = ord("A");
    $ret = array();
    for ($i=0; $i<26; $i++) {
        $ret[chr($A+$i)] = array();
    }
    $ret["Other"] = array();

    $sql = "select distinct(city) from lawyer order by lawyer_name";
    $result = mysql_query($sql, $link);
    while ($row = mysql_fetch_array($result)) {
        $index = getSpellInitial($row["city"]);
        $row["href"] = url_generate(4, array(
            "action" => "/mobile/forum/i_city.php",
            "args" => array("city"=>$row["city"])
        ));
        $ret[$index][] = $row;
    }
    mysql_free_result($result);

//    foreach ($ret as $m=>$each) {
//        echo $m."<br/>";
//        foreach ($each as $l) {
//            var_dump($l);
//            echo "<br />";
//        }
//    }

    return $ret;
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
        if($array["article_id"] == $list["article_id"] && $array["board_id"] == $list["board_id"]) {
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
        $aNew["source"] = "δ���ռ�";
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
            $aNew["href"] = url_generate(3, array("news"=>$row1["boardname"], "groupid"=>$aNew["groupID"]));
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
                    }else if(strpos(iconv("GBK", "UTF-8//IGNORE", $notes), "���߰�") !== false){
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
            $arr=explode("��",  $notes);
            $aNew["notes"] = str_replace("���߰���","", $arr[0]);
            $postdate = "".$row1["UNIX_TIMESTAMP(posttime)"];
            $nowdate = strtotime(date('Y/m/d'));

            $aNew["postTime"] = $postdate;
            if($row1["source"] != null)
                $aNew["source"] = $row1["source"];
            else
                $aNew["source"] = "δ���ռ�";
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
    preg_match('/(\[����\]|\[���ô�\]|\[��������\])/', $title, $arr);
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
        $US = "[����]";
        $CNA = "[���ô�]";
        $OTH = "[��������]";
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
        $aNew["title"] = preg_replace('/\[.*\]/', "", $aNew["title"]);
        $boardName = $row["boardname"];
        $boardCnName = $row["board_desc"];
        $aNew["BoardsName"] = trim(substr($boardCnName, strpos($boardCnName,']')+1));
        $aNew["BoardsEngName"] = $boardName;
        $aNew["boardID"] = $boardid;
        if ($newsTypeName != "immigration") {
            $aNew["href"] = url_generate(3, array("news"=>$boardName, "groupid"=>$row["groupid"]));
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
                } else if(strpos(iconv("GBK", "UTF-8//IGNORE", $notes),"���߰�")!==false) {
                    if (strpos($notes,"��") !== false) {
                        break;//ok,it is
                    } else {
                        while ($notes1 = fgets($filehandle)) {
                            if ($i = strpos(iconv("GBK", "UTF-8//IGNORE", $notes1), "��")) {
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
        $arr=explode("��",  $notes);
        $aNew["notes"] = str_replace("���߰���","",$arr[0]);
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
        $aNew["source"] = "δ���ռ�";
        $sql = "select source,read_num,total_reply from  dir_article_{$aNew["boardID"]} where article_id={$aNew["articleID"]}";
        $result1 = mysql_query($sql, $link);
        if ($row1 = mysql_fetch_array($result1)) {
            if($row1["source"] != null)
                $aNew["source"] = $row1["source"];
            else
                $aNew["source"] = "δ���ռ�";
            $aNew["read_num"] = $row1["read_num"];
            $aNew["total_reply"] = $row1["total_reply"];
        } else {
            $aNew["source"] = "δ���ռ�";
            $aNew["read_num"] = "0";
            $aNew["total_reply"] = "0";
        }
        $aNew["total_reply"] = getNewsReply($link, $aNew["boardID"], $aNew["articleID"]);
        $ret[] = $aNew;
    }
    usort($ret, 'sortByActionTime');

    return array($ret, $end_flag);
}

function getNewsReply($link, $board_id, $group_id) {
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

        $sql = "select read_num,total_reply as reply_num,owner from dir_article_".$brdarr["BOARD_ID"]." where article_id='{$record["groupID"]}'";
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

function getCurrUserInfo() {
    global $currentuser;

    $data = array();
    $num = bbs_getcurrentuinfo($data);
    $userData["LOGINTIME"] = $data["logintime"];
    $userData["UTMPKEY"] = $data["utmpkey"];
    $userData["UTMPNUM"] = $num;
    $userData["loginNum"] = $currentuser["numlogins"];
    $userData["UTMPUSERID"] = $data["userid"];
    $userData["LOGINTIME"] = $data["logintime"];
    $userData["loginResult"] = "1"; //ok
    $userData["headimg"] = get_user_img($userData["UTMPUSERID"]);
    $con = db_connect_web();

    $strSql = "select numeral_user_id from users where user_id='{$userData["UTMPUSERID"]}'";
    $resultcnt = mysql_query($strSql, $con);
    if($rowsCnt = mysql_fetch_array($resultcnt)) {
        $user_id = $rowsCnt["numeral_user_id"];

    } else {
        return null;
    }
    $strSql="SELECT count(*) retCount  FROM dir_article_memory USE INDEX (index_ownerid_posttime)  WHERE owner_id=$user_id";
    $resultcnt = mysql_query($strSql, $con);
    if($rowsCnt = mysql_fetch_array($resultcnt)) {
        $userData["articleNum"]=$rowsCnt["retCount"];

    } else {
        $userData["articleNum"]="0";
    }
    $strSql=" select count(fav_article_id) retCount from fav_article where  user_numid=$user_id and (type=1 or type=2 or type=11)";
    $resultcnt = mysql_query($strSql, $con);
    if($rowsCnt = mysql_fetch_array($resultcnt)) {
        $userData["favNum"]=$rowsCnt["retCount"];

    } else {
        $userData["favNum"]="0";
    }
    $strSql = "SELECT count(club.club_id) retCount FROM club, club_group, club_member WHERE club.club_group_id=club_group.club_group_id
                AND club.club_id=club_member.club_id AND member_num_id=$user_id";
    $resultcnt = mysql_query($strSql, $con);
    if($rowsCnt = mysql_fetch_array($resultcnt)) {
        $userData["clubNum"]=$rowsCnt["retCount"];

    } else {
        $userData["clubNum"]="0";
    }
    $sqlStr="SELECT count(numeral_friend_id) retCount  FROM friend_list
                  WHERE numeral_user_id=$user_id AND type<>2";
    $resultcnt = mysql_query($sqlStr, $con);
    if($rowsCnt = mysql_fetch_array($resultcnt)) {
        $userData["friendNum"]=$rowsCnt["retCount"];

    } else {
        $userData["friendNum"]="0";
    }
    $sqlstr="select count(users.user_id) retCount from friend_list,users  where friend_list.numeral_user_id=$user_id and
                 friend_list.numeral_friend_id=users.numeral_user_id and friend_list.type=2";
    $resultcnt = mysql_query($sqlstr, $con);
    if($rowsCnt = mysql_fetch_array($resultcnt)) {
        $userData["blackNum"] = $rowsCnt["retCount"];

    }else{
        $userData["blackNum"] = "0";
    }
    $sqlstr="select count(1) retCount from funs_list f,users u where f.numeral_user_id=$user_id and
                 f.numeral_friend_id=u.numeral_user_id and f.type=1";
    $resultcnt = mysql_query($sqlstr, $con);
    if($rowsCnt = mysql_fetch_array($resultcnt))
    {
        $userData["solicitudeNum"]=$rowsCnt["retCount"];

    }else {
        $userData["solicitudeNum"] = "0";
    }
    $sqlstr = "select count(1) retCount from funs_list f,users u where f.numeral_friend_id=$user_id and
                 f.numeral_user_id=u.numeral_user_id and f.type=1";
    $resultcnt = mysql_query($sqlstr, $con);
    if($rowsCnt = mysql_fetch_array($resultcnt)) {
        $userData["funsNum"]=$rowsCnt["retCount"];

    } else {
        $userData["funsNum"] = "0";
    }
    $unread = 0;
    $total = 0;
    bbs_getmailnum($userData["UTMPUSERID"], $total,$unread, 0, 0);
    $userData["mailNum"]="$unread";
    mysql_close($con);
    return $userData;
}

/* ����ע��ϵ:
 *  ����0  ˫���޹�ע��ϵ
 *  ����1  ��user��עfriend
 *  ����2  ��friend��עuser
 *  ����3  ˫�������ע
 */
function checkFocusEach($link, $user_id, $friend_id) {
    // ��� user_id �Ĺ�ע�б����Ƿ��� friend_id
    $sql1 = "SELECT COUNT(*) AS count FROM funs_list WHERE numeral_user_id=$user_id AND numeral_friend_id=$friend_id AND type=1;";
    // ��� friend_id �Ĺ�ע�б����Ƿ��� user_id
    $sql2 = "SELECT COUNT(*) AS count FROM funs_list WHERE numeral_user_id=$friend_id AND numeral_friend_id=$user_id AND type=1;";

    $result1 = mysql_query($sql1, $link);
    $result2 = mysql_query($sql2, $link);

    if (mysql_fetch_array($result1)["count"] == 0)
        $flag1 = 0;
    else
        $flag1 = 1;

    if (mysql_fetch_array($result2)["count"] == 0)
        $flag2 = 0;
    else
        $flag2 = 2;

    mysql_free_result($result1);
    mysql_fetch_array($result2);
    return ($flag1+$flag2);
}

function checkFriendEach($link, $user_id, $friend_id) {
    // ��� user_id �ĺ����б����Ƿ��� friend_id
    $sql1 = "SELECT COUNT(*) AS count FROM friend_list WHERE numeral_user_id=$user_id AND numeral_friend_id=$friend_id AND type=0 AND is_approve='Y' ;";
    // ��� friend_id �ĺ����б����Ƿ��� user_id
    $sql2 = "SELECT COUNT(*) AS count FROM friend_list WHERE numeral_user_id=$friend_id AND numeral_friend_id=$user_id AND type=0 AND is_approve='Y' ;";

    $result1 = mysql_query($sql1, $link);
    $result2 = mysql_query($sql2, $link);

    if (mysql_fetch_array($result1)["count"] == 0)
        $flag1 = 0;
    else
        $flag1 = 1;

    if (mysql_fetch_array($result2)["count"] == 0)
        $flag2 = 0;
    else
        $flag2 = 2;

    mysql_free_result($result1);
    mysql_fetch_array($result2);
    return ($flag1+$flag2);
}

function getMyFriendList($link, $user_id, $app_type, $page, $num=10){
    global $currentuser;
    $ret = array();

    if ($currentuser['userid']=='guest'||$currentuser['userid']!=$user_id)
        return NULL;

    if (!$link) {
        return;
    }
    $page = ($page-1)*$num;

    $numeral_user_id = $currentuser["num_id"];

    // 0 friend 1 solicitude 2 apply 3 fans
    if ($app_type==0)
        $sqlStr="SELECT numeral_friend_id,expr,app_reason,type,add_date,is_show,is_approve FROM friend_list WHERE type=0 AND is_approve='Y' AND numeral_user_id=$numeral_user_id AND type=0 ORDER BY add_date DESC";
    elseif ($app_type==1)
        $sqlStr="SELECT numeral_friend_id,expr,app_reason,type,add_date,is_show,is_approve FROM funs_list WHERE type=1 AND numeral_user_id=$numeral_user_id ORDER BY add_date DESC";
    elseif ($app_type==2)
        $sqlStr="SELECT numeral_friend_id,expr,app_reason,type,add_date,is_show,is_approve FROM friend_list WHERE (is_show='N' AND is_approve='N' )  AND numeral_user_id=$numeral_user_id AND type=0 ORDER BY add_date DESC limit 100";
    elseif ($app_type==3)
        $sqlStr="SELECT numeral_user_id as numeral_friend_id,expr,app_reason,type,add_date,is_show,is_approve FROM funs_list WHERE type=1 AND numeral_friend_id=$numeral_user_id ORDER BY add_date DESC";
    else {
        return;
    }

    $sqlStr .= " LIMIT $page,$num";
    $circle_re = mysql_query($sqlStr, $link);
    if(!$circle_re) {
        return;
    }
    $n=1;
    while ($circle_row = mysql_fetch_array($circle_re)) {
        $num_user_id = $circle_row["numeral_friend_id"];
        $friend_userid = get_userid2($circle_row["numeral_friend_id"], $link);
        $data = array();
        $data["num_user_id"] = $circle_row["numeral_friend_id"];
        $data["user_id"] = $friend_userid;
        if ($data["user_id"] == null ) {
            $data["user_id"] = "";
        }

        $data["expr"] = $circle_row["expr"];
        $data["app_reason"] = $circle_row["app_reason"];

        $data["headimg"] = get_user_img($friend_userid);
        $data["href"] = "memberinfo.php?userid=$friend_userid";

        if ($circle_row["type"] == 0)
            $data["is_friend"] = "1";
        else
            $data["is_friend"] = "2";
        $data["type"] = $circle_row["type"];
        if($app_type == 1||$app_type == 3) {
            if ($app_type == 1) {
                $is_sql = "SELECT numeral_user_id FROM funs_list WHERE numeral_user_id=$num_user_id AND numeral_friend_id =$numeral_user_id ORDER BY add_date DESC";
                $is_f_sql = "SELECT numeral_user_id FROM friend_list WHERE type='0' AND numeral_friend_id=$num_user_id AND numeral_user_id =$numeral_user_id AND (is_approve='Y' OR is_approve='N') ORDER BY add_date DESC";
            }
            elseif ($app_type == 3) {
                $is_sql = "SELECT numeral_user_id FROM funs_list WHERE numeral_friend_id=$num_user_id AND numeral_user_id =$numeral_user_id ORDER BY add_date DESC";
                $is_f_sql = "SELECT numeral_user_id FROM friend_list WHERE type='0' AND numeral_user_id=$num_user_id AND numeral_friend_id =$numeral_user_id AND (is_approve='Y' OR is_approve='N') ORDER BY add_date DESC";
            }
            $is_result = mysql_query($is_sql, $link);
            $is_f_ret = mysql_query($is_f_sql, $link);
            if($is_row = mysql_fetch_array($is_result))
                $data["type"] = 1;
            else
                $data["type"] = 0;
            if($is_f_row=mysql_fetch_array($is_f_ret))
                $data["is_friend"] = "1";
            else
                $data["is_friend"] = "2";
        } elseif ($app_type == 2) { //send msg_id to app for reply
            $msg_arr = array();
            $msg_arr['msg_type'] = '1';
            $msg_arr['f_user'] = $friend_userid;
            $msg_arr['t_user'] = $user_id;
            $action = new Action($msg_arr);
            $action->get_msg_id();
            if(empty($action->msg_id))
                $data['msg_id']='no id';
            else
                $data['msg_id']=$action->msg_id;
            $action=NULL;
        } elseif ($app_type == 0) {
            $each_sql = "SELECT numeral_friend_id FROM friend_list WHERE numeral_user_id=$num_user_id AND numeral_friend_id =$numeral_user_id AND is_approve ='Y' limit 1 ";
            $each_ret = mysql_query($each_sql,$link);
            if($bf_row = mysql_fetch_array($each_ret))
                $data['is_each'] = '1';
            else
                $data['is_each'] = '0';
        }

        $data["is_show"] = $circle_row["is_show"];
        $data["is_approve"] = $circle_row["is_approve"];
        $data["add_date"] = $circle_row["add_date"];


        if(getOnlineStatus($friend_userid))
            $is_online = '����';
        else
            $is_online = '����';
        $data["user_status"]=$is_online;
        $ret[]=$data;
    }
    mysql_free_result($circle_re);

    return $ret;
}

/* ��� f_user �Ƿ��� t_user �ĺ����б��� */
function check_friend($link, $t_user, $f_user) {
    $sql = "SELECT COUNT(*) AS count FROM users u1,users u2,friend_list f WHERE
        u1.user_id='$t_user' AND u2.user_id='$f_user' AND
        f.numeral_user_id=u1.numeral_user_id AND f.numeral_friend_id=u2.numeral_user_id";
    $is_friend = false;
    $result = mysql_query($sql, $link);
    if ($row = mysql_fetch_array($result)) {
        if ($row["count"] == "0")
            $is_friend = false;
        else
            $is_friend = true;
    }

    mysql_free_result($result);
    return $is_friend;
}

// ��ȡ���������б�
function getFriendApply($link, $page, $num=10) {
    global $currentuser;
    $page = ($page-1)*$num;

    $user_id = $currentuser["userid"];
    $sql = "SELECT t_user,f_user,is_handle,content,UNIX_TIMESTAMP(msg_date) as date FROM sys_msg WHERE msg_type=1 AND (t_user='{$user_id}' OR f_user='{$user_id}') ORDER BY msg_date DESC limit $page,$num";
    $result = mysql_query($sql, $link);
    $ret = array();
    while ($row = mysql_fetch_array($result)) {
        $tmp["to"] = $row["t_user"];
        $tmp["from"] = $row["f_user"];
        $tmp["date"] = $row["date"];
        $tmp["content"] = iconv("UTF-8", "GBK//IGNORE", $row["content"]);
        $tmp["handle_result"] = "W"; // W��δ���� Y��ͬ�� N�Ѿܾ�

        if ($row["t_user"] == $user_id) {
            // �յ��ĺ�������
            // is_handle:"W"��ʾ���ڴ���,"Y"��ʾ�Ѵ���
            if ($row["is_handle"] == "Y") {
                // �Ѵ����������Ҫ��ѯfriend_list����ȡ��ѯ���
                if (check_friend($link, $row["t_user"], $row["f_user"])) {
                    $tmp["handle_result"] = "Y";
                } else {
                    $tmp["handle_result"] = "N";
                }
            } elseif ($row["is_handle"] == "N")
                continue;
        } else {
            // �Լ����͵ĺ�������
            if ($row["is_handle"] == "Y") {
                // ��ѯ�Լ��Ƿ��Ѿ��ڶԷ��ĺ����б���
                if (check_friend($link, $row["f_user"], $row["t_user"])) {
                    $tmp["handle_result"] = "Y";
                } else {
                    $tmp["handle_result"] = "N";
                }
            } elseif ($row["is_handle"] == "N")
                continue;
        }

        $ret[] = $tmp;
    }

    mysql_free_result($result);
    return $ret;
}

function makeMailPath($user_id, $filename) {
    $filepath = BBS_HOME."/mail/".strtoupper(substr($user_id, 0, 1))."/$user_id/$filename";
    if (file_exists($filepath))
        return $filepath;
    else
        return false;
}

/* $type
 *      "all"       ������������
 *      "total"     �ռ���
 *      "unread"    ���ʼ�
 *      "send"      �ѷ��͵��ʼ�(������)
 *      "delete"    ��ɾ�����ʼ�(������)
 */
function getMailNumByType($user_id, $type="all") {
    $ret = array();
    $total = 0;
    $unread = 0;
    $send = 0;
    $delete = 0;

    switch($type) {
        case "total":
        case "unread":
            bbs_getmailnum($user_id, $total, $unread, 0, 0);
            $ret["total"] = $total;
            $ret["unread"] = $unread;
            break;
        case "send":
            $mail_fullpath = bbs_setmailfile($user_id, ".SENT");
            $send = bbs_getmailnum2($mail_fullpath);
            $ret["total"] = $send;
            $ret["send"] = $send;
            break;
        case "delete":
            $mail_fullpath = bbs_setmailfile($user_id, ".DELETED");
            $delete = bbs_getmailnum2($mail_fullpath);
            $ret["total"] = $delete;
            $ret["delete"] = $delete;
            break;
        case "all":
        default:
            bbs_getmailnum($user_id, $total, $unread, 0, 0);
            $mail_fullpath = bbs_setmailfile($user_id, ".SENT");
            $send = bbs_getmailnum2($mail_fullpath);

            $mail_fullpath = bbs_setmailfile($user_id, ".DELETED");
            $delete = bbs_getmailnum2($mail_fullpath);
            $ret["total"] = $total;
            $ret["unread"] = $unread;
            $ret["send"] = $send;
            $ret["delete"] = $delete;
    }

    return $ret;
}

function getMailInfo($user_id, $mail, $type, $mode=0) {
    $ret["file"] = $mail["FILENAME"];
    $ret["time"] = $mail["POSTTIME"];
    $ret["owner"] = $mail["OWNER"];
    $ret["title"] = $mail["TITLE"];
    $ret["mail_id"] = $mail["MAILID"];
    $ret["owner_img"] = get_user_img($mail["OWNER"]);
    $ret["href"] = url_generate(4, array(
        "action" => "one_mail.php",
        "args" => array("type"=>$type, "mailid"=>$ret["mail_id"])
    ));
    // ��ȡ����ժҪ��Ϣ
    $filepath = makeMailPath($user_id, $mail["FILENAME"]);
    $tmp = "";

    $content = file($filepath);
    if ($filepath)
        if ($mode == 0) {
            $tmp = $content[5]."...";
        } else {
            $content = array_slice($content, 5);
            $tmp = implode("<br />", $content);
        }

    // $mode : 0 ��ȡ����ժҪ, 1��ȡ��������
    if ($mode == 0)
        $ret["abstr"] = $tmp;
    else
        $ret["content"] = $tmp;

    return $ret;
}

function getMailByType($user_id, $type, $page, $num) {
    if ($user_id == "guest")
        return false;

    $page = ($page-1)*$num;
    switch ($type) {
        case "unread":
            $page = 0;
        case "total":
            $path = ".DIR";
            break;
        case "delete":
            $path = ".DELETED";
            break;
        case "send":
            $path = ".SENT";
            break;
        default:
            $type = "total";
            $path = ".DIR";
    }

    $mail_fullpath = bbs_setmailfile($user_id, $path);
    $arr = getMailNumByType($user_id, $type);
    $result_total = $arr[$type];
    if ($result_total == 0 or $page >= $result_total)
        return false;

    $mail_total = $arr["total"];    // ��ǰ�ʼ����µ��ʼ�����,���ڼ����ʼ�id
    $mail_count = 0;        // �ѱ������ʼ���
    $result_count = 0;      // ��ǰ�����������ʼ���
    $result_current = 0;    // ��ǰ��ȡ�������������ʼ���,��$result_count > $pageʱ���ۼ�
    $ret = array();
    while($result_current < $num) {
        $all_mails = bbs_getmails($mail_fullpath, $page, $num);
        // ��ȡ����β(-1)���ȡ����(false)ֱ���˳�
        if ($all_mails == -1 or $all_mails == false)
            break;

        foreach ($all_mails as $each) {
            $mail_count++;
            // ͬһ�ʼ������ʼ�id��0����,����ʱ���Ⱥ�˳�������,�����ʼ�idΪ(����-�ѱ�����)
            $each["MAILID"] = $mail_total-$mail_count;

            switch ($type) {
                case "unread":
                    // ���ж�FLAGSΪ���ʼ�ʱ�ۼ�result_count
                    if (preg_match('/^M|N/', $each["FLAGS"])) {
                        $result_count++;
                        if ($result_count > $page) {
                            // �����ȡ��ʼλ��,��ȡ�ʼ�������$ret��
                            $result_current++;
                            $ret[] = getMailInfo($user_id, $each, $type);

                            if ($result_count >= $num)
                                // ����ȡ���㹻�������ʼ���,����whileѭ��
                                break 3;
                        }
                    }
                    break;
                case "total":
                case "send":
                case "delete":
                    $result_current++;
                    $ret[] = getMailInfo($user_id, $each, $type);
                    break;
                default:
                    return false;
            } // end switch
        }
        $page += $num;
    } // end while

    return $ret;
}

function sendmail($userid,$title,$content,$backup,$signature) {
    $returnValue = array();
    $returnValue['error'] = false;
    $returnValue['userId'] = $userid;

    if( strlen($userid) > 12 || strlen($userid) < 1 )
    {
        $returnValue['error'] = true;
        $returnValue['errorMsg'] = "�ռ����ʺų��Ȳ��� [[[ $userid ]]]";
        return $returnValue;
    }
    $userInfo = array();
    $unum = bbs_getuser($userid,$userInfo);
    if($unum == 0){
        $returnValue['error'] = true;
        $returnValue['errorMsg'] = "������ռ����ʺ�";
        return $returnValue;
    }
    $ret = bbs_mail_check_and_update_num();
    if($ret == -2) {
        $returnValue['error'] = true;
        $returnValue['errorMsg'] = "���ݿ����";
        return $returnValue;
    } else if($ret == 1) {
        $returnValue['error'] = true;
        $returnValue['errorMsg'] = "��ͨ�û�ÿ�����ֻ�ܷ���100���ʼ�, ���Ѵﵽ���ޡ�";
        return $returnValue;
    } else if($ret==2) {
        $returnValue['error'] = true;
        $returnValue['errorMsg'] = "����ÿ�����ֻ�ܷ���200���ʼ�, ���Ѵﵽ���ޡ�";
        return $returnValue;
    }


    if ($_COOKIE["mitbbs_big5"] == "true"||$_COOKIE["tw_hk_big5"] == "true") {
        $title =b2g($title);
        $content = b2g($content);
    }

    $ret2=bbs_postmail($userid,
        preg_replace("/\\\(['|\"|\\\])/", "$1", iconv("UTF-8","GB18030",$title)),
        preg_replace("/\\\(['|\"|\\\])/", "$1", iconv("UTF-8","GB18030",$content)),
        $signature,
        $backup);
    $returnValue["backup"] = $backup;
    if ($ret2 != 6) {
        switch ($ret2) {
            case -1:
                $returnValue['error'] = true;
                $returnValue['errorMsg'] = "�޷������ļ�";
                break;
            case -10-1:
                $returnValue['error'] = true;
                $returnValue['errorMsg'] = "�Է���������ʼ�";
                break;
            case -3-1:
                $returnValue['error'] = true;
                $returnValue['errorMsg'] = "�Է�������";
                break;
            case -5-1:
                $returnValue['error'] = true;
                $returnValue['errorMsg'] = "������ӵ�";
                break;
            case -6-1:
                $returnValue['error'] = false;
                $returnValue['errorMsg'] = "���ܱ��浽������";
                break;
            case -2-1:
                $returnValue['error'] = true;
                $returnValue['errorMsg'] = "�Է��Ѱ�����������";
                break;
            default:
                $returnValue['error'] = true;
                $returnValue['errorMsg'] = "δ֪ԭ��";
        }
    }
    return $returnValue;
}

//����ϴ����������˵��ļ�
function checkImageFile($filename) {
    $image_mime_type = array(
        "image/png",
        "image/x-png",
        "image/jpeg",
        "image/pjpeg",
        "image/bmp",
        "image/gif",
        "image/vnd.microsoft.icon",
        "image/tiff",
        "image/svg+xml");

    if (!is_file($filename))
        return false; //�Ҳ����ļ�

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_info = finfo_file($finfo, $filename);

    return in_array($mime_info, $image_mime_type);
}

function getSource($type,$fileName) {
//            echo '$type: '.$type;
    switch($type){
        case 'image/jpeg':
            return imagecreatefromjpeg($fileName);
        case 'image/png':
            return imagecreatefrompng($fileName);
        case 'image/bmp':
            return imagecreatefromwbmp($fileName);
        case 'image/gif':
            return imagecreatefromgif($fileName);
    }
    return false;
}

function constrcutThumbnail($fileName) {
    if(!is_file($fileName))
        return false;
    $return = getimagesize($fileName);
    $width = $return[0];
    $height = $return[1];
    $type = $return["mime"];
    $filepath = $fileName.'_nail';

    // $percent = 1.5;  //ͼƬѹ����

    $target = imagecreatetruecolor("400","400");
    $source = getSource($type,$fileName);
    if ($source == false)
        return false;
    if($width>$height){
        $x = floor(($width-$height)/2);
        $width = $height;
        $y = 0;
    }else{
        $y = floor(($height-$width)/2);
        $height = $width;
        $x = 0;
    }
    imagecopyresampled($target, $source, 0, 0, $x, $y, "400", "400",$width,$height);
    $res = imagejpeg($target,$filepath,50);
    if($res){
        @unlink($fileName);
        @rename($filepath, $fileName);
        return true;
    }else{
        @unlink($fileName);
        return false;
    }
}

//$link = db_connect_web();

// ����ȫ�ֱ���
//$food_class_list = initFoodClassList($link<!--);-->

// mysql_close($link);

?>
