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
        "lawyer_name" => "测试用例",
        "office" => "未名空间",
        "country" => "China",
        "country_cname" => "中国",
        "state" => "",
        "city" => "BeiJing",
        "city_cname" => "北京市",
        "street" => "海淀区苏州街14号",
        "zip_code" => "100085",
        "telephone" => "010-00011000",
        "website" => "www.mitbbs.com",
        "introduction" => "未名空间(mitbbs.com) - 海外华人第一门户，创建于1996年，拥有数十万海外注册用户,为服务全球华人的综合性网站、人气最旺的网络社区",
        "approval_userid" => "1",
        "approval_state" => "北京市海淀区",
        "approval_time" => strftime("G-%m-%d", time()),
        "creator" => $creator,
        "creat_time" => strftime("%G-%m-%d", time()),
        "last_modify_time" => strftime("%G-%m-%d", time()),
        "identity_flag" => "S",
        "visit_num" => "8086",
        "business" => "海外华人,海外,华人,门户,社区,论坛,BBS",
        "picname" =>"/yimin/images/ChenLaw.jpg"
    );
}
// 将编码转为GBK
foreach ($lawyer as $index=>$each) {
    $tmp = "";
    $tmp = iconv("UTF-8", "GBK//IGNORE", $each);
    $lawyer[$index] = empty($tmp)?$each:$tmp;
}

?>
    <div class="ds_box border_bottom">
        <a href="" onclick="go_last_page();"><img src="img/btn_left.png" alt="bth_left.png"/></a>
        <?php echo $lawyer["lawyer_name"]; ?>律师个人信息
    </div>
    <ul class="lawer_info_box margin-bottom">
        <li class="lawer_base">
            <h4 class="border_bottom">基本信息</h4>
            <div class="lawer_box">
                <p><span>律师姓名：</span><em><?php echo $lawyer["lawyer_name"]; ?></em></p>
                <p><span>事务所名称：</span><em><?php echo $lawyer["office"]; ?></em></p>
                <p><span>所在地：</span><em><?php echo $lawyer["country_cname"]; ?>,<?php echo $lawyer["city_cname"]; ?></em></p>
                <p><span>办公地址：</span><em><?php echo $lawyer["street"]; ?></em></p>
                <p><span>联系电话：</span><em><?php echo $lawyer["telephone"]; ?></em></p>
            </div>

        </li>
        <li class="lawer_details">
            <h4 class="border_bottom">律师简介</h4>
            <div class="lawer_box">
                <p class="indent"><?php echo $lawyer["introduction"]; ?></p>
            </div>
        </li>
        <li class="business_rang">
            <h4 class="border_bottom">业务范围</h4>
            <div class="lawer_box"><p><?php echo $lawyer["business"]; ?></p></div>
        </li>
    </ul> <!--End lawer_info_box-->
    <div class="lawer_ask">
        <a href="">免费法律咨询</a>
    </div>
</div>
</body>
</html>
<?php
mysql_close($link);
?>