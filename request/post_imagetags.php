<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
require_once(dirname(__FILE__)."/../func.php");
$link = db_connect_web();

$dbg_switch = true;

$method_array = $_POST;
$user_id = $currentuser["userid"];
$user_num_id = $currentuser["num_id"];
$utmpnum = $_COOKIE["UTMPNUM"];
if (empty($user_id) or $user_id == "guest")
    error_quit(-1, "您还没有登录", "beforeExit", array($link, ""));

$tmp_dir = getUploadTmpDir($user_id, $utmpnum);

function error_quit($errno, $error_str, $fun=NULL, $args=array(NULL, "")) {
    // before exit
    if ($fun != NULL)
        call_user_func_array($fun, $args);

    echo json_encode(array("result"=>$errno, "msg"=>iconv("GBK", "UTF-8", $error_str)));
    exit;
}




function getFilePathFromIndex($dir) {
    $content_arr = file($dir."/.index");

    $file_arr = array();
    foreach ($content_arr as $line) {
        // 删除换行符号
        $line = substr($line, 0, -1);
        list($path, $pic_name) = explode(" ", $line);
        $res = array();
        // 文件名格式:  数字前缀 + "_" + 随机序列
        // 文件名格式不正确直接退出
        if (!preg_match('/.*\/(\d)_(.*)$/', $path, $res))
            return false;

        $index = intval($res[1]);
        // 文件名前缀重复直接退出
        if (isset($file_arr[$index]))
            return false;

        $pic_name = explode(".", $pic_name)[0];

        $file_arr[$index] = array("path"=>$path, "pic_name"=>$pic_name);
    }

    return $file_arr;
}

function dpCheckTagWithUserID($link, $shop_id, $tag_name, $dir, $user_id){
    if($dir == 'dish'){
        $table = 'tags';
    }else{
        $table = 'env_tags';
    }
    $sql = 'select count(*) as count from '.$table.' where shop_id='.$shop_id.' and tag_name="'.$tag_name.'" and user_id="'.$user_id.'"';
    $result = mysql_query($sql, $link);
    if ($row = mysql_fetch_array($result))
        return $row["count"];
    else
        return 0;
}

function dpInsertTag($link, $tag, $shop_id, $user_id, $dir){
    if($dir == 'dish'){
        $table = 'tags';
    }else{
        $table = 'env_tags';
    }
    $sql = 'insert into '.$table.'(shop_id,tag_name,choose_num,user_id) values ('.$shop_id.',"'.$tag.'",1,'.$user_id.')';
    mysql_query($sql, $link);
    $sql1 = 'select tag_id from '.$table.' where shop_id='.$shop_id.' and tag_name="'.$tag.'" and user_id='.$user_id;
    $result = mysql_query($sql1, $link);
    if ($row = mysql_fetch_array($result))
        return $row["tag_id"];
    else
        return false;
}

function dpGetTagsIDWithUserID($link, $tag_name, $shop_id, $dir, $user_num_id){
    if($dir == 'dish'){
        $table = 'tags';
    }else{
        $table = 'env_tags';
    }
    $sql1 = 'select tag_id from '.$table.' where shop_id='.$shop_id.' and tag_name="'.$tag_name.'" and user_id="'.$user_num_id.'"';
    $result = mysql_query($sql1, $link);
    if ($row = mysql_fetch_array($result))
        return $row["tag_id"];
    else
        return false;
}

function dpCheckTag($link, $shop_id, $tag_name, $user_num_id, $dir){
    $count = dpCheckTagWithUserID($link, $shop_id, $tag_name, $dir, $user_num_id);
    if($count <= 0){
        return dpInsertTag($link, $tag_name, $shop_id, $user_num_id, $dir);
    }else{
        return dpGetTagsIDWithUserID($link, $tag_name, $shop_id, $dir, $user_num_id);
    }
}

function dpInsertIMG($link, $shop_id, $tag_id, $user_num_id, $user_id, $dir, $img_name, $desc){
    if($tag_id=="-1"){
        $sql = 'insert into comment_img(shop_id,user_id,user_name,type,img_name,create_time,display,description)
                values('.$shop_id.','.$user_num_id.',"'.$user_id.'","'.$dir.'","'.$img_name.'",'.time().',1,"'.$desc.'")';
    } else {
        $sql = 'insert into comment_img(shop_id,tag_id,user_id,user_name,type,img_name,create_time,display,description)
                values('.$shop_id.','.$tag_id.','.$user_num_id.',"'.$user_id.'","'.$dir.'","'.$img_name.'",'.time().',1,"'.$desc.'")';
    }

     return mysql_query($sql, $link);
}

function dpSavePic2Db($link, $shop_id, $file_info) {
    global $user_id, $user_num_id;
    $file_name = $file_info["pic_name"].'_'.$user_id.'_'.time();
    $shop_path =  BBS_HOME.'/pic_home/comment/'.$shop_id.'/'.$file_info["dir"];
    @mkdir($shop_path);
    while(is_file($shop_path.'/'.$file_name)){
        $file_name = $file_info["pic_name"].'_'.$user_id.'_'.(time()+1);
    }
    $tag_id = dpCheckTag($link, $shop_id, $file_info["tag_name"], $user_num_id, $file_info["dir"]);
    if (dpInsertIMG($link, $shop_id, $tag_id, $user_num_id, $user_id, $file_info["dir"], $file_name, "")) {
        @system("cp {$file_info["path"]} $shop_path/$file_name -r");
        if (!constrcutThumb($shop_path."/"."file_name")) {
            // 创建缩略图失败则将原图复制为缩略图
            @system("cp {$file_info["path"]} {$shop_path}/{$file_name}_nail -r");
        }
    } else {
        return false;
    }

    return true;
}

function beforeExit($link=NULL, $tmp_dir="") {
    if ($link != NULL)
        @mysql_close($link);

    if ($tmp_dir != "")
        @system("rm $tmp_dir -rf");
}

if (!isset($method_array["shop_id"]))
    error_quit(-2, "未知的店铺", "beforeExit", array($link, $tmp_dir));
$shop_id = $method_array["shop_id"];

$file_arr = getFilePathFromIndex($tmp_dir);
for ($i = 1; $i <= sizeof($file_arr); $i++) {
    $tmp = array();
    if (preg_match('/([a-zA-Z]+)_(.*)/', $method_array["tag_".$i], $tmp)) {
        $file_arr[$i]["tag_name"] = "";
        switch ($tmp[1]) {
            case "dish":
            case "env":
                $file_arr[$i]["tag_name"] = $tmp[2];
                if ($tmp_name = iconv("UTF-8", "GBK//IGNORE", $tmp[2]))
                    $tmp[2] = $tmp_name;
            case "auto":
                $file_arr[$i]["dir"] = $tmp[1];
                break;
            default:
                $file_arr[$i]["dir"] = "auto";
        }
    } else {
        continue;
    }

    $file_info = $file_arr[$i];
    if (!file_exists($file_info["path"]))
        continue;

    if (dpSavePic2Db($link, $shop_id, $file_info) == false) {
        error_quit(-3, "图片上传失败", "beforeExit", array($link, $tmp_dir));
    }
}

@system("rm $tmp_dir -rf");

//if ($dbg_switch) {
//    foreach ($method_array as $a=>$b) {
//        log2file("$a=>$b");
//    }
//
//    foreach ($file_arr as $i=>$each) {
//        log2file("[$i]");
//        foreach ($each as $key=>$value) {
//            log2file("\t$key=>\t$value");
//        }
//        log2file("");
//    }
//}

echo json_encode(array("result"=>0));
mysql_close($link);

return true;