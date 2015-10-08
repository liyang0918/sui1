<?php
include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
include_once(dirname(__FILE__)."/func.php");
include_once(dirname(__FILE__)."/../../yimin/mitbbs_lawyer_funcs.php");
include_once("head.php");
$link = db_connect_web();
//data part
$creator=$_GET["board"];
$lawyer = lawyer_getlawyer_bycreator($creator, $link);
if (!empty($lawyer)) {
    $lawyer["picname"] = "http://".$_SERVER['HTTP_HOST'] ."/picture/".strtoupper(substr($lawyer["creator"], 0, 1)).'/'.$lawyer["creator"].'/lawyerimg';
} else {
    $lawyer = array(
        "lawyer_name" => "��������",
        "office" => "δ���ռ�",
        "country" => "China",
        "country_cname" => "�й�",
        "state" => "",
        "city" => "BeiJing",
        "city_cname" => "������",
        "street" => "���������ݽ�14��",
        "zip_code" => "100085",
        "telephone" => "010-00011000",
        "website" => "www.mitbbs.com",
        "introduction" => "δ���ռ�(mitbbs.com) - ���⻪�˵�һ�Ż���������1996�꣬ӵ����ʮ����ע���û�,Ϊ����ȫ���˵��ۺ�����վ��������������������",
        "approval_userid" => "1",
        "approval_state" => "�����к�����",
        "approval_time" => strftime("G-%m-%d", time()),
        "creator" => $creator,
        "creat_time" => strftime("%G-%m-%d", time()),
        "last_modify_time" => strftime("%G-%m-%d", time()),
        "identity_flag" => "S",
        "visit_num" => "8086",
        "business" => "���⻪��,����,����,�Ż�,����,��̳,BBS",
        "picname" =>"/yimin/images/ChenLaw.jpg"
    );
}
// ������תΪGBK
foreach ($lawyer as $index=>$each) {
    $tmp = "";
    $tmp = iconv("UTF-8", "GBK//IGNORE", $each);
    $lawyer[$index] = empty($tmp)?$each:$tmp;
}

?>
    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php echo $lawyer["lawyer_name"]; ?>��ʦ������Ϣ
    </div>
    <ul class="lawer_info_box margin-bottom">
        <li class="lawer_base">
            <h4 class="border_bottom">������Ϣ</h4>
            <div class="lawer_box">
                <p><span>��ʦ������</span><em><?php echo $lawyer["lawyer_name"]; ?></em></p>
                <p><span>���������ƣ�</span><em><?php echo $lawyer["office"]; ?></em></p>
                <p><span>���ڵأ�</span><em><?php echo $lawyer["country_cname"]; ?>,<?php echo $lawyer["city_cname"]; ?></em></p>
                <p><span>�칫��ַ��</span><em><?php echo $lawyer["street"]; ?></em></p>
                <p><span>��ϵ�绰��</span><em><?php echo $lawyer["telephone"]; ?></em></p>
            </div>

        </li>
        <li class="lawer_details">
            <h4 class="border_bottom">��ʦ���</h4>
            <div class="lawer_box">
                <p class="indent"><?php echo $lawyer["introduction"]; ?></p>
            </div>
        </li>
        <li class="business_rang">
            <h4 class="border_bottom">ҵ��Χ</h4>
            <div class="lawer_box"><p><?php echo $lawyer["business"]; ?></p></div>
        </li>
    </ul> <!--End lawer_info_box-->
    <div class="lawer_ask">
        <a href="">��ѷ�����ѯ</a>
    </div>
</div>
</body>
</html>
<?php
mysql_close($link);
?>