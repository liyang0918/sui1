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
        $ret["faileDesc"] = "尚未登录";
        $ret["logoutResult"] = "2"; //error
    } else {
        bbs_wwwlogoff($userid, $utmpkey);
        $ret["faileDesc"] = "登出成功";
        $ret["logoutResult"] = "1"; //error
    }

    return $ret;
}

$ret = userLogout();
echo '<script type="text/javascript">';
echo 'location.href = "index.php";';
if ($ret["logoutResult"] == "1") {
    echo 'Alert("登出成功", 1);';
} else {
    echo 'Alert("您尚未登录", 1);';
}

echo '</script>';


?>
</div>
</body>
</html>