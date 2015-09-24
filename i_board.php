<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
$link = db_connect_web();

//data part
$board_name=$_GET["board"];
$url_page = url_generate(4, array(
        "action"=>"/mobile/forum/i_board.php",
        "args"=>array("type"=>$_COOKIE["app_type"], "board"=>$board_name)))."&page=";
$brdarr = array();
$brdnum = bbs_getboard($board_name, $brdarr);

$page=intval($_GET["page"]);
if(empty($page)){
    $page=1;
}
if ($brdnum == 0) {
    if ($num == 0) wap_error_quit("不存在的版面");
}

/* 每页文章数 */
$article_num = 10;
/* 起始页 */
$start_num = $article_num*($page-1)+1;
/* 文章总数 */
$totalarticle = bbs_countarticles($brdnum, $dir_modes["ORIGIN"]);
$articles = bbs_getarticles($brdarr["NAME"], $start_num, $article_num, $dir_modes["ORIGIN"]);

function getLawyerArticles($board_name) {
    global $articles;
    $ret = array();
    foreach ($articles as $article) {
        $href = url_generate(4, array(
            "action"=>"/mobile/forum/i_article.php",
            "args"=>array("reqtype"=>"column", "board"=>$board_name, "groupid"=>$article["GROUPID"])
        ));

        $ret[] = array(
            "href" => $href,
            "title" => $article["TITLE"],
            "read_num" => $article["READ_NUM"],
            "posttime" => strftime("%G-%m-%d", $article["POSTTIME"])
        );
    }

    return $ret;


}
$lawyer = getLawyerInfo($link, $board_name);
$ret = getLawyerArticles($board_name);
?>
    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        律师专栏
    </div>
    <ul class="new_list_content_listbox">
        <li class="news_ltems news_list_lione">
            <a href="<?php echo $lawyer["href"]; ?>">
                <img class="column_img" src="<?php echo $lawyer["img"]; ?>" alt="newlist_img"/>
                <div class="lione_r_box">
                    <h3><?php echo $lawyer["name"]; ?></h3>
                    <p class="column_p"><?php echo $lawyer["desc"]; ?></p>
                </div>
                <span class="critize right_b nobg"><img class="column_btn" src="img/btn_right.png" alt="btn_right"/></span>
            </a>
        </li>
    </ul>
    <ul class="hot_recommend margin-bottom">
<?php foreach ($ret as $each) { ?>
    <li class="hot_li hot_list_wrap im_conter_box border_bottom">
        <div class="content_list nopic padding10 ">
            <h4 class="singleline"> <a href="<?php echo $each["href"]; ?>"><?php echo $each["title"]; ?></a></h4>
            <p class="commen_p">
                <span class="im_l noborder pl"><?php echo $each["read_num"]; ?>人看过</span>
                <span class="commen_right "><?php echo $each["posttime"]; ?></span>
            </p>
        </div>
    </li>
<?php }?>
    </ul>
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
mysql_close($link);
?>