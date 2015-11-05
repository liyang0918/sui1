<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");

$curr_url = $_SERVER["REQUEST_URI"]."?".$_SERVER["QUERY_STRING"];

if (empty($currentuser) or $currentuser["userid"] == "guest") {
    setcookie("before_login", $curr_url);
    header("Location:login.php");
}

// 文章类型：1论坛文章 2俱乐部文章
$type = 0;
if (isset($_GET["board"])) {
    $type = 1;
    $name = $_GET["board"];
} elseif (isset($_GET["club"])) {
    $type = 2;
    $name = $_GET["club"];
}

?>
    <div class="ds_box border_bottom">
        <a onclick="return go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        发文
    </div><!--<End ds_box-->
    <form id="form_article" class="newreply_conter input_padding1">
        <h4>标题:</h4>
        <input class="newreply_txt" type="text"/>
        <h4>正文:</h4>
        <textarea cols="30" rows="10"></textarea>
    </form>
    <form id="form_image" enctype="multipart/form-data" class="newreply_conter input_padding2" method="post" action="file_upload.php" target="upload_file">
        <p id="image_area">
            <span class="add_img margin_r"></span>
            <input name="image_file" type="file" onchange="image_submit();"/>
            <input name="img_count" id="img_count" type="hidden" value="0"/>
        </p>
    </form>
    <p class="newreply_conter input_padding2"><input class="newreply_sub" type="button" value="发表" onclick="return article_submit();"/></p>
        <!-- /# -->
    <script type="text/javascript">
        // 创建一个隐藏的iframe，当表单提交时子页面跳转
        var iframe = document.createElement("iframe");
        iframe.setAttribute("name", "upload_file");
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
                    var image_area = document.getElementById("image_area");
                    var img_count = document.getElementById("img_count");
                    var img = document.createElement("img");
                    var count = parseInt(img_count.value);
                    count++;
                    img.setAttribute("id", "img_"+count);
                    img.setAttribute("class", "newreply_img_r");
                    img.setAttribute("src", file_path);
                    img.setAttribute("alt", "img");
                    img.setAttribute("class", "newreply_img_r");
                    image_area.appendChild(img);
                    img_count.value = count;
                }
            }
        }

        function image_submit() {
            document.getElementById("form_image").submit();
            return false;
        }

        function article_submit() {
            var curr_url = "<?php echo $curr_url; ?>";
            var currentuser = "<?php echo $currentuser["userid"]; ?>";
            var name = "<?php echo $name; ?>";
            var article_type = "<?php echo $type; ?>";
            if (currentuser == "guest") {
                document.cookie = "before_login="+curr_url;
                window.location = "login.php";
                return false;
            }

            if (article_type != "1" && article_type != "2") {
                Alert("未知的版名或俱乐部名!", 1);
                return false;
            }

            // 获取表单内容，使用ajax post文章
            var form_article = document.getElementById("form_article");
            var input = form_article.getElementsByTagName("input");
            var textarea = form_article.getElementsByTagName("textarea");
            if (input[0].value.length == 0) {
                Alert("请填写文章标题!"+article_type, 1);
                input[0].focus();
                return false;
            }
            if (textarea[0].value.length == 0) {
                Alert("文章内容太少!", 1);
                textarea[0].focus();
                return false;
            }
            var url = "/mobile/forum/request/post_article.php";
            var para = "";

            var jumpto = "";
            if (article_type == "1") {
                para = "board="+name+"&title="+input[0].value+"&reid=0&content="+textarea[0].value;
                var jumpto = "one_board.php?board="+name;
            } else if (article_type == "2") {
                para = "club="+name+"&title="+input[0].value+"&reid=0&content="+textarea[0].value;
                var jumpto = "one_club.php?club="+name;
            }

            send_article(url, para, jumpto, false);
//            document.location = href;
            return false;
        }
    </script>
<?php
include_once("foot.php");
?>