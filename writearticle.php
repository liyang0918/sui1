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

// �������ͣ�1��̳���� 2���ֲ�����
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
    // �����ر��б����ͼƬ·����ȡ��������
    // ͼƬ��·����������ڷ������ϵ�λ��
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

/* ���ϴ��ĸ�������� $attachdir ��,��$attachdir ���� .index �ļ�����¼�˽����ϴ����ļ���Ϣ��post_article ʱ,��� .index �ļ���ȡ����Ϣ��
 * �ļ���ÿһ�м�¼һ�����ϴ��ĸ�������Ϣ����Ϣ��ʽΪ
 *          ����·��+�ո�+�����ϴ�ʱʹ�õ��ļ���
 * ����·��Ϊ $tmpfilename, �����ϴ�ʱʹ�õ��ļ���Ϊ $_FILES["image_file"]["file"],������ԭʼ����
 */

$title_backup = $_POST["title_backup"];
$content_backup = $_POST["content_backup"];

if (!empty($_FILES["image_file"]["tmp_name"])) {
    // ��������֤ͼƬ,����ŵ�ָ���ļ���
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
                // �����֤ͨ����ͼƬ�ɹ�����ָ���ļ���,��image_count++
                $tmparr = array();
                if(preg_match("/.*\/(.*)\/(.*)/", $tmpfilename, $tmparr) == 1) {
                    $file_dir = $thumb_dir."/".$tmparr[1];
                    $file_path = $file_dir."/".$tmparr[2];
                    if ($image_count == 0) {
                        @system("rm -rf ".dirname(__FILE__)."/$file_dir");
                    }
                    @mkdir(dirname(__FILE__)."/$file_dir");
                    @copy($tmpfilename, dirname(__FILE__)."/$file_path");
                    // ��������ͼ
                    constrcutThumbnail(dirname(__FILE__)."/$file_path");

                    $image_list[$image_count] = $file_path;
                    $image_count++;
                }
            } else {
                unlink($tmpfilename);
                echo "<script type='text/javascript'>Alert('�ϴ�ʧ��,��ͼƬ����!', 1)</script>";
            }
        } else {
            echo "<script type='text/javascript'>Alert('�ϴ�ʧ��!', 1)</script>";
        }
    }
}
//var_dump($image_list);


?>
    <div class="ds_box border_bottom">
        <a onclick="go_last_page()"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        ����
    </div><!--<End ds_box-->
    <form id="form_article" class="newreply_conter input_padding1">
        <h4>����:</h4>
        <input class="newreply_txt" type="text" value="<?php echo $title_backup;?>"/>
        <h4>����:</h4>
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
    <p class="newreply_conter input_padding2"><input class="newreply_sub" type="button" value="����" onclick="return article_submit();"/></p>
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
                Alert("δ֪�İ�������ֲ���!", 1);
                return false;
            }

            // ��ȡ�����ݣ�ʹ��ajax post����
            var form_article = document.getElementById("form_article");
            var input = form_article.getElementsByTagName("input");
            var textarea = form_article.getElementsByTagName("textarea");
            if (input[0].value.length == 0) {
                Alert("����д���±���!"+article_type, 1);
                input[0].focus();
                return false;
            }
            if (textarea[0].value.length == 0) {
                Alert("��������̫��!", 1);
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