<?php

require_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
require_once("func.php");
require_once("head.php");

function userLogout() {
    global $currentuser, $_COOKIE;
    $ret = array();
    $userid = $currentuser["userid"];
    $utmpkey = $_COOKIE["UTMPKEY"];
    if ($userid == "guest" or empty($userid))
        $loginok = 2;
    else
        $loginok = 1;

    if ($loginok != 1) {
        $ret["faileDesc"] = "ÉÐÎ´µÇÂ¼";
        $ret["logoutResult"] = "2"; //error
    } else {
        bbs_wwwlogoff($userid, $utmpkey);
        $ret["faileDesc"] = "µÇ³ö³É¹¦";
        $ret["logoutResult"] = "1"; //error
    }

    return $ret;
}

$ret = userLogout();
echo '<script type="text/javascript">';
if(!empty($_COOKIE["before_login"]))
    echo 'location.href = "'.$_COOKIE["before_login"].'";';
else
    echo 'location.href = "index.php";';
if ($ret["logoutResult"] == "1") {
    echo 'Alert("µÇ³ö³É¹¦", 1);';
} else {
    echo 'Alert("ÄúÉÐÎ´µÇÂ¼", 1);';
}

echo '</script>';


?>
</div>
</body>
</html>