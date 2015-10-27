<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
//data part
$board_name=$_GET["board"];
$url_page = url_generate(2, array("type"=>$_COOKIE["app_type"], "board"=>$board_name))."&page=";
$brdarr = array();
$brdnum = bbs_getboard($board_name, $brdarr);

var_dump($brdarr);

$page=intval($_GET["page"]);
if(empty($page)){
    $page=1;
}
if ($brdnum == 0) {
    if ($num == 0) wap_error_quit("不存在的版面");
}

/* 每页文章数 */
$article_num = 20;
/* 起始页 */
$start_num = $article_num*($page-1)+1;
/* 文章总数 */
$totalarticle = bbs_countarticles($brdnum, $dir_modes["ORIGIN"]);
$articles = bbs_getarticles($brdarr["NAME"], $start_num, $article_num, $dir_modes["ORIGIN"]);

function getBoardArticles() {
    global $articles, $brdarr;
    $link = db_connect_web();
    $sql_pub = "select owner,posttime,title,read_num,total_reply as reply_num from dir_article_".$brdarr["BOARD_ID"]." where groupid=";

    // 置顶文章标志
    // ret[1] 存放置顶文章，ret[0]存放普通文章
    $ret[0] = array();
    $ret[1] = array();
    foreach ($articles as $article) {
        $href = url_generate(3, array("type"=>$_COOKIE["app_type"], "board"=>$brdarr["NAME"], "groupid"=>$article["GROUPID"]));
        if (!strncasecmp($article["FLAGS"], "d", 1)) {
            $ret[1][] = array("href" => $href, "title" => $article["TITLE"]);
        } else {
            $sql = $sql_pub.$article["GROUPID"];
            $result = mysql_query($sql, $link);
            if ($result) {
                $row = mysql_fetch_array($result);
                $tmp = iconv("UTF-8", "GBK//IGNORE", $row["title"]);
                if ($tmp)
                    $row["title"] = $tmp;
                $row["href"] = $href;
                $row["img"] = get_user_img($row["owner"]);
                $ret[0][] = $row;
            }
            mysql_free_result($result);
        }
    }

    mysql_close($link);
    return $ret;
}

$ret = getBoardArticles();

?>

    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php echo $brdarr["DESC"]; ?>
        <a href="writearticle.php?board=<?php echo $brdarr["NAME"]; ?>" class="span_r" >发文</a>
    </div>
    <div class="theme_wrap">
<?php
    // 显示置顶文章
    if (!empty($ret[1])) {
        echo '<div class="theme_top">';
        foreach ($ret[1] as $each) {
?>
            <a href="<?php echo $each['href']; ?>" onclick="add_read_num(this)"> <p class="theme_p"><span class="theme_red">置顶</span><?php echo $each["title"]; ?></p></a>
<?php } ?>
      </div>
<?php
    }
    // 显示普通文章
    if (!empty($ret[0])) {
        echo '<ul class="theme_conter">';
        foreach ($ret[0] as $each) {
?>
            <li class="theme_li">
                <a class="theme_a" href="<?php echo $each["href"]; ?>" onclick="add_read_num(this)">
                    <div class="theme_up">
                        <img class="theme_small" src="<?php echo $each["img"]; ?>" alt="pic"/>
                        <div class="theme_right">
                            <h4><?php echo $each["owner"]; ?></h4>
                            <span><?php echo date("H:i", strtotime($each["posttime"])); ?>&nbsp;</span><span class="theme_time"><?php echo date("Y-m-d", strtotime($each["posttime"])); ?></span>
                        </div>
                    </div>
                    <p class="theme_middle"><?php echo $each["title"]; ?></p>
                    <div class="theme_bottom">
                        <p class="p_r"><img src="img/email.png" alt="email.png"/><span><?php echo $each["reply_num"]; ?></span></p>
                        <p class="p_l"><img src="img/eye.png" alt="eye.png"/><span><?php echo $each["read_num"]; ?></span></p>
                    </div>
                </a>
            </li>
<?php
        }
        echo '</div>';
    }
?>

<?php
    // 文章分页显示
    echo page_partition($totalarticle, $page, $article_num);
?>

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

<?php
include_once("foot.php");
?>