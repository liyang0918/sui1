<?php
	/**
	 * This file lists boards to user.
	 * $Id: bbsupload.php,v 1.1.2.2 2015/09/24 11:10:57 bbsroot Exp $
	 */
	$filenames=array();
	$filesizes=array();
	$limit_file_type=array("image/png", "image/x-png", "image/jpeg", "image/pjpeg", "image/bmp", "image/gif", "image/vnd.microsoft.icon", "image/tiff", "image/svg+xml");
	global $errno;
	@$action=$_POST["act"];
    	$totalsize=0;
	$allnames="";
	require("mitbbs_funcs.php");

	//检查上传到服务器端的文件
	function checkFileOnServer($filename)
	{
		global $limit_file_type;

		if(!is_file($filename))
	    	   return -1; //找不到文件
	
		$commend = "file --mime-type -b $filename";
		$info = @system($commend);
		$in_array = 0;
	
		if(in_array($info, $limit_file_type))
		{
	    	    $in_array = 1;
		}
		//else
	  	    //@unlink($filename);

		return $in_array;
	}
	//End

	if ($loginok !=1 )
		html_nologin();
	else
	{
		if (!strcmp($currentuser["userid"],"guest"))
		{
		    echo '<script language="javascript">alert(\'您还没有登录或者发呆时间过长已被系统清除，暂时不能粘贴附件，系统将关闭此窗口\'); this.window.close();</script>';
		    mitbbs_html_error_quit("匆匆过客，不能粘贴附件");	
		}
		html_init("gb2312","粘贴附件");
		$attachdir=bbs_getattachtmppath($currentuser["userid"] ,$utmpnum);

		@mkdir($attachdir);
		if ($action=="delete") {
			if(!securityCheck()){
				mitbbs_html_error_quit("安全验证失败!");
				
			}
			@$act_attachname=$_POST["attachname"];
			$fp1=@fopen($attachdir . "/.index","r");
			if ($fp1!=FALSE) {
				$fp2=@fopen($attachdir . "/.index2","w");
				while (!feof($fp1)) {
					$buf=fgets($fp1);
					if (FALSE==($pos=strpos($buf," " . $act_attachname . "\n"))) {
						fputs($fp2,$buf);
					} else {
						@unlink($attachdir . "/" . substr($buf, 0 ,$pos-1));
					}
				}
				fclose($fp1);
				fclose($fp2);
				@unlink($attachdir . "/.index");
				@rename($attachdir . "/.index2",$attachdir . "/.index");
			}
		} else if ($action=="add") {
			if(!securityCheck()){
				mitbbs_html_error_quit("安全验证失败!");				
			}	
			@$errno=$_FILES['attachfile']['error'];
			if ($errno==UPLOAD_ERR_OK) {
				$buf=$_FILES['attachfile']['name'];
				
				$tok = strtok($buf,"/\\");
				$act_attachname="";
				while ($tok) {
					$act_attachname=$tok;
					$act_attachname = str_replace(" ", "_", $act_attachname);
    					$tok = strtok("/\\");
				}
				//$act_attachname=strtr($act_attachname,$filename_trans);
				//$act_attachname=substr($act_attachname,-60);
				if ($act_attachname!="") {
			        	if ($_FILES['attachfile']['size']>ATTACHMAXSIZE) 
							$errno=UPLOAD_ERR_FORM_SIZE;
					else if(!in_array($_FILES['attachfile']['type'], $limit_file_type))
						$errno= 1000;
				} else
					$errno=100;
			}
		}
		$filecount=0;
		/*
		if ($handle = @opendir($attachdir)) {
                    while (false != ($file = readdir($handle))) { 
                        if ($file[0]=='.')
                            continue;
                    	$filenames[] = $file;
                    	$filesizes[] = filesize($attachdir . "/" . $file);
                    	$totalsize+=$filesizes[$filecount];
                    	$filecount++;
			$allnames = $allnames . $file . ";";
                    }
                    closedir($handle);
                }*/
                if (($fp=@fopen($attachdir . "/.index","r"))!=FALSE) {
                	while (!feof($fp)) {
	                	$buf=fgets($fp);
	                	$buf=substr($buf,0,-1); //remove "\n"
	                	if ($buf=="")
	                		continue;
	                	$file=substr($buf,0,strpos($buf,' '));
	                	if ($file=="")
	                		continue;
	                	$name=strstr($buf,' ');
						$name=substr($name,1);
                    	$filenames[] = $name;
	                	$filesizes[$name] = filesize($file);
                    		$totalsize+=$filesizes[$name];
                    		$filecount++;
	                	$allnames = $allnames . $name . ";";
	                }
			fclose($fp);
                }
?>
<script language="JavaScript">
<!--

function SymError()
{
  return true;
}

window.onerror = SymError;

//-->
</script>

<style type="text/css">.txt01 {  font-family: "宋体"; font-size: 12px}
.txt02 {  font-family: "宋体"; font-size: 12px; color: #770260}
.txt-b01 { font-family: "宋体"; font-size: 12px; color: #770260 ; background-color: #C7A9EF; border-color: #C7A9EF #5C034A #551A49 #C9B3E7; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px}
.txt-b02 { font-family: "宋体"; font-size: 12px; color: #000000; background-color: #F5EAF9}
.txt-b03 { font-family: "宋体"; font-size: 12px; color: #770260; background-color: #F5EAF9}
.form01 {  font-family: "宋体"; font-size: 12px; height: 20px; letter-spacing: 3px}
.form02 {  font-size: 12px}
a:hover {  color: #FF0000; text-decoration: none}
.title01 { font-family: "黑体"; font-size: 16px; color: #770260 ; letter-spacing: 5px}
</style>

<body bgcolor="#FFFFFF">
<?php
                if ($action=="add") {			
                	if ($_FILES['attachfile']['size']+$totalsize>ATTACHMAXSIZE) {
                		unlink($attachdir . "/" . $act_attachname);
                		unset($filenames,$act_attachname);
                		$errno=UPLOAD_ERR_FORM_SIZE;
                	}
                	if ($filecount>ATTACHMAXCOUNT) {
                		echo "附件个数超过规定！";
                		
			} else
                	switch ($errno) {
                	case UPLOAD_ERR_OK:
						@mkdir($attachdir);
						$tmpfilename=tempnam($attachdir,"att");
						if (isset($filesizes[$act_attachname])) {
							echo "存在同名文件！";
						} 
						else
						{
							if (is_uploaded_file($_FILES['attachfile']['tmp_name'])) 
							{
				    				if(move_uploaded_file($_FILES['attachfile']['tmp_name'],$tmpfilename))
								{
                		         			    /* 填写 .index*/		
								    $check = checkFileOnServer($tmpfilename);
								    if($check <= -1)
								    {
									echo '文件上传到服务器失败！';
									break;
								    }
								    else if($check == 0)
								    {
									echo '抱歉，服务器不接受此类型的文件';
									@unlink($tmpfilename);
									break;
								    }
								
								    if (($fp=@fopen($attachdir . "/.index", "a"))==FALSE) 
								    {
                							@unlink($attachdir . "/" . $act_attachname);
                						    } 
								    else 
								    {
                						       fputs($fp,$tmpfilename . " " . $act_attachname . "\n");
                						       fclose($fp);
                						       echo "类型文件上载成功！";
	                    					       $filenames[] = $act_attachname;
												   clearstatcache();
		                	    			       $filesizes[$act_attachname] = filesize($tmpfilename);
	                    					       $totalsize+=$filesizes[$act_attachname];
	                    					       $filecount++;
		                				       $allnames = $allnames . $act_attachname . ";";
                						       break;
                						    }
								}
								else
								   echo '上传文件失败！';
							}
							echo "保存附件文件失败！";
						}
						break;
                	case UPLOAD_ERR_INI_SIZE:
                	case UPLOAD_ERR_FORM_SIZE:
                		echo "文件超过预定的大小" . sizestring(ATTACHMAXSIZE) . "字节";
                		break;
                	case UPLOAD_ERR_PARTIAL:
                		echo "文件传输出错！";
                		break;
                	case UPLOAD_ERR_NO_FILE:
                		echo "没有文件上传！";
                		break;
			case 1000:
				echo "抱歉，服务器只接受图片类型的附件";
				break;
                	case 100:
                		echo "无效的文件名！";
				break;
                	default:
                		echo "未知错误";
                	}
                	echo "<br />";
                }
?>
<script language=javascript>
function addsubmit() {
  var e1,e3;
  var pos=0;
  var obj=document.forms[0].elements["attachfile"];
  if (!obj) return true;
  if (obj.value == ""){
	alert('您还没选择上传的附件');
 	return false;
  } else {
        document.forms[0].paste.value='附件上载中，请稍候...';
        document.forms[0].paste.disabled=true;
        document.forms[0].submit();
  }
}

function deletesubmit() {
  document.forms[1].attachname.value=document.forms[1].elements["removefile"].value;
  document.forms[1].submit();
}

function clickclose() {
	if (document.forms[0].elements["attachfile"].value == "") return window.close();
	else if (confirm("您填写了文件名，但没有上载。是否确认关闭？")==true) return window.close();
	return false;
}
<!--
        opener.document.forms["postform"].elements["attachname"].value = <?php echo "\"$allnames\""; ?>;
-->
</script>
<form name="addattach" method="post" ENCTYPE="multipart/form-data" align="left" action="bbsupload.php">
	<?php addSecurityCheck();  ?>
  <table border="0" cellspacing="2" class="txt-b03">
    <tr> 
      <td height="2"> 
        <table border="0">
          <tr> 
            <td colspan="2" height="13" class="txt02"> <font>1、点“<font color="#FF0000">浏览</font>”按钮，找到您所要粘贴的附件文件：</font> 
            </td>
          </tr>
<?php
	if ($filecount<ATTACHMAXCOUNT) {
?>
          <tr> 
            <td colspan="2" height="25" class="form01"> 
              <input type="hidden" name="MAX_FILE_SIZE" value=<?php echo(ATTACHMAXSIZE);?>>
              <input type="file" name="attachfile" size="20" value class="form02">
            </td>
          </tr>
<?php
    } else {
?>
          <tr> 
            <td colspan="2" height="25" class="form01">附件个数已满！</td>
              <input type="hidden" name="attachfile" size="20" value class="form02">
          </tr>
<?php
	}
?>
        </table>
      </td>
    </tr>
    <tr> 
      <td> 
        <table border="0" width="500">
          <tr> 
            <td width="420" class="txt02">2、选定一个文件后，点“<font color="#FF0000">粘贴</font>”按钮，将该文件贴到文章上:</td>
            <td width="80"><input type="hidden" name="act" value="add"><input type="button" width="61"
          height="21" value="粘贴" name="paste" border="0" onclick="addsubmit();" class="form01" >
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td class="txt02"> 3、如有多个附件文件要粘贴，重复1、2步骤；</td>
    </tr>
    <tr> 
      <td> 
        <table border="0" width="500">
          <tr> 
            <td width="420" class="txt02">4、所有的附件粘贴完毕后，点“<font color="#FF0000">完成</font>”按钮返回。</td>
            <td width="80"> 
              <input type="button"  width="61"
          height="21" value="完成" border="0" onclick="return clickclose()" class="form01">
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form><form name="deleteattach" ENCTYPE="multipart/form-data" method="post" align="left" action="bbsupload.php"> 
<table border="0" width="500" cellspacing="2">
	<?php addSecurityCheck();  ?>
  <tr> 
    <td width="423"><font color="#804040"> 
      <select name="removefile" cols="40" size="1" class="form02">
<?php
      foreach ($filenames as $file)
        printf("<option value=\"%s\">%s</option>",$file,$file);
?>
      </select>
      </font></td>
    <td width="77"><input type="hidden" name="act" value="delete"><input type="hidden" name="attachname"><input type="button" width="61" height=21 value="删除" onclick="return deletesubmit()" class="form01">
    </td>
  </tr>
</table>
  <table border="0" cellspacing="2" class="txt-b03">
    <tr><td>
        <table border="0" width="500">
          <tr> 
            <td width="200" class="txt01" align="right">现在附件文件总量为：</td>
            <td width="300" class="txt01"><font color="#FF0000"><b>&nbsp;&nbsp;<?php echo sizestring($totalsize); ?>字节</b></font></td>
  	</tr>
          <tr> 
            <td width="200" class="txt01" align="right">注意：附件总容量最大不能超过：</td>
            <td width="300" class="txt01"><font color="#FF0000"><b>&nbsp;&nbsp;<?php echo sizestring(ATTACHMAXSIZE); ?>字节</b></font></td>
  	</tr>
  	</table>
  </td></tr>
</table>
<table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
</table>
<div align="center"> 
  <center>
    <p>　</p>
  </center>
</div></form>
</body>
</html>
<?php
	} // nologin else
?>
