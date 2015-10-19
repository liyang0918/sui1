<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
/**
 * Created by PhpStorm.
 * User: liyang
 * Date: 15-10-19
 * Time: 上午10:54
 */

$thumb_dir = "thumb";
if (is_dir($thumb_dir))
    @mkdir($thumb_dir);

// file_path 是相对于 nginx根路径的位置
$file_path = "NULL";

$utmpnum = $_COOKIE["UTMPNUM"];
$image_count = 0;
if ($_POST["img_count"]) {
    $image_count = intval($_POST["img_count"]);
}

/* 待上传的附件存放在 $attachdir 中,在$attachdir 中有 .index 文件，记录了将被上传的文件信息，post_article 时,会打开 .index 文件读取该信息。
 * 文件中每一行记录一个待上传的附件的信息，信息格式为
 *          附件路径 + 空格 + 附件上传时使用的文件名
 *          其中:附件路径为 $tmpfilename, 附件上传时使用的文件名为 $_FILES["image_file"]["file"],即附件原始名称
 */
if (!empty($_FILES["image_file"]["tmp_name"])) {
    // 创建附件暂存路径,post_article时会读取该文件夹
    $attachdir = bbs_getattachtmppath($currentuser["userid"] ,$utmpnum);
    @mkdir($attachdir);
    // 创建附件临时文件名
    $tmpfilename = tempnam($attachdir, "att");
    if (is_uploaded_file($_FILES["image_file"]["tmp_name"])) {
        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $tmpfilename)) {
            /* 检查文件类型是否为图片 */
            if (checkImageFile($tmpfilename)) {
                // 1.添加附件信息到 .index文件中
                if ($image_count == 0) {
                    $fp = fopen($attachdir."/.index", "w");
                } else {
                    $fp = fopen($attachdir."/.index", "a");
                }

                if ($fp == false)
                    $fp = fopen($attachdir."/.index", "w");

                fputs($fp, $tmpfilename." ".$_FILES["image_file"]["name"]."\n");
                @fclose($fp);

                // 2.生成图片缩略图
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
                }
            } else {
                unlink($tmpfilename);
                $file_path = "ERROR:上传失败,非图片类型!";
            }
        } else {
            $file_path = "ERROR:上传失败!";
        }
    } else {
        $file_path = "ERROR:文件上传失败!";
    }
}

?>
<!-- 上传成功,且缩略图创建成功,则file_path为缩略图路径;否则,file_path记录错误信息,以"ERROR:"开头 -->
<h3 id="file_path"><?php echo $file_path; ?></h3>
