<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");
$link = db_connect_web();
$shop_id = $_GET["shop_id"];

// page 为当前评论页,num为每页评论条数
$page = 1;
$num = 10;
$total_row = getShopCommentTotal($link, $shop_id);
$shop_info = getShopInfoById($link, $shop_id);
?>
    <div class="ds_box border_bottom">
        <a onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        店铺信息
    </div><!--<End ds_box-->
    <div class="conter2_list nopadding">
        <div class="conter2_list_conter border_bottom ds_div">
            <img class="shop_topimg" src="<?php echo $shop_info["img"]; ?>" alt="topimg"/>
            <div>
                <h4><?php echo $shop_info["cnName"]; ?></h4>
                <p class="conter2_pt">
                <?php for ($i = 0; $i < 5; $i++) { ?>
                    <?php if ($i < $shop_info["avg_score"]-1) { ?>
                    <img src="img/redstar.png" alt="redstar.png"/>
                    <?php } else { ?>
                    <img src="img/graystar.png" alt="redstar.png"/>
                    <?php }} ?>
                    <span>人均：$<?php echo round($shop_info["avg_pay"], 2); ?></span>
                </p>
                <p class="conter2_pd">
                    <span class="margin_r">口味：<?php echo round($shop_info["taste_score"]); ?></span>
                    <span class="margin_r">环境：<?php echo round($shop_info["env_score"]); ?></span>
                    <span class="margin_r">服务：<?php echo round($shop_info["sev_score"]); ?></span>
                </p>
            </div>
        </div>
        <p class="ds_p border_bottom">
            <img src="img/ds1.png" alt="ds1.png"/>
            <span>
                <?php echo $shop_info["location"]; ?>
            </span>
        </p>
        <p class="ds_p border_bottom"><img src="img/ds2.png" alt="ds2.png"/><?php echo $shop_info["contact"]; ?></p>
        <p class="ds_p border_bottom"><img src="img/ds3.png" alt="ds3.png"/><?php echo $shop_info["business_time"]; ?></p>
    </div><!--<End conter2_list-->
    <div class="ds_group">
        <p class="ds_member">
           <strong>会员点评</strong>
            <a href="dp_comment_list.php?shop_id=<?php echo $shop_id; ?>"><em>查看全部</em></a>
        </p>
        <ul class="member_group" id="comment_content">
        </ul>
        <?php
        // 分页显示
        $total_page = intval($total_row/$num)+1;
        $frt_page = 1;
        $end_page = $total_page;
        // 不足一页的隐藏页码
        if ($total_row > $num)
            $page_str = '<div id="page_part" class="pages_box margin-bottom">';
        else
            $page_str = '<div id="page_part" class="pages_box margin-bottom" hidden="hidden">';

        $page_str .= '<a href="" id="pre_page" onclick="return getComment(-1);">上一页</a>';
        $page_str .= "<span id='sep_left'>...</span>";
        $page_str .= '<a id="now_page">'.$page.'</a>';
        $page_str .= "<span id='sep_right'>...</span>";
        $page_str .= '<a href="" id="sub_page" onclick="return getComment(1);">下一页</a>';

        $page_str .= '</div>';
        echo $page_str;
        ?>
    </div>
</div>
<script type="text/javascript">
    getComment(0);

    function show_page (page, total_page) {
        var now_page = document.getElementById("now_page");
        now_page.innerHTML = page;

        var pre_page = document.getElementById("pre_page");
        var sep_left = document.getElementById("sep_left");
        var sep_right = document.getElementById("sep_right");
        var sub_page = document.getElementById("sub_page");
        // 上一页
        if (page == 1)
            pre_page.setAttribute("hidden", "hidden");
        else
            pre_page.removeAttribute("hidden");

        // 左省略号
        if (page < 3)
            sep_left.setAttribute("hidden", "hidden");
        else
            sep_left.removeAttribute("hidden");

        // 右省略号
        if (total_page-page < 2)
            sep_right.setAttribute("hidden", "hidden");
        else
            sep_right.removeAttribute("hidden");

        // 下一页
        if (total_page-page < 1)
            sub_page.setAttribute("hidden", "hidden");
        else
            sub_page.removeAttribute("hidden");



    }

    // orientation: -1 向前(左)一页,1向后(右)一页,0获取当前页
    function getComment(orientation) {
        var shop_id = "<?php echo $shop_id; ?>";

        var total_row = parseInt("<?php echo $total_row;?>");
        var num = parseInt("<?php echo $num;?>");
        var total_page = parseInt(total_row/num)+1;
        var tag = document.getElementById("now_page");
        var page = parseInt(tag.innerHTML);

        switch (orientation) {
            case -1:
                if (page > 1)
                    page--;
                else
                    page = 1;
                break;
            case 0:
                if (page < 1)
                    page = 1;

                if (page > total_page)
                    page = total_page;

                break;
            case 1:
                if (page < total_page)
                    page++;
                else
                    page = total_page;
                break;
            default :
                if (page < 1)
                    page = 1;

                if (page > total_page)
                    page = total_page;

                break;
        }

        var url = "/mobile/forum/request/dp_comment.php";
        var para = "shop_id="+shop_id+"&page="+page+"&num="+num;
        var myAjax = new Ajax.Request(url,
            {
                method: "post",
                parameters: para,
                asynchronous: false,
                onSuccess: function (ret) {
                    var ret_json = eval("(" + ret.responseText + ")");
                    if (ret_json.content != undefined) {
                        var tag = document.getElementById("comment_content");
                        tag.innerHTML = ret_json.content;

                        show_page(page, total_page);
                    } else {
                    }
                },
                onFailure: function (x) {
                }
            }

        );

        return false;
    }

</script>
<br><br><br><br>
<foot class="ds_footer">
    <a class="ds_footer_a" href="write_dianping.php?shop_id=<?php echo $shop_id; ?>">写点评</a>
    <a href="#">发照片</a>
</foot><!--<End ds_footer-->
</body>
</html>
<?php
mysql_close($link);
?>