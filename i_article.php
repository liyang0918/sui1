<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once("func.php");
include_once("head.php");
$link = db_connect_web();
$reqtype = $_GET["reqtype"];
$boardname = $_GET["board"];
$article_id = $_GET["groupid"];
$brdarr = array();
$brdnum = bbs_getboard($boardname, $brdarr);
if ($brdnum == 0) {
    wap_error_quit("指定版面不存在!");
}
$articles = array();
$num = bbs_get_onerecord_from_id($article_id, $dir_modes["NORMAL"], $articles, $brdarr["BOARD_ID"]);
show_result($articles);
if ($num == 0) {
    wap_error_quit("错误的文章号,原文可能已被删除!");
}
$article = $articles[0];

//++++++++++++++++++++++++++++++++++++++++++++++++++
// 修改name的获取方式,从数据库内获取 liyang-20151106
$name = getLawyerName($link, $boardname);
$name_tmp = iconv("UTF-8", "GBK//IGNORE", $author);
if ($name_tmp)
    $name = $name_tmp;
//++++++++++++++++++++++++++++++++++++++++++++++++++

$newType = getImmigrationNewsType($article["TITLE"]);
if (empty($newType))
    $newType = "未知";
$article["TITLE"] = preg_replace('/\[.*\]/', "", $article["TITLE"]);
//+++++++++++++++++++++++++++++++++++++++++++++++++++
// 修改律师专栏文章路径的拼接方式,目录区分大小写,取brdarr["NAME"]
$filepath = BBS_HOME ."/boards/".$brdarr["NAME"]."/".$article["FILENAME"];
$attach_flag = $article["ATTACHPOS"];
$attach_linkstr = "/article2/".$brdarr["NAME"]."/".$article["ARTICLE_ID"];
//+++++++++++++++++++++++++++++++++++++++++++++++++++
$ret_str = read_news_web($filepath, $attach_flag, "", "", $attach_linkstr, 0);
?>
    <div id="linklist"></div>
    <div id="carouselfigure"></div>
    <div class="detail">
        <div class="ds_box border_bottom">
            <a href="" onclick="return go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
            <?php echo $brdarr["DESC"]; ?>
        </div>
    <?php if ($reqtype == "news") { ?>
        <div class="content_up border_bottom">
            <h3 class="content_name"><?php echo $article["TITLE"]; ?></h3>
            <p class="content_detail font12">
                <span><?php echo $newType; ?>新闻</span>
                <em><?php echo strftime("%G-%m-%d", $article["POSTTIME"]); ?></em>
            </p>
        </div>
        <div class="article_box">
            <p class="content_detail">
                <?php echo $ret_str[0]; ?>
            </p>
            <p class="content_detail">
                <?php echo $ret_str[1]; ?>
            </p>
        </div>
    <?php } else if ($reqtype == "visa") {?>
        <div class="content_up border_bottom">
            <h3 class="content_name"><?php echo $article["TITLE"]; ?></h3>
            <p class="content_detail font12">
                <span class="margin_r">作者: <?php echo $article["OWNER"]; ?></span>
                <span>来源: <?php echo $boardname; ?>版</span>
                <em><?php echo strftime("%G-%m-%d", $article["POSTTIME"]); ?></em>
            </p>
        </div>
        <div class="article_box">
            <p class="content_detail">
                <?php echo $ret_str[0];?>
            </p>
            <p class="content_detail">
                <?php echo $ret_str[1]; ?>
            </p>
        </div>
    <?php } else if ($reqtype == "column") { ?>
        <div class="content_up border_bottom">
            <h3 class="content_name"><?php echo $article["TITLE"]; ?></h3>
            <p class="content_detail font12">
                <span class="margin_r"><?php echo $name; ?>律师 </span>
                <span><?php echo strftime("%G-%m-%d", $article["POSTTIME"]); ?></span>
                <em><?php echo $article["READ_NUM"]; ?> 看过</em>
            </p>
        </div>
        <div class="article_box">
            <p class="article"><?php echo $ret_str[0]; echo $ret_str[1]; ?></p>
        </div>
    <?php } ?>
    </div>
    <div class="pagebox"></div>
<?php
@mysql_close($link);
?>
