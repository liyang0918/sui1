<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15-9-16
 * Time: ионГ11:25
 */
$str = "/home/liyang/works/sui1/manage.sh";

$arr = array();
preg_match("/.*\/(.*)\/(.*)/", $str, $arr);
var_dump($arr);


