<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");
$conn=db_connect_web();
$all_arr=array();

function getClasses($msg) {
    global $forum_class_list;
    $t_data = array();
    $t_data["href"] = url_generate(1, array("class" => $forum_class_list[$msg]["section_num"]));
    $t_data["img"] = "img/board".$msg.".png";
    $t_data["alt"] = "board".$msg.".png";
    $t_data["content"] = $forum_class_list[$msg]["section_name"];

    return $t_data;
}

$type="classes";
$str_content = '<div id="detail"><div class="board_wrap">';

for ($i = 1; $i <= 12; $i++) {
    $t_data = getClasses($i);
    $str_content .=
        '<div class="bd_list border_bottom">
            <a href="'.$t_data["href"].'">
                <img src="'.$t_data["img"].'" alt="'.$t_data["alt"].'"/>
                <strong>'.$t_data["content"].'</strong>'.'<span class="bd_right"></span>
            </a>
        </div>';
}


$str_content .= '</div></div>';
// echo $str_content;
// json_encode()转换的字符串若含的中文,必须为UTF-8编码
$all_arr["detail"] = iconv('GBK', 'UTF-8', $str_content);
//var_dump($all_arr);
echo json_encode($all_arr);
mysql_close($conn);
?>
