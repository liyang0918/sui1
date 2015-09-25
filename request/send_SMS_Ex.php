<?php
//for external send SMS
$Headurl="https://rest.nexmo.com/sms/json?";
$Headurl_us="https://rest.nexmo.com/sc/us/2fa/json?";
$Key_id="7da4832a";
$Secret="eb6ee7b3";
function sendSMSex($to,$datas,$type){
    global $Headurl,$Key_id,$Secret,$Headurl_us;
    if($type==0) {
        $body = "Verification code [" . $datas[0] . "] from www.mitbbs.com. Please input it in " . $datas[1] . " minutes.";
        $body = iconv("gb2312", "utf-8//IGNORE", $body);
        $send_body="api_key=".$Key_id."&api_secret=".$Secret."&from=Mitbbs"."&to=".$to."&text=".$body;
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$Headurl);
        curl_setopt($curl,CURLOPT_HEADER,1);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$send_body);
    }elseif($type==1){
        $send_body="api_key=".$Key_id."&api_secret=".$Secret."&to=".$to."&pin=".$datas[0];
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$Headurl_us);
        curl_setopt($curl,CURLOPT_HEADER,1);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$send_body);
    }

    $data=curl_exec($curl);

    curl_close($curl);
    $pos=strpos($data,"status\":\"");
    $str_sub=substr($data,$pos+9);
    $pos=strpos($str_sub,"\"");
    $status=substr($str_sub,0,$pos);
    $pos=strpos($str_sub,"error-text\":\"");
    $str_sub_1=substr($str_sub,$pos+13);
    $pos=strpos($str_sub_1,"\"");
    $err_txt=substr($str_sub_1,0,$pos);

    /*
    $ret_arr=explode(":", $data);
    var_dump($data);
    var_dump($ret_arr);
    var_dump($ret_arr[14]);
    var_dump($ret_arr[15]);
    */
    if($data == NULL ) {
        $ret="fail";
    }
    if($status!='0') {
        //$ret.="error code :" . $result->statusCode . "<br>";
        //$ret.="error msg :" . $result->statusMsg . "<br>";
        //TODO 添加错误处理逻辑
        //if($result->statusCode = '160013')
        $ret=$status."|".$err_txt;
        //$ret=$data;
    }else{
        //$ret.="Sendind TemplateSMS success!<br/>";
        // 获取返回信息
        //$smsmessage = $data->TemplateSMS;
        //$ret.="dateCreated:".$smsmessage->dateCreated."<br/>";
        //$ret.="smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
        //TODO 添加成功处理逻辑
        $ret="ok";
    }
    return $ret;
}
?>
