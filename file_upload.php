<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
/**
 * Created by PhpStorm.
 * User: liyang
 * Date: 15-10-19
 * Time: ����10:54
 */

$thumb_dir = "thumb";
if (is_dir($thumb_dir))
    @mkdir($thumb_dir);

// file_path ������� nginx��·����λ��
$file_path = "NULL";

$utmpnum = $_COOKIE["UTMPNUM"];
$image_count = 0;
if ($_POST["img_count"]) {
    $image_count = intval($_POST["img_count"]);
}

/* ���ϴ��ĸ�������� $attachdir ��,��$attachdir ���� .index �ļ�����¼�˽����ϴ����ļ���Ϣ��post_article ʱ,��� .index �ļ���ȡ����Ϣ��
 * �ļ���ÿһ�м�¼һ�����ϴ��ĸ�������Ϣ����Ϣ��ʽΪ
 *          ����·�� + �ո� + �����ϴ�ʱʹ�õ��ļ���
 *          ����:����·��Ϊ $tmpfilename, �����ϴ�ʱʹ�õ��ļ���Ϊ $_FILES["image_file"]["file"],������ԭʼ����
 */
if (!empty($_FILES["image_file"]["tmp_name"])) {
    // ���������ݴ�·��,post_articleʱ���ȡ���ļ���
    $attachdir = bbs_getattachtmppath($currentuser["userid"] ,$utmpnum);
    @mkdir($attachdir);
    // ����������ʱ�ļ���
    $tmpfilename = tempnam($attachdir, "att");
    if (is_uploaded_file($_FILES["image_file"]["tmp_name"])) {
        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $tmpfilename)) {
            /* ����ļ������Ƿ�ΪͼƬ */
            if (checkImageFile($tmpfilename)) {
                // 1.��Ӹ�����Ϣ�� .index�ļ���
                if ($image_count == 0) {
                    $fp = fopen($attachdir."/.index", "w");
                } else {
                    $fp = fopen($attachdir."/.index", "a");
                }

                if ($fp == false)
                    $fp = fopen($attachdir."/.index", "w");

                fputs($fp, $tmpfilename." ".$_FILES["image_file"]["name"]."\n");
                @fclose($fp);

                // 2.����ͼƬ����ͼ
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
                }
            } else {
                unlink($tmpfilename);
                $file_path = "ERROR:�ϴ�ʧ��,��ͼƬ����!";
            }
        } else {
            $file_path = "ERROR:�ϴ�ʧ��!";
        }
    } else {
        $file_path = "ERROR:�ļ��ϴ�ʧ��!";
    }
}

?>
<!-- �ϴ��ɹ�,������ͼ�����ɹ�,��file_pathΪ����ͼ·��;����,file_path��¼������Ϣ,��"ERROR:"��ͷ -->
<h3 id="file_path"><?php echo $file_path; ?></h3>
