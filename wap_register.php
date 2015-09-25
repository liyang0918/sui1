<?php
session_start();
require_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
require_once("func.php");
include_once("head.php");
//register part judge
function isalpha($uid)
{
    if(strcmp($uid,"A")==-1||strcmp($uid,"z")>0)
        return FALSE;
    else
        return TRUE;
}

function isnum($uid)
{
    if(strcmp($uid,"0")==-1||strcmp($uid,"9")>0)
        return FALSE;
    else
        return TRUE;
}
if($_POST["submit"]) {
    $reg_phone = trim($_POST["phone_num"]);
    $user_id = trim($_POST["user_id"]);
    $password = trim($_POST["password"]);
    $country = trim($_POST["reg_country"]);
    $confirm = trim($_POST["confirm_str"]);

    $nickname = '';
}
if($_POST["submit"]) {
    if (!strcmp($user_id, "")) {
        $error[$i++] = "用户代号(ID)不能为空";
    } else if (!isalpha($user_id[0])) {
        $error[$i++] = "用户代号必须以字母开头";
    } else if (strlen($user_id) > 12) {
        $error[$i++] = "用户代号长度不得大于12个字符";
    } else {
        for ($loop; $loop < strlen($user_id); $loop++) {
            if ($loop < strlen($user_id) - 1)
                if (isnum($user_id[$loop]) && isalpha($user_id[$loop + 1])) {
                    $error[$i++] = "帐号必须由英文字母或数字组成，并且第一个字符必须是英文字母,数字必须置后!";
                    break;
                }
        }
    }

    if (!strcmp($password, ""))
        $error[$i++] = "密码不能为空";
    //html_error_quit("密码不能为空");
    if (!strcmp($user_id, $password))
        $error[$i++] = "密码不可以与用户名相同";
    if (strlen($password) < 6 or strlen($password) > 18) {
        if (strcmp($password, ""))
            $error[$i++] = "密码长度为6-18个字符";
        //html_error_quit("密码长度为6-18个字符");
    }

    $ph_ret = get_ar_ph_user($reg_phone);
    if (empty($reg_phone)) {
        $error[$i++] = "请填写注册手机号码！";
    }
    if ($ph_ret != 0) {
        $error[$i++] = "请更换您的注册电话号码，该号码已经注册过！";
    }
    if ($_SESSION['phone_num_final'] != $country . $reg_phone) {
        $error[$i++] = "短信地区+电话号与注册不符";
    }
    if (strcmp($confirm, $_SESSION['confirm_num']) != 0 || empty($confirm) || time() - $_SESSION['publish_time'] > 600) {
        $error[$i++] = "验证码错误或已经过期,请重新填写或重新申请验证码";
    }

    if ($i == 0) {
        //$ret=bbs_createnewid($userid,$password,$nickname,$reg_email,$reuser);
        //$ret=bbs_createnewid_ph($userid,$password,$nickname,$area_code,$reg_phone,$reuser);
        $ret = bbs_createnewid_ph($user_id, $password, $nickname, $country, $reg_phone, $reg_email, $reuser);
        //var_dump($ret);
        switch ($ret) {
            case 0:
                break;
            case 1:
                if (strcmp($userid, "")) {
                    $error[$i++] = "用户名有非数字字母字符或者首字符不是字母或者数字没有置后";
                    //html_error_quit("用户名有非数字字母字符或者首字符不是字母或者数字没有置后!");
                    break;
                }
            case 2:
                if (strcmp($userid, "")) {
                    $error[$i++] = "用户名至少为两个字母";
                    //html_error_quit("用户名至少为两个字母!");
                    break;
                }
            case 3:
                if (strcmp($nickname, "")) {
                    $error[$i++] = "用户名中包含系统用字或不雅用字";
                    //html_error_quit("系统用字或不雅用字!");
                    break;
                }
            case 4:
                if (strcmp($userid, "")) {
                    $error[$i++] = "该用户名已经被使用";
                    //html_error_quit("该用户名已经被使用!");
                    break;
                }
            case 5:
                if (strcmp($userid, "")) {
                    $error[$i++] = "用户名太长,最长12个字符";
                    //html_error_quit("用户名太长,最长12个字符!");
                    break;
                }
            case 6:
                if (strcmp($password, "")) {
                    $error[$i++] = "密码太长,最长18个字符";
                    //html_error_quit("密码太长,最长18个字符!");
                    break;
                }
            case 8:
                if (strcmp($userid, "")) {
                    $error[$i++] = "你不能使用此帐号注册,此名称为本站保留字";
                    break;
                }
            case 10:
                if ($i == 0) {
                    $error[$i++] = "系统错误,请与系统管理员SYSOP联系";
                    //html_error_quit("系统错误,请与系统管理员SYSOP联系.");
                    break;
                }
            case 11:
                if ($i == 0) {
                    $error[$i++] = "您输入的电话号码非法或已被其他用户占用,请输入其它号码,或使用邮箱注册";
                    //html_error_quit("系统错误,请与系统管理员SYSOP联系.");
                    break;
                }
            case 12:
                if ($i == 0) {
                    $error[$i++] = "该IP地址注册用户比较频繁，请稍后再试试您的运气！";
                    break;
                }
            case 19:
                if ($i == 0) {
                    $error[$i++] = "您输入的邮件地址非法或已被其他用户占用,请输入其它邮件地址";
                    break;
                }
            default:
                if ($i == 0) {
                    $error[$i++] = "注册ID时发生未知的错误";
                    //html_error_quit("注册ID时发生未知的错误!");
                    break;
                }
        }
    }
}
$i=0;
    if($i==0&&!empty($_POST["submit"])){
        $conn=db_connect_web();
        $num=get_userid3($userid,$conn);
        @mysql_close($conn);
        save_num_www($user_id,$num);

        if(get_user_entid($currentuser["num_id"])==0)
            levelup_www($user_id);

        /* To send HTML mail, you can set the Content-type header. */
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=gb2312\r\n";

        /* additional headers */
        $headers .= "From:未名空间<id@{$domains_info['BBSDOMAIN_US']}>\r\n";


//        settype($year,"integer");
//        settype($month,"integer");
//        settype($day,"integer");
//        settype($m_register,"bool");
        //$test=save_information($userid,$reg_email,$gender,$year,$month,$day,$education,$profession,$address);
//        if(!$m_register)
//            $mobile_phone="";
//        html_init("gb2312");
//        save_email_www($userid,$reg_email);
        echo('
		<div class="reg_succeed">
			<p>注册成功</p>
			<form action="login.php" method="post" name="loginform" >
                                <span><a href="#" onclick="javascript:loginform.submit()">现在登录进站</a></span>
                        	<input type="hidden" name="logintype" value="reg">
                                <input type="hidden" name="id" value="'.$user_id.'">
                                <input type="hidden" name="passwd" value="'.$password.'">
                        </form>
		</div>
	');
    }

if ($i!=0 ||empty($_POST["submit"])){
//end register
//get part
    if (!empty($_GET["area"]))
        $eare_code = $_GET["area"];
    $area_arr = array();
    $conn = db_connect_web();
    $get_countries_sql = "select country_code,en_name from countries order by en_name";
    if (!$conn) {
        wpa_error_quit('数据库连接失败: ');
    } else {
        $result = mysql_query($get_countries_sql);
        while ($row = mysql_fetch_array($result)) {
            $country_sel;
            if ($row['country_code'] == $area_code) {
                $area_arr[] = "<option value=\"" . $row['country_code'] . "\" selected>" . $row['en_name'] . "</option>";
            } else
                $area_arr[] = "<option value=\"" . $row['country_code'] . "\" >" . $row['en_name'] . "</option>";
        }
    }
    mysql_close($conn);
?>
    <div id="registe" class="reg_title">
        <h3>欢迎您注册未名空间</h3>
    </div>
    <div class="reg_wrap">
    <form action="wap_register.php" method="post">
        <div id="in_info" class="reg_box">
		<span>账号：</span>
		<input class="reg_input" maxlength="12" size="30" id="user_id" name="user_id" placeholder="字母开头，数字置后,3-12字符" value="<?php if (isset($_GET['u'])) echo $_GET['u']; else echo($_POST["user_id"]);?>" onblur="check_username(this)">
		<p id="user_err" hidden="hidden" class="error_tips">输入错误的提示信息</p>
        </div>
        <div id="pw_info" class="reg_box">
		<span>密码：</span>
		<input class="reg_input" maxlength="18" size="30" id="password" name="password" type="password" onblur="check_password()" placeholder="6-18位字母数字组合">
		<a type="button" id="passwd_btn" name="passwd_btn" onclick="passwd_show()">显示</a>
		<p id="password_err" hidden="hidden" class="error_tips">输入错误的提示信息</p>
        </div>
        <div id="country_info" class="reg_box">
		<span>选择国家：</span>
		<select name="reg_country" class="reg_input" id="reg_country_id" onblur="check_country(this)">
                <option value="Not set" selected>-- 国家 --</option>
                    <?php
                    foreach ($area_arr as $country) {
                        echo $country;
                    }
                    ?>
           	</select>
	</div>
	<div id="phone-info" class="reg_box">
           	<span>手机号码：</span>
		<input class="reg_input" maxlength="12" type="text" size="30" id="phone_num" name="phone_num" placeholder="" onblur="check_phone(this)" onPropertyChange="webchange();">
		<p id="phone_err" hidden="hidden" class="error_tips">输入错误的提示信息</p>
        </div>
        <div id="confirm" class="reg_box">
	    	<span>验证码：</span>
		<input class="reg_input reg_code" size="6" maxlength="4" name="confirm_str" id="confirm_str_id" placeholder="4位数字" onblur="check_confirm(this)"><input class="code_btn" type="button" disabled="disabled" id="send_sms_btn" onclick="ready_for_sms(this)" value="点此获取验证码">
		<p id="confirm_err" hidden="hidden" class="error_tips">输入错误的提示信息</p>
        </div>
        <div id="reg_read" classs="reg_box">
            <p class="reg_agree">注册即同意<a href="/mitbbs_register.php" class="headlink">未名空间用户使用条款</a></p>
        </div>
<?php
    if($i!=0){
    ?>
    <div id="registe" class="reg_error">
        <p>
            <?php
            $err_cnt=1;
            foreach($error as $err ){
                echo $err_cnt."." .$err."<br>";
                $err_cnt++;
	}
?>
        </p>
    </div>
<?php
}
?>
        <div id="sub_part" class="reg_box reg_btn">
            <input id="submit_regisger" name="submit" value="确认注册" type="submit">
        </div>
    </form>
    </div>



    <script type="text/javascript" src="js/funs.js"></script>
    <script type="text/javascript" src="js/send_sms.js"></script>
    <script type="text/javascript">
        function immediately() {
            var element = document.getElementById("phone_num");
            if ("\v" == "v") {
                element.onpropertychange = webChange;
            } else {
                element.addEventListener("input", webChange, false);
            }
        }
        function webChange() {
            //if(!element.value.match('/^\d+$/')){element.value="";};
            //alert(element.value.match(/^\d+$/'));
            var obj = document.getElementById('phone_num');
            var reg = /^[0-9]$/;

            if (!reg.test(obj.value.substr(obj.value.length - 1, obj.value.length)))
                obj.value = obj.value.substr(0, obj.value.length - 1);
        }
        function show_reg_error(){
            var arr_js = <?php echo implode(",",$error);?>;
        }
    </script>
    <script type="text/javascript">
        immediately();
    </script>
<?php
}
include_once("foot.php");
?>
