<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."func.php");
include_once("head.php");
//data part
$link = db_connect_web();
$board_name = $_GET["news"];
$group_id = $_GET["group"];
$reply_page = 0; // 0���� 1д����
$textarea = $_GET["textarea"]; // ����ҳ�ı�����
if (isset($_GET["reply"]))
    $reply_page = $_GET["reply"];

$curr_url = url_generate(3, array("news"=>$board_name, "groupid"=>$group_id));
$article_type = 1; //3������
$brdarr = array();
$brdnum = bbs_getboard($board_name, $brdarr);

if ($brdnum == 0) {
    if ($num == 0) wap_error_quit("�����ڵİ���");
}

$fav_flag = 0;
$check_result = check_if_fav($link, 1, $brdarr["BOARD_ID"], $group_id, 0, $brdarr["NAME"], "", "");
if ($check_result && mysql_num_rows($check_result) > 0)
    $fav_flag = 1;

function readArticleContent($link) {
    global $board_name, $group_id, $brdarr, $article_type;
    $sql = "SELECT owner_id,owner,groupid,article_id,boardname,title,type_flag,UNIX_TIMESTAMP(posttime) as posttime,total_reply,read_num,filename,attachment FROM dir_article_" . $brdarr["BOARD_ID"] . " ".
        "WHERE groupid=".$group_id." AND article_id=".$group_id;
    $ret = mysql_query($sql, $link);
    $row = mysql_fetch_array($ret);
    mysql_free_result($ret);
    $t_data = array();
    if($row == false){
        mysql_free_result($ret);
        wap_error_quit("��ȡ����ʧ��");
    }else{
        $att_arr = array();
        $content_arr = array();
        $title = preg_replace( '/\[[A-Z]{4}\]/', "", $row["title"]);
        $tmp = iconv("UTF-8", "GBK//IGNORE", $title);
        if ($tmp)
            $title = $tmp;
        $reply_num = $row["total_reply"];
        $read_num = $row["read_num"];
        $reply_href = url_generate(4, array(
            "action" => "one_reply_list.php",
            "args" => array("board"=>$board_name, "group"=>$group_id)
        ));

        $t_data["title"] = $title;
        $t_data["reply_num"] = $reply_num;
        $t_data["reply_href"] = $reply_href;
        $t_data["owner"] = $row["owner"];
        $t_data["posttime"] = $row["posttime"];
        $t_data["img"] = get_user_img($row["owner"]);
        $t_data["file"] = check_board_filename($board_name, $row["filename"]);
        $content_arr = get_file_content($t_data["file"], $row["attachment"], $board_name, $row["article_id"], $article_type, $att_arr);
        $t_data["content"] = trans_content_html($content_arr[1]);
        $t_data["attach"] = $att_arr;
        $t_data["article_id"]=$row["article_id"];
    }
    return $t_data;
}
//data end

$t_data = readArticleContent($link);
if ($reply_page == 0) {
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="return go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php echo $brdarr["DESC"]; ?>
    </div>
    <div class="news_content_box">
        <h3><?php echo $t_data["title"]; ?></h3>
        <p class="news_p">
            <span class="news_tips"><?php echo $brdarr["DESC"]; ?></span>
            <span class="news_tips"><?php echo strftime("%Y-%m-%d", $t_data["posttime"]); ?></span>
            <a href="<?php echo $t_data["reply_href"]; ?>"><em class="news_img"><?php echo $t_data["reply_num"]; ?>����</em></a>
        </p>
        <p class="news_info">
                <span class="news_text"><?php echo $t_data["content"]; ?></span>
        </p>
    </div><!--------End news_content_box-->
    <footer class="collect_share">
        <a class="news_a" onclick="news_reply()">д����</a>
        <span class="collect">
            <a id="fav_<?php echo $fav_flag; ?>" href="" onclick="return collect_by_type(1, this, '<?php echo $brdarr["BOARD_ID"];?>', '<?php echo $group_id; ?>', '<?php echo $t_data["title"];?>')">
                <?php if ($fav_flag == 1) { ?>
                    <img id="collect_img" src="img/star.png" alt="star.png" hidden="hidden"/>
                    <span id="collect_span" >���ղ�</span>
                <?php } else { ?>
                    <img id="collect_img" src="img/star.png" alt="star.png"/>
                    <span id="collect_span">�ղ�</span>
                <?php } ?>
            </a>
        </span>
    </footer><!--------End collect_share-->
<?php } else if($reply_page == 1) { ?>
    <div class="ds_box border_bottom">
        <a href="" onclick="return go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        ��������
    </div>
    <form class="reply_conter" action="">
        <textarea id="text_<?php echo $group_id; ?>"><?php echo iconv("UTF-8", "GBK//IGNORE", $textarea); ?></textarea>
        <p class="reply_p">
            <input class="reply_send"  type="submit" value="����" onclick="if(false == reply_submit()) return false; else return true;" />
        </p>
    </form><!--End reply_conter-->
<?php } else if ($reply_page == 2) {?>
    <div class="ds_box border_bottom">
        <a href="" onclick="return go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        ���۳ɹ�
    </div>
<?php }?>
<head>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        function news_reply() {
            var board = "<?php echo $board_name; ?>";
            var groupid = "<?php echo $group_id; ?>";
            var curr_url = "<?php echo $curr_url; ?>";
            var currentuser = "<?php echo $currentuser["userid"]; ?>";
            if (currentuser == "guest") {
                document.cookie = "before_login="+curr_url;
                window.location = "login.php";
            }

            curr_url = curr_url + "&reply=1";
            window.location = curr_url;
        }
    </script>
    <script>
        function reply_submit() {
            var board = "<?php echo $board_name; ?>";
            var groupid = "<?php echo $group_id; ?>";
            var curr_url = "<?php echo $curr_url; ?>";
            var title = "<?php echo $t_data['title']; ?>";
            var currentuser = "<?php echo $currentuser["userid"]; ?>";
            if (currentuser == "guest") {
                document.cookie = "before_login="+curr_url;
                window.location = "login.php";
                return false;
            }

            var obj = document.getElementById("text_"+groupid);
            var content = obj.value;
            if (content.replace(/(^\s*)|(\s*$)/g, "").length < 10) {
                alert("�������ݲ�����10����!");
                return false;
            }

            post_article(board, title, groupid, content, 0, curr_url+"&reply=0");
            return false;
        }
    </script>
</head>
<?php
mysql_close($link);
?>
