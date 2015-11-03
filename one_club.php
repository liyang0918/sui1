<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
//data part
$link = db_connect_web();

$user_id = $currentuser["userid"];
$user_num_id = $currentuser["num_id"];

$club_name=$_GET["club"];
$url_page = url_generate(2, array("type"=>$_COOKIE["app_type"], "club"=>$club_name))."&page=";
$clubarr = array();
$club_id = bbs_getclub($club_name, $clubarr);
$member_type = clubCheckMember($club_id, $user_num_id, $link);
//show_result($clubarr);

$page = intval($_GET["page"]);
if(empty($page)){
    $page=1;
}
/* 每页文章数 */
$article_num = 10;
/* 起始页 */
$start_num = $article_num*($page-1)+1;

if ($club_id == 0) {
    wap_error_quit("错误的俱乐部参数!");
}

/* 检查俱乐部的审批状态 */
if ($clubarr["CLUB_APPROVAL_STATE"] > 1) {
    wap_error_alert_quit("此俱乐部尚未通过审批,暂时不能访问", array("db_link"=>$link));
}

/* 检查访问俱乐部的权限 */
if ($clubarr["CLUB_TYPE"] == 0) {
    if ($user_id == "guest") {
        wap_error_alert_quit("本俱乐部为私密俱乐部,请先登录!", array("db_link"=>$link));
    }

    if ($member_type != 2) {
        wap_error_alert_quit("您不是本俱乐部正式成员,无权访问本俱乐部", array("db_link"=>$link));
    }
}

/* 文章总数 */
$totalarticle = bbs_countarticles($club_name, $dir_modes["ORIGIN"], 1);
$articles = bbs_getarticles($club_name, $start_num, $article_num, $dir_modes["ORIGIN"], 1);

function getClueArticles($link) {
    global $articles, $clubarr;

    $sql_table_id = intval($clubarr["CLUB_ID"])%256;
    if ($sql_table_id == 0)
        $sql_table_id = 256;

    $sql_pub = "select owner,posttime as posttime,title,read_num,total_reply from club_dir_article_".$sql_table_id." where groupid=";

    // ret[1] 存放置顶文章，ret[0]存放普通文章
    $ret[0] = array();
    $ret[1] = array();
    foreach ($articles as $article) {
        $href = url_generate(3, array("type"=>$_COOKIE["app_type"], "club"=>$clubarr["CLUB_NAME"], "groupid"=>$article["GROUPID"]));
        if (!strncasecmp($article["FLAGS"], "d", 1)) {
            $ret[1][] = array("href" => $href."&dingflag=1", "title" => $article["TITLE"]);
        } else {
            $sql = $sql_pub.$article["GROUPID"];
            $result = mysql_query($sql, $link);
            if ($result) {
                $row = mysql_fetch_array($result);
//                $row["title"] = htmlentities($row["title"]);
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

    return $ret;
}

$ret = getClueArticles($link);

?>

    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php echo $clubarr["CLUB_CNAME"]; ?>
        <a href="" onclick="return jump_to_write_article();" class="span_r">发文</a>
    </div>
    <div class="theme_wrap">
<?php
    // 显示置顶文章
    if (!empty($ret[1])) {
        echo '<div class="theme_top">';
        foreach ($ret[1] as $each) {
?>
            <a href="<?php echo $each['href']; ?>" onclick="add_read_num(this)"> <p class="theme_p"><span class="theme_red">置顶</span><?php echo $each["title"]; ?></p></a>
<?php
        }
        echo '</div>';
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
                            <span><?php echo date("Y-m-d", strtotime($each["posttime"])); ?>&nbsp;&nbsp;</span><span class="theme_time"><?php echo date("H:i", strtotime($each["posttime"])); ?></span>
                        </div>
                    </div>
                    <p class="theme_middle"><?php echo $each["title"]; ?></p>
                    <div class="theme_bottom">
                        <p class="p_r"><img src="img/email.png" alt="email.png"/><span><?php echo $each["total_reply"]; ?></span></p>
                        <p class="p_l"><img src="img/eye.png" alt="eye.png"/><span><?php echo $each["read_num"]; ?></span></p>
                    </div>
                </a>
            </li>
<?php
        }
    }
?>
    </div>
<?php
    // 文章分页显示
    echo page_partition($totalarticle, $page, $article_num);
?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        function jump_to_write_article() {
            var user_id = "<?php echo $user_id; ?>";
            if (user_id == "guest") {
                window.location.href = "login.php";
                return false;
            }

            var flag = check_user_perm('<?php echo $member_type; ?>', '<?php echo $clubarr["CLUB_TYPE"]; ?>');
            if (flag) {
                window.location.href = "writearticle.php?club=<?php echo $clubarr["CLUB_NAME"]; ?>";
                return false;
            } else {
                return false;
            }
        }


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