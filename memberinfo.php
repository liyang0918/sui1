<?php
    include_once(dirname(__FILE__)."/../../mitbbs_funcs.php");
    include_once("func.php");
    include_once("head.php");
        global $codeEnts;
        global $codeDecs;
        $lookupuser = array();
        $userinfo = array();
        $userid = $_GET["userid"];
        $conn = db_connect_web_wap();
        if (!$conn) {
              $m_error =1;
        } else if (!isset($userid) || trim($userid) == "") {
              $m_error =2;
        }else if(bbs_getuser($userid, $lookupuser) == 0) {
              $m_error =3;
        }else {
            $userinfo["nickname"] = iconv("GBK","UTF-8//IGNORE",zj_substr($lookupuser['username'],0,20)."...");
//            $userinfo["nickname"] = mit_iconv($lookupuser['username']);
            $userinfo["identity"] = mit_iconv(bbs_user_level_char($lookupuser["userid"]));
            $getbank = load_bank_web($conn, $lookupuser["num_id"]);
            $userinfo["allcash"] = $getbank[0]['all_newcash'];
            $userinfo["approvecash"] = $getbank[0]['new_cash'];
            $userinfo["loginNums"] = $lookupuser["numlogins"];
            $userinfo["postNums"] = $lookupuser["numposts"];
            $userexp = bbs_getuser_exp($lookupuser["userid"]);
            $userinfo["shenf"] = mit_iconv(bbs_user_level_char($lookupuser["userid"]));
            $userinfo["exp"] = $userexp;
            $userinfo["jingyjb"] = mit_iconv(bbs_getuser_expstr($lookupuser["userid"], $userexp));

            $headimg = BBS_HOME.'/pic_home/home/'.strtoupper(substr($lookupuser["userid"],0,1)).'/'.$lookupuser["userid"].'/headimg';
            if(!file_exists($headimg)){
                $userinfo["headimgURL"] ="";
            }else{
                $userinfo["headimgURL"] = "http://".$_SERVER['SERVER_NAME']."/picture/".strtoupper(substr($lookupuser["userid"],0,1))."/".$lookupuser["userid"]."/headimg";
            }
        }
        mysql_close($conn);
        echo "<div class=\"member_pic\"><img src=\"".$userinfo["headimgURL"]."\" width=\"100\"></div>";
//        echo "<div class=\"member_info\"><span>".$lookupuser["userid"]."</span>(<span>".$userinfo["nickname"]."</span>)</div>";
        echo "<div class=\"member_info\"><span>".$lookupuser["userid"]."</span></div>";
        echo "<div class=\"member_list\">";
        echo "<ul class=\"memberinfo_list\">";
        echo "<li><span class=\"infolist_left\">昵称</span>：<span class=\"infolist_right\">". $userinfo["nickname"]."</span></li>";
        echo "<li><span class=\"infolist_left\">身份</span>：<span class=\"infolist_right\">". $userinfo["shenf"]."</span></li>";
        echo "<li><span class=\"infolist_left\">伪币</span>：<span class=\"infolist_right\">".$userinfo["allcash"]."</span></li>";
        echo "<li><span class=\"infolist_left\">可用伪币</span>：<span class=\"infolist_right\">".$userinfo["approvecash"]."</span></li>";
        echo "<li><span class=\"infolist_left\">登录次数</span>：<span class=\"infolist_right\">".$userinfo["loginNums"]."</span></li>";
        echo "<li><span class=\"infolist_left\">发文数</span>：<span class=\"infolist_right\">".$userinfo["postNums"]."</span></li>";
        echo "<li><span class=\"infolist_left\">经验值</span>：<span class=\"infolist_right\">".$userinfo["exp"]."（".$userinfo["jingyjb"]."）</span></li>";
        echo "<li><span class=\"infolist_left\">说明档</span>：<span class=\"infolist_right\">".$userinfo["desContent"]."</span></li>";
        echo "</ul></div>";
        ?>

<div class="ds_box border_bottom">
    <a href="search_result_member.html"><img src="img/btn_left.png" alt="bth_left.png"/></a>
    个人信息
</div><!--<End ds_box-->
<div class="jy_generalInfo border_bottom">
    <div class="gen_img"><img src="img/beauty.png" alt="beauty.png"/></div>
    <div class="gen_info">
        <h4>jameshxz</h4>
        <span>昵称：三桂</span>
    </div>
</div><!--End jy_generalInfo-->
<ul class="jy_gen_group">
    <li><span>身份</span><em>站务</em></li>
    <li><span>经验值</span><em>49455（开国大佬）</em></li>
    <li><span>登陆次数</span><em>66416</em></li>
    <li><span>发文数</span><em>88888</em></li>
    <li><span>伪币</span><em>667788</em></li>
    <li><span>可用伪币</span><em>990000</em></li>
</ul><!--End jy_gen_group-->

		</section>
		<!--底部开始-->
    	<footer>
        <ul>
            <li class="footer-li01">mitbbs.com</li>
            <li class="footer-li03"><a href="/wap/download.php">客户端</a></li>
            <li class="footer-li02"><a href="http://www.mitbbs.com">电脑版</a></li>
        </ul>
   		</footer>
   		<!--底部结束-->
	</div>

</body>
</html>