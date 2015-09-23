<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
include_once("head.php");
//data part
$board_name = $_GET["board"];
$group_id = $_GET["group"];
$url_page = url_generate(3, array("type"=>$_COOKIE["app_type"], "board"=>$board_name, "groupid"=>$group_id))."&page=";
$article_type = 1; //3������
$brdarr = array();
$brdnum = bbs_getboard($board_name, $brdarr);

$per_page=10;
$page = intval($_GET["page"]);
if(empty($page)){
    $page = 1;
}
if ($brdnum == 0) {
    if ($num == 0) wap_error_quit("�����ڵİ���");
}
$prt_arr = array();
$conn = db_connect_web();
$sql = "SELECT owner_id,owner,groupid,article_id,boardname,title,type_flag,posttime,total_reply,read_num,filename,attachment FROM dir_article_" . $brdarr["BOARD_ID"] . " ".
    "WHERE groupid=".$group_id." AND article_id=".$group_id;
$ret = mysql_query($sql,$conn);
$row = mysql_fetch_array($ret);
mysql_free_result($ret);
if($row == false){
    mysql_free_result($ret);
    wap_error_quit("��ȡ����ʧ��");
}else{
    $tmp_arr = array();
    $att_arr = array();
    $content_arr = array();
    $title = $row["title"];
    $board_cname = $brdarr["DESC"];
    $reply_num = $row["total_reply"];
    $read_num = $row["read_num"];
    $board_link = "";
    if($page == 1) {
        $tmp_arr["owner"] = $row["owner"];
        $tmp_arr["posttime"] = $row["posttime"];
        $tmp_arr["floor"] = "¥��";
        $tmp_arr["img"] = get_user_img($row["owner"]);
        $tmp_arr["file"] = check_board_filename($board_name, $row["filename"]);
        $content_arr = get_file_content($tmp_arr["file"], $row["attachment"], $board_name, $row["article_id"], $article_type, $att_arr);
        $tmp_arr["content"] = trans_content_html($content_arr[1]);
        $tmp_arr["attach"] = $att_arr;
        $tmp_arr["article_id"]=$row["article_id"];
        $prt_arr[] = $tmp_arr;
    }
}

//page part
$total_row = get_row_count($brdarr["BOARD_ID"],$group_id,$conn);
$total_page = intval($total_row/$per_page)+1;

//end page
$sql = "SELECT owner_id,owner,groupid,article_id,boardname,title,type_flag,posttime,total_reply,read_num,filename,attachment  FROM dir_article_" . $brdarr["BOARD_ID"] . " ".
    "WHERE groupid=".$group_id." AND article_id<>".$group_id;
$order = " ORDER BY article_id";
$limit =
$sql .= $order;
$start = ($page-1)*$per_page;
if($page == 1){
    $per_page--;
    $limit = " limit $start,$per_page";
}else{
    $limit = " limit $start,$per_page";
}
$sql .= $limit;
$ret = mysql_query($sql,$conn);
$floor_cnt = 2;
while ($row = mysql_fetch_array($ret)) {
    //if more than one article
    $content_arr = array();
    $tmp_arr = array();
    $tmp_arr["owner"] = $row["owner"];
    $tmp_arr["img"] = get_user_img($row["owner"]);
    $tmp_arr["posttime"] = $row["posttime"];
    $tmp_arr["floor"] = $floor_cnt."¥";
    $tmp_arr["file"] = check_board_filename($board_name,$row["filename"]);
    $content_arr = get_file_content($tmp_arr["file"],$row["attachment"],$board_name,$row["article_id"],$article_type,$att_arr);
    $tmp_arr["content"] = trans_content_html($content_arr[1]);
    $tmp_arr["attach"] = $att_arr;
    $tmp_arr["article_id"] = $row["article_id"];
    $tmp_arr["re_content"] = get_add_textarea_context($tmp_arr["file"],$tmp_arr["owner"]) ;
    $prt_arr[] = $tmp_arr;
    $floor_cnt++;
}
mysql_free_result($ret);
mysql_close($conn);
$i_cnt = count($prt_arr);
//data end
?>

    <div class="theme_wrap">
        <div class="news_title">
            <p><?php echo $title;?></p>
            <span><?php echo $board_cname;?></span>
            <em><?php echo $reply_num."/".$read_num;?></em>
        </div>
        <ul class="news_conter">
            <?php for($i_loop=0;$i_loop<$i_cnt;$i_loop++) {
            ?>
            <li  class="news_li" name="abc">
                <div class="theme_up">
                    <img class="theme_small" src="<?php echo $prt_arr[$i_loop]["img"];?>" alt="theme_pic"/>
                    <div class="theme_right fc_span">
                        <h4><?php echo $prt_arr[$i_loop]["owner"];?></h4>
                        <span class="theme_time"><?php echo $prt_arr[$i_loop]["posttime"];?></span>
                    </div>
                </div>
                <span class="news_position news_host"><?php echo $prt_arr[$i_loop]["floor"];?></span>
                <p id="re_content_<?php echo $prt_arr[$i_loop]["article_id"];?>" hidden="hidden"><?php echo $prt_arr[$i_loop]["re_content"];?></p>
                <p id="content_<?php echo $prt_arr[$i_loop]["article_id"];?>"class="theme_middle black_font"><?php echo $prt_arr[$i_loop]["content"];?></p>
                <div id="re_<?php echo $prt_arr[$i_loop]["article_id"];?>" class="news_reply">
<!--                    <a type="button" onclick="alert(2124)">�޸�</a>-->
                    <a type="button" name="<?php echo $prt_arr[$i_loop]["article_id"];?>" onclick="reply_show(this)">�ظ�</a>
<!--                    <a class="cancel" href="javascript:;">ɾ��</a>-->
                </div>
            </li>
            <?php }?>
        </ul>
    </div>
