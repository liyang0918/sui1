<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
$link = db_connect_web();
//data part
$class = $_GET["class"];
$url_page = url_generate(1, array("type"=>$_COOKIE["app_type"], "class"=>$class))."&page=";

function getBoardsBySection($class)
{
    global $link;
    global $currentuser;

    $china_flag = is_china();
    $section_num = $class;

    if($china_flag == 1){
        $limitboards = getlimitboards();
    }
    $boards_original = bbs_getboards($section_num, 0, 0);
    $boards = $boards_original;
    list ($board_list, $rows) = boards_dir_sort($boards);

    $fav_arr = array();
    if ($currentuser["userid"] != "guest") {
        $fav_arr = get_fav_arr($currentuser["num_id"],$link);
    }

    /* boards_rank[0]���board��boards_rank[1]���board_group,��groupFlag���� */
    $boards_rank[0] = array();
    $boards_rank[1] = array();
    foreach ($board_list as $i) {
        if($china_flag == 1 and in_array($boards["NAME"][$i],$limitboards))
                continue;

        $tmp["EnglishName"] = $boards["NAME"][$i];
        $tmp["ChineseName"] = $boards["DESC"][$i];
        $tmp["zapped"]= $boards["ZAPPED"][$i];
        if ($boards["FLAG"][$i] & BBS_BOARD_GROUP) {
            $tmp["groupFlag"]= 1;
            $boards_original = bbs_getboards($boards["BID"][$i], $boards["BID"][$i],0);
            $childboardnum = count($boards_original["NAME"]);
            $tmp["childBoardNum"] = "$childboardnum";
        }
        else
            $tmp["groupFlag"]= 0;

        $tmp["BID"] = $boards["BID"][$i];
        $tmp["boardID"] = $boards["BOARD_ID"][$i];
        $tmp["fav"] = "0";
        $tmp["fav_id"] = "0";
        if ($currentuser["userid"] != "guest")
            if(($ret_id = check_key($fav_arr,$tmp["EnglishName"]))!=false){
                $tmp["fav"] = "1";
                $tmp["fav_id"] = $ret_id;
            }
        $brdarr = array();
        $brdnum = bbs_getboard($tmp["EnglishName"], $brdarr);
        $tmp["boardBM"] = explode(trim(" ", $tmp["BM"]));
        $tmp["boardimg"] = getBoardImg($tmp["EnglishName"]);
        $tmp["href"] = url_generate(2, array("board"=>$tmp["EnglishName"]));
        $tmp["online"] = $brdarr["CURRENTUSERS"];
        $tmp["boardtotalarticle"] = getBoardGroupNum($tmp["boardID"], $link);

        $boards_rank[$tmp["groupFlag"]][] = $tmp;
    }

    return $boards_rank;
}

$boards = getBoardsBySection($class);

$page=intval($_GET["page"]);
if(empty($page)){
    $page=1;
}

/* ÿҳ������ */
$board_num = 10;
/* ��ʼҳ */
$start_num = $board_num*($page-1)+1;
/* ��������,Ŀǰ������board_group */
$totalboard = count($boards[0]);
?>

    <div class="ds_box border_bottom">
        <a href="forum_discuss.html"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php echo getClassName($class); ?>
    </div><!--------End ds_box-->
    <div class="hot_li">
        <ul class="class_list_wrap border_bottom block">
<?php
for ($i = $start_num; $i < $start_num+9 and $i < $totalboard-1; $i++) {
    $data = $boards[0][$i];
    echo '<li class="content_list_wrap padding10 border_bottom padding-bottom board_link">';
    echo '<a href="'.$data["href"].'">';
    echo '<img class="hot_li_img" src="'.$data["boardimg"].'" alt="boardimg"/>';
    echo '<div class="hot_content">';
    echo '<h3 class="hot_name">'.$data["ChineseName"].'</h3>';
    echo '<p class="hot_des">'.$data["EnglishName"].'</p>';
    echo '<p class="hot_count"><span class="hot_left">��ǰ���ߣ�'.$data["online"].'��</span><span class="hot_right">����������'.$data["boardtotalarticle"].'</span></p>';
    echo '</div></a></li>';
}

if ($i < $totalboard) {
    $data = $boards[0][$i];
    echo '<li class="content_list_wrap padding10  padding-bottom board_link">';
    echo '<a href="'.url_generate(2, array("board"=>$data["EnglishName"])).'">';
    echo '<img class="hot_li_img" src="'.$data["boardimg"].'" alt="boardimg"/>';
    echo '<div class="hot_content">';
    echo '<h3 class="hot_name">'.$data["ChineseName"].'</h3>';
    echo '<p class="hot_des">'.$data["EnglishName"].'</p>';
    echo '<p class="hot_count"><span class="hot_left">��ǰ���ߣ�'.$data["online"].'��</span><span class="hot_right">����������'.$data["boardtotalarticle"].'</span></p>';
    echo '</div></a></li>';
}

?>
        </ul>
    </div>
<?php
    // ���·�ҳ��ʾ
    echo page_partition($totalboard, $page, $board_num);
?>

<!--    <script type="text/javascript" src="js/jquery.js"></script>-->
<!--    <script type="text/javascript" src="js/js.js"></script>-->
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

<?php
mysql_close($link);
include_once("foot.php");
?>