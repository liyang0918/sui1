<?php
require_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
require_once("func.php");
include_once("head.php");
//get part
if(!empty($_GET["area"]))
    $eare_code=$_GET["area"];
            $area_arr=array();
$conn=db_connect_web();
            $get_countries_sql="select country_code,en_name from countries order by en_name";
            if (!$conn) {
                wpa_error_quit('数据库连接失败: ');
            } else {
                $result = mysql_query($get_countries_sql);
                while($row = mysql_fetch_array($result))
                {
                    $country_sel;
                    if($row['country_code']==$area_code){
                        $area_arr[]="<option value=\"".$row['country_code']."\" selected>".$row['en_name']."</option>";
                    }else
                        $area_arr[]="<option value=\"".$row['country_code']."\" >".$row['en_name']."</option>";
                }
            }
mysql_close($conn);
?>
<div id="registe" >
    <p align="center" style="width: 100%">欢迎您注册未名空间</p>
    <td><select name="reg_country" class="input" id="reg_country_id" onblur="check_country(this)">
            <option value="Not set" selected>-- 国家 --</option>
            <?php
                foreach($area_arr as $country){
                    echo $country;
                }
            ?>
            </select>
    </td>
    <td width="40%"><input maxlength="12" size="30" id="phone_num" name="phone_num" placeholder="手机号码" onblur="check_phone(this)"></td>
    <td><input size="6" maxlength="4" name="confirm_str" id="confirm_str_id"><input type="button" disabled="disabled" id="send_sms_btn" onclick="ready_for_sms(this)" value="获取短信验证码"> </td>
    </tr>
    <td width="40%"><input maxlength="12" size="30" id="user_name_id" name="userid" placeholder="ID:字母开头，数字置后,3-12字符"
                       value="<?php if(isset($_GET['u'])) echo $_GET['u']; else echo ($_POST["userid"]);?>"
                       onblur="check_username('user_name_id','user_name_help_id')" ></td>
    <td><input maxlength="18" size="30" id="password" name="passwd" type="password" placeholder="请输入密码"></td>
    <td><input type="button" onlick="passwd_show()" value="密码可见"></td>
</div>
<tr align="center">
    <p>注册即同意</p><td height="100" colspan="2" class="logo-bg"><p><a href="/mitbbs_register.php" class="headlink">未名空间用户使用条款</a>
<input id="submit_regisger" name="submit" disabled="disabled" value="确认注册" type="submit">
<script type="text/javascript" src="js/funs.js"></script>
<?php
include_once("foot.php");
?>