<?php
    // ��ҳ��ʾ
    echo page_partition($total_row, $page, $per_page);
?>

<head>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var page = <?php echo $page;?>;

                $("#page_now").css("background-color", "blue");
                $("#page_now").removeAttr("href");

            //alert($("#page_part a").size());
            $("#page_part a").click(function(){
                var url_page = "<?php echo $url_page;?>";
                if(this.id == "pre_page")
                    url_page = url_page+(page-1);
                else if(this.id == "sub_page")
                    url_page = url_page+(page+1);
                else{
                    url_page = url_page+$(this).text();
                }
                this.href = url_page;
            });
        });
    </script>
    <script type="text/javascript">
        var text_id = "";
        var btn_id = "";
        function reply_show(obj){
            <?php if($currentuser["userid"] == "guest"){ ?>
                var url_page = "<?php echo $url_page.$page;?>";
            alert(url_page);
                document.cookie = "before_login="+url_page;
                window.location = "login.php";
            <?php } ?>
            if(obj.text == "�ظ�"){
                obj.text = "ȡ��"
            $("#"+text_id).remove();
            $("#"+btn_id).remove();
            var re_id = obj.name;
            text_id = "text_"+obj.name;
            btn_id = "btn_"+obj.name;
            var board_name = '<?php echo $board_name;?>';
            var title = '<?php echo $title;?>';
            var re_li = $("#re_"+re_id);
            var re_con = $("#re_content_"+obj.name).text();
//            var atta_str='<tr> <td align="right">������</td> ' +
//                '<td colspan="2"><span id="pro_span"> ' +
//                ' <a href="javascript:void(0);" class="news" onclick="document.getElementById(\'pic_span\').style.display=\'block\';document.getElementById(\'pro_span\').innerHTML=\'\';">�����Ӹ���</a></span> ' +
//                ' <span style="display:none" id="pic_span"><input name="attachname" size="85" maxlength="100"  value="" type="text">' +
//                ' <a href="#" onclick="return GoAttachWindow()" class="news">��������</a></span>' +
//                '</td></tr>';
            var atta_str='<input name="attachfile[]" capture="camera" accept="image/*" type="file" style="margin-left: 10px;width: 200px" multiple="multiple">';
            var rep_body=atta_str+     '<tr><td><textarea id='+text_id+'>'+re_con+'</textarea><button id='+btn_id+' onclick="post_article('+'\''+board_name+'\',\''+title+'\','+obj.name+')">����</button></td></tr>';
                re_li.after(rep_body);
            }else if(obj.text == "ȡ��"){
                obj.text = "�ظ�";
                $("#"+text_id).remove();
                $("#"+btn_id).remove();
            }
        }
        function GoAttachWindow(){

            var hWnd = window.open("bbsupload.php","_blank","width=600,height=300,scrollbars=yes");

            if ((document.window != null) && (!hWnd.opener))

                hWnd.opener = document.window;

            hWnd.focus();

            return false;

        }
    </script>
</head>
<?php
include_once("foot.php");
?>
