<?php

function url_generate($level, $data) {
    /*  level ��Ϊ4��
     *      1: ����
     *      2: ����
     *      3: ����
     *      4: ����
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