<?php

function url_generate($level, $data) {
    /*  level 分为4级
     *      1: 分类
     *      2: 版面
     *      3: 分组
     *      4: 保留
     * */
    $url = "../forum_articlecontent.html";
    switch($level) {
        case 1:
            if (!isset($data["class"]))
                return false;
            $url = 'one_class.php?classes='.$data["classes"];
            break;
        case 2:
            if (isset($data["board"]))
                $url = 'one_board.php?board='.$data["board"];
            else if (isset($data["club"]))
                $url = 'one_club.php?club='.$data["club"];
            else
                return false;

            break;
        case 3:
            if(!isset($data["board"]) or !isset($data["groupid"]))
                return false;
            $url = 'one_group.php?board='.$data["board"].'&group='.$data["groupid"];
            break;
        default:
            break;
    }

    return $url;
}

?>