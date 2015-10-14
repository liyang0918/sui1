<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."func.php");
include_once("head.php");
$link = db_connect_web();

//data part
$type = $_GET["type"];
$user_id = $currentuser["userid"];
$url_page = url_generate(4, array(
        "action" => "one_mail_list.php",
        "args" => array("type"=>$type)
    ))."&page=";

$per_page=10;
$page = intval($_GET["page"]);
if(empty($page)){
    $page = 1;
}


$total_row = 0;
if ($type == "unread")
    $total_row = getMailNumByType($user_id, "unread")["unread"];
elseif ($type == "total")
    $total_row = getMailNumByType($user_id, "total")["total"];
elseif ($type == "delete")
    $total_row = getMailNumByType($user_id, "delete")["delete"];
else
    return false;

$t_data = getMailByType($user_id, $type, $page, $per_page);
?>
    <div class="ds_box border_bottom">
        <a href="mymails.php"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php
        if ($type == "unread")
            echo "新邮件";
        elseif ($type == "total")
            echo "收邮件";
        elseif ($type == "delete") {
            echo "垃圾箱";
            echo '<span onclick="_alertClearTrash();" class="trash_clear">清空</span>';
        } else
            echo "邮件列表"
        ?>
    </div>
    <ul class="mr_group">
    <?php if (empty($t_data)) {
        echo "<h2><center><font color=#ff0000>本邮箱中目前没有邮件</font><center></h2>";
    }

    foreach ($t_data as $each) { ?>
        <li class="mr_list">
            <a class="jy_email_content" href="<?php echo $each["href"]; ?>">
                <div class="mr_list_div">
                    <img class="mr_list_img" src="<?php echo $each["owner_img"]; ?>" alt="pic"/>
                    <div>
                        <span><?php echo $each["owner"]; ?></span>
                        <em><?php echo $each["title"]; ?></em>
                        <p class="mr_p_bg ellipsis h20" >
                            <img src="img/mr_btn_arrow.png" alt="mr_btn_arrow.png"/>
                            <?php echo $each["abstr"]; ?>
                        </p>
                        <p class="mr_p_bottom">
                            <span class="mr_span_r"><?php echo date("Y-m-d", $each["time"]); ?></span>
                        </p>
                    </div>
                </div>
            </a>
        </li>
<?php
    }
    // 分页显示
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
</head>
<?php
include_once("foot.php");
?>
