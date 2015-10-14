<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15-9-16
 * Time: ÉÏÎç11:25
 */

$arr = file("/home/bbs/mail/M/mitbbs/M.1440397542_2.Z0");

foreach ($arr as $key=>$value) {
    echo "[$key]   $value<br />";
}

