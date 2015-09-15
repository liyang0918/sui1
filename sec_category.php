<?php
switch($_COOKIE["app_type"]){
    case "index" :
        include_once("sec_index.php");
        break;
    case "/mobile/forum/one_page.php" :
        include_once("sec_index.php");
        break;
    default:
        break;
}
?>
