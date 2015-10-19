<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once("head.php");

$curr_url = $_SERVER["REQUEST_URI"];
$thumb_dir = "thumb";
if (is_dir($thumb_dir))
    @mkdir($thumb_dir);

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

if (isset($_POST["article_type"]))
    $type = intval($_POST["article_type"]);

if (isset($_POST["t_name"]))
    $name = $_POST["t_name"];

$image_count = 0;
$image_list = array();
if (isset($_POST["image_count"])) {
    $image_count = $_POST["image_count"];
    // 将隐藏表单中保存的图片路径提取到数组中
    // 图片的路径保存的是在服务器上的位置
    $i = 0;
    while ($i < $image_count) {
        $image_list[$i] = $_POST["image_$i"];
        ++$i;
    }
}

$utmpnum = $_COOKIE["UTMPNUM"];
//var_dump($currentuser);
//echo "====>";
//var_dump($_FILES);
//echo "<br />";
//var_dump($_POST);
//echo "<br />";
//foreach ($_SERVER as $key=>$each)
//    echo $key."    =>    ".$each."<br />";
//echo "<br />";

/* 待上传的附件存放在 $attachdir 中,在$attachdir 中有 .index 文件，记录了将被上传的文件信息，post_article 时,会打开 .index 文件读取该信息。
 * 文件中每一行记录一个待上传的附件的信息，信息格式为
 *          附件路径+空格+附件上传时使用的文件名
 * 附件路径为 $tmpfilename, 附件上传时使用的文件名为 $_FILES["image_file"]["file"],即附件原始名称
 */

$title_backup = $_POST["title_backup"];
$content_backup = $_POST["content_backup"];

if (!empty($_FILES["image_file"]["tmp_name"])) {
    // 在这里验证图片,并存放到指定文件夹
    $attachdir=bbs_getattachtmppath($currentuser["userid"] ,$utmpnum);
    @mkdir($attachdir);
    $tmpfilename = tempnam($attachdir, "att");
    if (is_uploaded_file($_FILES["image_file"]["tmp_name"])) {
        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $tmpfilename)) {
            if (checkImageFile($tmpfilename)) {
                $fp = fopen($attachdir."/.index", "a");
                if ($fp == false)
                    $fp = fopen($attachdir."/.index", "w");

                fputs($fp, $tmpfilename." ".$_FILES["image_file"]["name"]."\n");
                @fclose($fp);
                // 如果验证通过且图片成功放入指定文件夹,则image_count++
                $tmparr = array();
                if(preg_match("/.*\/(.*)\/(.*)/", $tmpfilename, $tmparr) == 1) {
                    $file_dir = $thumb_dir."/".$tmparr[1];
                    $file_path = $file_dir."/".$tmparr[2];
                    if ($image_count == 0) {
                        @system("rm -rf ".dirname(__FILE__)."/$file_dir");
                    }
                    @mkdir(dirname(__FILE__)."/$file_dir");
                    @copy($tmpfilename, dirname(__FILE__)."/$file_path");
                    // 创建缩略图
                    constrcutThumbnail(dirname(__FILE__)."/$file_path");

                    $image_list[$image_count] = $file_path;
                    $image_count++;
                }
            } else {
                unlink($tmpfilename);
                echo "<script type='text/javascript'>Alert('上传失败,非图片类型!', 1)</script>";
            }
        } else {
            echo "<script type='text/javascript'>Alert('上传失败!', 1)</script>";
        }
    }
}
//var_dump($image_list);


?>
    <div class="ds_box border_bottom">
        <a onclick="go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        发文
    </div><!--<End ds_box-->
    <form id="form_article" class="newreply_conter input_padding1">
        <h4>标题:</h4>
        <input class="newreply_txt" type="text" value="<?php echo $title_backup;?>"/>
        <h4>正文:</h4>
        <textarea cols="30" rows="10"><?php echo $content_backup; ?></textarea>
    </form>
    <form id="form_image" enctype="multipart/form-data" class="newreply_conter input_padding2" method="post" action="writearticle.php">
        <input type="hidden" id="title_backup" name="title_backup" value=""/>
        <input type="hidden" id="content_backup" name="content_backup" value=""/>
        <input type="hidden" name="article_type" value="<?php echo $type; ?>" />
        <input type="hidden" name="t_name" value="<?php echo $name; ?>"/>
        <input type="hidden" name="image_count" value="<?php echo $image_count; ?>">
        <?php foreach ($image_list as $key=>$imagepath) { ?>
            <input type="hidden" name="image_<?php echo $key; ?>" value="<?php echo $imagepath; ?>">
        <?php } ?>
        <p id="image_area">
            <span class="add_img margin_r"></span>
            <input name="image_file" type="file" onchange="image_submit();"/>
            <?php foreach($image_list as $path) { ?>
                <img class="newreply_img_r" src="<?php echo $path; ?>" alt="file.png"/>
            <?php } ?>
        </p>
    </form>
    <p class="newreply_conter input_padding2"><input class="newreply_sub" type="button" value="发表" onclick="return article_submit();"/></p>
        <!-- /# -->
    <script type="text/javascript">
        function image_submit() {
            var form_article = document.getElementById("form_article");
            var input = form_article.getElementsByTagName("input");
            var textarea = form_article.getElementsByTagName("textarea");
            var title_backup = document.getElementById("title_backup");
            var content_backup = document.getElementById("content_backup");

            if (input[0].value.length != 0) {
                title_backup.value = input[0].value;
            }
            if (textarea[0].value.length != 0) {
                content_backup.value = textarea["0"].value;
            }

            document.getElementById("form_image").submit();
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

            if (article_type == "1") {
                para = "board="+name+"&title="+input[0].value+"&reid=0&content="+textarea[0].value;
                var href = "one_board.php?board="+name;
            } else if (article_type == "2") {
                para = "club="+name+"&title="+input[0].value+"&reid=0&content="+textarea[0].value;
                var href = "one_club.php?club="+name;
            }

            send_article(url, para, false);
            document.location = href;
            return false;
        }
    </script>
<?php
include_once("foot.php");
?>