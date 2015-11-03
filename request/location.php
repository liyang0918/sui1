<?php
session_start();
require_once(dirname(__FILE__)."/../../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/../func.php");

if (isset($_POST["search"])) {
    $address = $_POST["search"];
    $result["result"] = "0";
    $url = "http://ditu.google.cn/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";
    $tuCurl = curl_init();
    curl_setopt($tuCurl, CURLOPT_URL,$url);
    curl_setopt($tuCurl, CURLOPT_HEADER, 0);
    curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
    $tuData = curl_exec($tuCurl);
    $data = json_decode($tuData,true);

    $result["result"] = "1";
    $result["location"] = $data["results"][0]["geometry"]["location"];
    if (empty($result["location"]))
        $result["result"] = "0";

//    foreach ($result as $each) {
//        if (!is_array($each)) {
//        log2file($each);}
//        else {
//            foreach ($each as $key=>$value) {
//                log2file("\t$key=>$value");
//            }
//        }
//        log2file("====");
//    }
    echo json_encode($result);

//    foreach ($data["results"][0]["geometry"]["location"] as $key=>$each) {
//        log2file($key."=>".$each);
//    }
} else {
    $_SESSION["locate_flag"] = false;
    $_SESSION["lon"] = "0.0000";
    $_SESSION["lat"] = "0.0000";

    if ($_POST["result"] == "success") {
        $_SESSION["locate_flag"] = true;
        $_SESSION["lon"] = floatval($_POST["lon"]);
        $_SESSION["lat"] = floatval($_POST["lat"]);
    }
}
?>
