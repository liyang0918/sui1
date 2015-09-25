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

//���ʺ�,��Ӧ�������������˺��µ� ACCOUNT SID
$accountSid= 'aaf98f894a85eee5014a998477fe08f6';

//���ʺ�����,��Ӧ�������������˺��µ� AUTH TOKEN
$accountToken= 'fd7b80ae865343eaa7d6e7fe82bd018f';

//Ӧ��Id���ڹ���Ӧ���б��е��Ӧ�ã���ӦӦ�������е�APP ID
//�ڿ������Ե�ʱ�򣬿���ʹ�ù����Զ�Ϊ������Ĳ���Demo��APP ID
//$appId='aaf98f894a85eee5014a998a9ee208fa';
$appId='8a48b5514cfa209e014cff0a79d20407';

//�����ַ
//ɳ�л���������Ӧ�ÿ������ԣ���sandboxapp.cloopen.com
//�����������û�Ӧ������ʹ�ã���app.cloopen.com
//$serverIP='sandboxapp.cloopen.com';
$serverIP='app.cloopen.com';


//����˿ڣ�����������ɳ�л���һ��
$serverPort='8883';

//REST�汾�ţ��ڹ����ĵ�REST�����л�á�
$softVersion='2013-12-26';

/**
  * ����ģ�����
  * @param to �ֻ����뼯��,��Ӣ�Ķ��ŷֿ�
  * @param datas �������� ��ʽΪ���� ���磺array('Marry','Alon')���粻���滻���� null
  * @param $tempId ģ��Id,����Ӧ�ú�δ����Ӧ��ʹ�ò���ģ������д1����ʽӦ�����ߺ���д���������ͨ����ģ��ID
  */       
function sendTemplateSMS($to,$datas,$tempId)
{
     //��ʼ��REST SDK
     global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
     //$ret="";
    //$ret.=$rest->check_info();

     // ����ģ�����
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
         //TODO ��Ӵ������߼�
         //if($result->statusCode = '160013')
             $ret.=$result->statusCode;
     }else{
         //$ret.="Sendind TemplateSMS success!<br/>";
         // ��ȡ������Ϣ
         $smsmessage = $result->TemplateSMS;
         //$ret.="dateCreated:".$smsmessage->dateCreated."<br/>";
         //$ret.="smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
         //TODO ��ӳɹ������߼�
         $ret="ok";
     }
    return $ret;
}

//Demo���� 
		//**************************************����˵��***********************************************************************
		//*�������ò���Demo��APP ID������ʹ��Ĭ��ģ��ID 1�������ֻ�����13800000000���������Ϊ6532��5������÷�ʽΪ           *
		//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
		//*��13800000000�ֻ����յ��Ķ��������ǣ�����ͨѶ����ʹ�õ�����ͨѶ����ģ�壬������֤����6532������5��������ȷ����     *
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

    //var_dump($phone_num, $confirm_arr, $temp_id);//�ֻ����룬�滻�������飬ģ��ID
    $filename="/tmp/sms.log";
    $fp=@fopen($filename,"a+");
    if($country_code=='86'){
        $tmp_id='cn';
        $ret_Ex=sendTemplateSMS($phone_num, $confirm_arr, $temp_id);//�ֻ����룬�滻�������飬ģ��ID
        if($ret_Ex!="ok"){
            $ret="���ŷ��ʹ���(".$ret.")";
        }else
            $ret=true;
    } else
    if($country_code=='1') {
        $tmp_id='us';
        $ret_Ex = sendSMSex($phone_num, $confirm_arr, 1);
        if($ret_Ex=="ok"){
            $ret=true;
        }else{
            $ret="���ŷ��ʹ���(����)";
        }
    }else {
        $ret_Ex = sendSMSex($phone_num, $confirm_arr, 0);
        $tmp_id = 'other';
        if($ret_Ex=="ok"){
            $ret=true;
        }else{
            $ret="���ŷ��ʹ���";
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
