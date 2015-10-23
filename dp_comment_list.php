<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."func.php");
include_once("head.php");
$link = db_connect_web();

$shop_id = $_GET["shop_id"];
//data part
$url_page = url_generate(4, array(
        "action" => "dp_comment_list.php",
        "args" => array("shop_id"=>$shop_id)
    ))."&page=";

$per_page = 20;
$page = intval($_GET["page"]);
if(empty($page)){
    $page = 1;
}

$prt_arr = array();

//page part
$total_row = getShopCommentTotal($link, $shop_id);
//end page
$t_data = getShopComment($link, $shop_id, $page, $per_page);
?>
<div class="ds_box border_bottom">
    <a onclick="go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    全部点评
    <span>(<?php echo $total_row; ?>)</span>
</div><!--------End ds_box-->
<div class="ds_group nomargin_top">
    <ul class="member_group">
        <?php foreach ($t_data as $each) { ?>
        <li class="member_list border_bottom">
            <h4><?php echo $each["user_name"]; ?></h4>
            <p class="member_pt">
            <?php for ($i = 0; $i < 5; $i++) { ?>
                <?php if ($i < $each["avg_score"]-1) {?>
                <img src="img/redstar.png" alt="redstar.png"/>
                <?php } else {?>
                <img src="img/graystar.png" alt="redstar.png"/>
            <?php }} ?>
                <span>$<?php echo $each["consume"]; ?>/人</span>
            </p>
            <p class="member_info">
                <?php echo $each["content"]; ?>
            </p>
            <?php if (isset($each["img_list"])) {?>
            <div class="member_img">
                <?php foreach ($each["img_list"] as $img) { ?>
                <img src="<?php echo $img; ?>" alt="img"/>
                <?php } ?>
            </div>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
</div>
<?php
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
