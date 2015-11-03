<?php
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

$link = db_connect_web();
$page = $_COOKIE["current_page"];
if (empty($page))
    $page = 1;

$i_board_list = array(
    "0" => array("title"=>"移民", "boardid"=>"182"),
    "1" => array("title"=>"签证", "boardid"=>"306"),
    "2" => array("title"=>"出国", "boardid"=>"118"),
    "3" => array("title"=>"回国", "boardid"=>"239"),
    "4" => array("title"=>"探亲", "boardid"=>"162"),
    "5" => array("title"=>"海外生活", "boardid"=>"69")
);

// detail start
$sql_base = 'select boardname,board_desc from board where board_id=';
$str_article = '<div class="board_wrap">';
foreach ($i_board_list as $i=> $each) {
    $sql = $sql_base.$each["boardid"];
    $result = mysql_query($sql, $link);
    $row = mysql_fetch_array($result);
    $boardEngName = $row["boardname"];
    $boardCnName = $row["board_desc"];

    $boardCnName = preg_replace('/^\d*\[.*\]/', "", $boardCnName);
    $str_article .= '<div class="bd_list border_bottom">';
    $str_article .=
'<a href="'.url_generate(2, array("board"=>$boardEngName)).'">
    <img src="img/dis'.($i+1).'.png" alt="board1.png"/>
    <strong>'.$each["title"].' <em class="dis_tip">'.$boardCnName.'（'.$boardEngName.'）</em></strong>
    <span class="bd_right"></span>
</a>';
    $str_article .= '</div>';
}

$str_article .= '</div>';

//detail end

$str_article = mb_convert_encoding($str_article, "UTF-8", "GBK");
$all_arr["detail"] = $str_article;
if ($page == 1)
    echo json_encode($all_arr);
else
    echo json_encode(array("article"=>$str_article));
mysql_close($link);
?>
