<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
if(empty($_COOKIE["app_type"]))
    setcookie("app_type", "index");
if(empty($_COOKIE["app_show"]))
    setcookie("app_show", iconv("GBK","UTF-8//IGNORE","论坛"));
if(empty($_COOKIE["sec_category"]))
    setcookie("sec_category", "top");

if(!is_own_label($_COOKIE["sec_category"], "index")) {
    setcookie("app_type", "index");
    setcookie("app_show", iconv("GBK", "UTF-8//IGNORE", "论坛"));
    setcookie("sec_category", "top");
    // 切换栏目需要重新加载
    echo '<script language="javascript">location.href=location.href</script>';
}

include_once("head.php");
include_once("sec_index.php")
?>
<div id="linklist">
</div>
<?php
$articlelist = array();
if ($_COOKIE["sec_category"] == "top" or empty($_COOKIE["sec_category"])) {
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

    $link = db_connect_web();
    $articlelist = getArticleLunbo($link);
    $img_arr = array();
    foreach ($articlelist as $row) {
        $tmp=array();
        $board_id = $row["board_id"];
        $article_id = $row["article_id"];
        $local_img = $row["new_url"];
//boardname
        $sql_str = "select board_desc,boardname from board where board_id=" . $board_id;
        $result2 = mysql_query($sql_str, $link);
        if ($row_board = mysql_fetch_array($result2)) {
            $board_name = $row_board["boardname"];
        }
        mysql_free_result($result2);

        $img = BBS_HOME.'/pic_home/boards/'.$board_name."/".$local_img;
        if (is_file($img)) {
            $tmp["imgURL"] = "http://".$_SERVER["SERVER_NAME"]."/boardimg/".$board_name."/".$local_img;
        }
//article detail
        $sql_str1 = "select title,groupid,owner,read_num,reply_num,total_reply,board_id,o_bid,o_groupid from dir_article_" . $board_id .
            " where article_id=" . $article_id;
        $result3 = mysql_query($sql_str1, $link);
        if ($row1 = mysql_fetch_array($result3)) {
            $tmp["title"] = $row1["title"];
            $tmp["groupid"] = $row1["groupid"];
            $tmp["article_id"] = $article_id;
            $tmp["type"] = "BBS";
        }
        mysql_free_result($result3);
        $img_arr[] = $tmp;
    }
    $type = "index";
// top image start
    $str_img = '<div id="carouselfigure" class="main_visual">';
    $img_url = '<div class="main_image"><ul>';
    foreach ($img_arr as $i=>$imgDate) {

        $php_page = url_generate(3, array("board"=>$board_name, "groupid"=>$imgDate["groupid"]));
        $img_arr[$i]["php_page"] = $php_page;
        $img_url .=
            '<li>
                <img src="'.$imgDate["imgURL"].'"></img>
            </a>
        </li>';
    }
    $img_url .= '</ul></div>';
    $str_img .= $img_url;
    $str_img .= '<p class="text"><a id="text" onclick="add_read_num(this)"></a></p>';
    $str_img .= "</div>";
    echo $str_img;
    mysql_close($link);
//top image end
}
?>

<div id="detail">
</div>
<div id="pagebox">
</div>
<?php
if ($_COOKIE["sec_category"] == "top" or empty($_COOKIE["sec_category"])) { ?>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.event.drag-1.5.min.js"></script>
<script type="text/javascript" src="js/jquery.touchSlider.js"></script>

<script type="text/javascript">
    var titlelist = Array();
    var pagelist = Array();
    var i = 0;
    <?php foreach($img_arr as $each) { ?>
        titlelist[i] = "<?php echo $each["title"]; ?>";
        pagelist[i] = "<?php echo $each["php_page"]; ?>";
        i++;
    <?php } ?>
    setImageEffect(titlelist, pagelist);
</script>
<?php
    }
include_once("foot.php");
?>
