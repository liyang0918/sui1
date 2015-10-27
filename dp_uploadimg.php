<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
$link = db_connect_web();

$curr_url = $_SERVER["REQUEST_URI"];

if (empty($currentuser) or $currentuser["userid"] == "guest") {
    setcookie("before_login", $curr_url);
    header("Location:login.php");
}

$shop_id = $_GET["shop_id"];
$tags = getAllTags($link, $shop_id);
$env_tags = getAllEnvTags($link, $shop_id);

var_dump($tags);
?>
<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width,	min-width:350px,initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>未名空间</title>
        <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/silder.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/reg.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/footer.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/dp_picList.css" type="text/css">
    </head>
<body>
<div class="conter">
    <nav class="navone">
        <div class="navone_menu">
            <h3 class="navone_h3"><img class="navone_forum" src="img/menu.png" alt="menu.png"/>点评</h3>
        </div>
        <img class="navone_space" src="img/space.png" alt="space.png"/>
        <?php if($currentuser["userid"]=="guest")  {?>
            <a class="navone_home" href="login.php">
                <img src="img/home.png" alt="home.png">
            </a>
        <?php }else{ ?>
            <a class="navone_home" href="jiaye.php"><img src="img/home.png" alt="home.png"/></a>
        <?php } ?>
    </nav>
    <ul class="navone_ul">
        <li class="navone_li"><a href="index.php"><img src="img/item.png" alt="item.png"/>论坛</a></li>
        <li class="navone_li"><a href="news.php"><img src="img/item.png" alt="item.png"/>新闻</a></li>
        <li class="navone_li"><a href="club.php"><img src="img/item.png" alt="item.png"/>俱乐部</a></li>
        <li class="navone_li"><a href="immigration.php"><img src="img/item.png" alt="item.png"/>移民专栏</a></li>
        <li class="navone_li"><a href="dianping.php"><img src="img/item.png" alt="item.png"/>点评</a></li>
        <li class="navone_li noborder"><a href="search.php"><img src="img/item.png" alt="item.png"/>搜索</a></li>
    </ul>

    <div class="ds_box border_bottom">
        <a onclick="go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        上传图片
    </div><!--<End ds_box-->

    <div id="dp_chose" class="dp_chosekind">
        <div class="dp_chose_item">
            <a id="kind_dish" href="" onclick="return kind_switch(this);">菜品</a>
            <a id="kind_env" href="" onclick="return kind_switch(this);">环境</a>
        </div>
        <span id="kind_type" hidden="hidden"></span>
        <span id="current_pic" hidden="hidden"></span>
        <ul id="tag_dish" class="dp_pic_name">
        <?php foreach ($tags as $key1=>$dish) { ?>
            <?php if ($key1 == 0) { ?>
            <li><span class="active"><?php echo $dish["tag_name"]; ?></span></li>
            <?php } else { ?>
            <li><span><?php echo $dish["tag_name"]; ?></span></li>
        <?php }} ?>
        </ul>
        <ul id="tag_env" class="dp_pic_name">
        <?php foreach ($env_tags as $key2=>$env) { ?>
            <?php if ($key2 == 0) { ?>
            <li><span class="active"><?php echo $env["tag_name"]; ?></span></li>
            <?php } else { ?>
            <li><span><?php echo $env["tag_name"]; ?></span></li>
        <?php }} ?>
        </ul>
        <div class="dp_submit_pic">
            <input type="button" value="确认选择" onclick="return kind_submit();">
        </div>
    </div>

    <div id="dp_upload">
    <form id="form_image" method="post" action="dp_uploadimg_server.php" target="uploadImg" class="dp_picList_form" enctype="multipart/form-data">
        <input name="img_count" id="img_count" type="hidden" value="0">
        <ul class="dp_picList">
            <li>
                <img src="img/JIAHAO.png" alt="JIAHAO.png"/>
                <input name="image_file" type="file" onchange="image_submit();">
            </li>
        </ul>
    </form>

    <form method="post" class="dp_picList_form">
        <ul id="dp_picList" class="dp_picList">
        </ul>
        <div class="dp_submit_pic"><input type="button" value="提交" onclick="return "/></div>
    </form>
    </div>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.nivo.slider.js" charset="utf-8"></script>
    <script type="text/javascript" src="js/js.js"></script>
    <script type="text/javascript" src="js/slide.js"></script>
    <script type="text/javascript" src="js/funs.js"></script>
    <script type="text/javascript">
        // 创建一个隐藏的iframe，当表单提交时子页面跳转
        var iframe = document.createElement("iframe");
        iframe.setAttribute("name", "uploadImg");
        iframe.setAttribute("id", "iframe1");
        iframe.setAttribute("style", "display: none");
        // 判断子页面是否加载完毕
        if (iframe.attachEvent){
            iframe.attachEvent("onload", function(){
                addimg();
            });
        } else {
            iframe.onload = function(){
                addimg();
            };
        }
        document.body.appendChild(iframe);

        function addimg() {
            var iframe1 = document.getElementById("iframe1");
            var getobj = iframe1.contentWindow.document.getElementById("file_path");
            if (getobj != undefined) {
                var file_path = getobj.innerHTML;
                if (file_path == "NULL") {
                    Alert("请选择图片", 1);
                } else if (file_path.indexOf("ERROR:") == 0) {
                    Alert(file_path.substr("ERROR:".length), 1);
                } else {
                    // 图片计数加1
                    var img_count = document.getElementById("img_count");
                    var count = parseInt(img_count.value);
                    count++;
                    img_count.value = count;

                    // 创建一个新的图片预览图
                    var dp_picList = document.getElementById("dp_picList");
                    var one_pic = document.createElement("li");
                    var tag1 = document.createElement("div");
                    var tag2_img = document.createElement("img");
                    var tag2_a = document.createElement("a");
                    var tag2_span = document.createElement("span");
                    var tag3_p = document.createElement("p");

                    tag2_img.id = "pic_"+count;
                    tag2_img.src = file_path;
                    tag2_span.id = "pic_kind_"+count;
                    tag2_span.innerHTML = "all";
                    tag3_p.id = "pic_type_"+count;
                    tag3_p.innerHTML = "选择图片类型";
                    tag3_p.setAttribute("onclick", "return chosekind(this)");

                    tag2_a.appendChild(tag3_p);
                    tag1.appendChild(tag2_img);
                    tag1.appendChild(tag2_a);
                    tag1.appendChild(tag2_span);
                    one_pic.appendChild(tag1);
                    dp_picList.appendChild(one_pic);
                }
            }
        }

        function image_submit() {
            document.getElementById("form_image").submit();
            return false;
        }

        function chosekind(obj) {
            var arr = obj.id.split("_");

            $('#current_pic').html(arr[2]);
            $('#dp_chose').show();
            $('#dp_upload').hide();
            $('#kind_dish').addClass("active");
            $('#kind_env').removeClass("active");
            $('#kind_type').html("dish");
            $('#tag_dish').show();
            $('#tag_env').hide();
        }

        function kind_switch(obj) {
            if (obj.id == "kind_dish") {
                $('#kind_env').removeClass("active");
                $('#kind_dish').addClass("active");
                $('#kind_type').html("dish");
                $('#tag_dish').show();
                $('#tag_env').hide();
            } else {
                $('#kind_env').addClass("active");
                $('#kind_dish').removeClass("active");
                $('#kind_type').html("env");
                $('#tag_dish').hide();
                $('#tag_env').show();
            }

            return false;
        }

        function kind_submit() {
            $('#dp_chose').hide();
            $('#dp_upload').show();
            // 获取当前图片的序号
            var pic_num = $('#current_pic').html();
            // 设置当前图片的 kind_type
            var kind_type = $('#kind_type').html();
            $('#pic_kind_'+pic_num).html(kind_type);
            var pic_type = "";
            if (kind_type == "dish") {
                var active = $('#tag_dish>li').find('.active').html();
                if (active != null) {
                    $('#pic_kind_'+pic_num).html(kind_type);
                    $('#pic_type_'+pic_num).html(active);
                }
            } else {
                var active = $('#tag_env>li').find('.active').html();
                if (active != null) {
                    $('#pic_kind_'+pic_num).html(kind_type);
                    $('#pic_type_'+pic_num).html(active);
                }
            }
        }

        $(document).ready(function () {
            document.getElementById("img_count").value = 0;
            $('#img_count').val(0);
            $('#dp_chose').hide();
            $('#dp_upload').show();
        })

    </script>
<?php
include_once("foot.php");
mysql_close($link);
?>