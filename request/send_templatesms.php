<?php
session_start();
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

require(dirname(__FILE__)."/CCPRestSDK.php");
require_once(dirname(__FILE__)."/send_SMS_Ex.php");
require("../../../mitbbs_funcs.php");

//主帐号,对应开官网发者主账号下的 ACCOUNT SID
$accountSid= 'aaf98f894a85eee5014a998477fe08f6';

//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
$accountToken= 'fd7b80ae865343eaa7d6e7fe82bd018f';

//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
//$appId='aaf98f894a85eee5014a998a9ee208fa';
$appId='8a48b5514cfa209e014cff0a79d20407';

//请求地址
//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
//生产环境（用户应用上线使用）：app.cloopen.com
//$serverIP='sandboxapp.cloopen.com';
$serverIP='app.cloopen.com';


//请求端口，生产环境和沙盒环境一致
$serverPort='8883';

//REST版本号，在官网文档REST介绍中获得。
$softVersion='2013-12-26';

/**
  * 发送模板短信
  * @param to 手机号码集合,用英文逗号分开
  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
  * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
  */       
function sendTemplateSMS($to,$datas,$tempId)
{
     //初始化REST SDK
     global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
     //$ret="";
    //$ret.=$rest->check_info();

     // 发送模板短信
     //$ret.="Sending TemplateSMS to $to <br/>";
        if($to[0]=='8' && $to[1]=='6'){
            $to=(substr($to,2));
        }
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     if($result == NULL ) {
         $ret=false;
     }
     if($result->statusCode!=0) {
         //$ret.="error code :" . $result->statusCode . "<br>";
         //$ret.="error msg :" . $result->statusMsg . "<br>";
         //TODO 添加错误处理逻辑
         //if($result->statusCode = '160013')
             $ret.=$result->statusCode;
     }else{
         //$ret.="Sendind TemplateSMS success!<br/>";
         // 获取返回信息
         $smsmessage = $result->TemplateSMS;
         //$ret.="dateCreated:".$smsmessage->dateCreated."<br/>";
         //$ret.="smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
         //TODO 添加成功处理逻辑
         $ret="ok";
     }
    return $ret;
}

//Demo调用 
		//**************************************举例说明***********************************************************************
		//*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
		//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
		//*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
		//*********************************************************************************************************************
//echo  $_SESSION['phone_num_final'];
if((!empty($_POST['phone_num_final']) || !empty($_SESSION['phone_num_final']) )&& !empty($_POST['temp_id']) ) {
    if((!empty($_POST['phone_num_final']))) {
        $phone_num = $_POST['phone_num_final'];
        $_SESSION['phone_num_final']=$phone_num;
    }
    elseif((empty($_POST['phone_num_final'])) &&(!empty($_SESSION['phone_num_final'])))
        $phone_num=$_SESSION['phone_num_final'];
    if(!empty($_GET['country_code']) )
        $country_code=$_GET["country_code"];
    else
        $country_code=$_POST["country_code"];
    $confirm_num=$_SESSION['confirm_num'];
    $temp_id=$_POST['temp_id'];
    $confirm_arr=array();
    $confirm_arr[0]=$confirm_num;
    $confirm_arr[1]='10';

    //var_dump($phone_num, $confirm_arr, $temp_id);//手机号码，替换内容数组，模板ID
    $filename="/tmp/sms.log";
    $fp=@fopen($filename,"a+");
    if($country_code=='86'){
        $tmp_id='cn';
        $ret_Ex=sendTemplateSMS($phone_num, $confirm_arr, $temp_id);//手机号码，替换内容数组，模板ID
        if($ret_Ex!="ok"){
            $ret="短信发送错误(".$ret.")";
        }else
            $ret=true;
    } else
    if($country_code=='1') {
        $tmp_id='us';
        $ret_Ex = sendSMSex($phone_num, $confirm_arr, 1);
        if($ret_Ex=="ok"){
            $ret=true;
        }else{
            $ret="短信发送错误(北美)";
        }
    }else {
        $ret_Ex = sendSMSex($phone_num, $confirm_arr, 0);
        $tmp_id = 'other';
        if($ret_Ex=="ok"){
            $ret=true;
        }else{
            $ret="短信发送错误";
        }
    }
    if ($fp!=false) {
        fwrite($fp,"country_code:".$country_code."\n");
        fwrite($fp,"phone_num:".$phone_num."\n");
        fwrite($fp, "return:".$ret_Ex."\n");
        fwrite($fp, "use country sms:".$tmp_id."\n");
        fclose($fp);
    }
    $ret=charsetToUTF8($ret);
    echo $ret;
}
?>
