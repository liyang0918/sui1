
var httpc ;
var httpn ;
var confirm_num ;
var ret_t;
var ret_o;
//check phonum when changed
// reset passwd with short message
function get_passwd_sms(obj) {
	var user_id = document.getElementById('userid_id').value;
	var phone_num_input= document.getElementById('phone_id').value;
	var phone_num_get;
	if(user_id.length<1) {
		alert("����д��Ҫ�һص��û�ID!");
		return;
	}
	if(phone_num_input==0) {
		alert("������ע��ʱ�ĵ绰����!");
		return;
	}
	var reg = /^\d/;
		var myAjax2 = new Ajax.Request("/check/get_ph_user.php",
		{
				method: 'get'
				, parameters: "user_id="+user_id
				, asynchronous: false
				, onSuccess : function (ret) {
				phone_num_get=ret.responseText;
				}
				, onFailure: function (ret) {
					alert("��ȡ�û��绰����ʧ��!");
				}
		});
	var country_arr=phone_num_get.split("|");
	var country_code=country_arr[0];
	phone_num_get=country_arr[1];
	if(phone_num_get==0) {
		alert("�����ڵ��û����ߵ绰��!");
		return ;
	}
	if(phone_num_input!=phone_num_get){
		alert("�û�����绰�Ų���!");
		return ;
	}
//������������֤��
	var myAjax = new Ajax.Request("/check/create_confirm.php",
		{
			method: 'get'
			, parameters: null
			, asynchronous: false
			, onSuccess: function (ret) {
			confirm_num = ret.responseText;
			}
			, onFailure: function (x) {
			alert("fail to get confirm_num from server ;")
			}
		});
//���Ͷ��� �һ�����
	var phone_num_final=country_code+phone_num_get;
	if(ret_t==undefined);
	btn_dcount(obj,60);
	var myAjax1 = new Ajax.Request("/check/send_templatesms.php",
		{
			method: 'post'
			, parameters: "phone_num_final="+phone_num_final+"&temp_id=21332&country_code="+country_code
			, asynchronous: false
			, onSuccess: function (ret) {
				if (ret.responseText.replace(/^\s+|\s+$/g, "" ) == true ) {
					//document.getElementById('u_p_id').innerHTML="<span style='color:green'>check the number you input !</span>";

					alert("��֤���ѳɹ����ͣ������");
				} else if (ret.responseText.replace( /^\s+|\s+$/g, "" )=='160013') {
					alert("����ĵ绰�����ʽ");
				} else {
					//document.getElementById('userid_id').innerHTML="<INPUT name=\"userid\" id=\"userid_id\" size=\"30\" maxLength=12 value=\"xxx\">";
					alert("["+ret.responseText+"]");
				}
			}
			, onFailure: function (x) {
			if (lang == 1)
				alert("check you phone number !");
			else
				alert("���������ֻ���!");
			return;
		}
		});
}
// register use phone number set short message
function ready_for_sms(obj) {
	//account infomation
	var lang = 0;
	var phone_num = document.getElementById('phone_num');
	var country_code = document.getElementById('reg_country_id');
	var user_id = document.getElementById('user_id');
	var phone_num_final = "";
	//check user's information legal
	if (user_id.value == '') {
			alert("�û���id����Ϊ��!");
	}
	if (country_code.value == 'Not set') {
		if (lang == 1)
			alert("Please select country!");
		else
			alert("��ѡ��һ�����һ����!");
		return;
	}
	if (phone_num.value == '') {
		if (lang == 1)
			alert("Phone number can't be null!");
		else
			alert("�ֻ����벻��Ϊ��!");
		return;
	}
	if(/^\d+$/.test(phone_num.value))
	{
	} else {
		if (lang == 1)
			alert("Phone number can't be char!");
		else
			alert("�ֻ�������Ϊ����!");
		return;
	}

	if (country_code.value == 'other') {
		if (lang == 1)
			alert("Please select country!");
		else
			alert("ϵͳ��֧���ⲿͨѶ!");
		return;
	}
	//split joint phone number for international
	phone_num_final = country_code.value + phone_num.value;
	//send time counter for 60mins
	if(ret_t==undefined);
	btn_dcount(obj,60);
	//create confirm_num save and send to user

	var myAjax = new Ajax.Request("request/create_confirm.php",
		{
			method: 'get'
			, parameters: null
			, asynchronous: false
			, onSuccess: function (ret) {
			confirm_num = ret.responseText
			//alert(confirm_num);
		}
			, onFailure: function (x) {
			alert("fail to get confirm_num from server ;")
		}
		});
	//send phone_num_final to supplier with template defined
	//send_templatesms(phone_num_final);
	var myAjax1 = new Ajax.Request("request/send_templatesms.php",
		{
			method: 'post'
			, parameters: "phone_num_final="+phone_num_final+"&temp_id=21332&country_code="+country_code.value
			, asynchronous: false
			, onSuccess: function (ret) {
			if (ret.responseText == true ) {
				Alert("���ŷ��ͳɹ�,����10����֮������")
			}
		}
			, onFailure: function (x) {
				alert("���������ֻ���!");
			return;
		}
		});


// send template message
}
//function send_templatesms(phone_num,confirm_num){
//
//	// send require to server :sendboxapp for test only ,substitution app when normal
//	// https://server/{SoftVersion}/Accounts/{accountSid}/{func}/{funcdes}?sig={SigParameter}
//	// SigParameter should be transfer by MD5(AccountId + authorization token + datetime with specify format )
//	//
//	// head setting
//	// Accept:application/xml;
//	// Content-Type:application/xml;charset=utf-8;
//	// Content-Length:256;
//	// Authorization: transfer by Base64(AccountId + ":" + datetime formated )
//	var url_base = "https://sandboxapp.cloopen.com:8883/2013-12-26/Accounts/aaf98f894a85eee5014a998477fe08f6/SMS/TemplateSMS?sig=" ;
//	var datestr=new Date().Format("yyyyMMddhhmmss");
//	var sigpmr = MD5("aaf98f894a85eee5014a998477fe08f6" + "fd7b80ae865343eaa7d6e7fe82bd018f" + datestr);
//	var url = url_base + sigpmr;
//	var bs64= new Base64();
//	var auth="aaf98f894a85eee5014a998477fe08f6:" + datestr;
//	var auth_64=bs64.encode(auth);
//	httpc = getHTTPObject();
//
//	httpc.open("POST", url, true);
//
//	// ���ð�ͷ
//	httpc.setRequestHeader("Accept", "application/xml");
//	httpc.setRequestHeader("Content-Type", "application/xml;charset=utf-8");
//	httpc.setRequestHeader("Content-Length", "256");
//	httpc.setRequestHeader("Authorization", auth_64);
//
//	// ���ð���
//	alert(confirm_num);
//	var body = "<TemplateSMS> <to>" + phone_num + "</to> <appId>aaf98f894a85eee5014a998a9ee208fa</appId> <templateId>10614</templateId> " +
//		"<datas> <data>" + confirm_num+ "</data> <data>1</data> </datas> </TemplateSMS>";
//	alert(url);
//	alert(body);
//	httpc.onreadystatechange = response_send_sms();
//	httpc.send(body);
//
//}
// ���Ͷ�����֤�� ���Ͷ��ŵ�ָ���绰 �ݲ���
//function send_sms(obj){
//  // ��ȡ���ҡ��ֻ�����
//	var lang=0;
//	//alert(window.location.pathname);
//	//alert(confirm_num);
//	btn_dcount(obj,30);
//	// ���ɶ������벢���浽���ݿ�
//// Ȼ��ʼ��ȡ��Ҫ��Login/Weapon/W�ĵ�һ���ڵ������ֵ
//	var userInfo = xmlDoc.createElement("user_info")
//	userInfo.setAttribute("ID",user_id.value);
//	userInfo.setAttribute("PASSWD",user_pw.value);
//	userInfo.setAttribute("PHONE",phone_num_final);
//
//	xmldoc.save('/xml/register.xml');
//	alert("saved ");
//	var url_locale = "/unknown.xml";
//	var httpn = getHTTPObject();
//	httpn.open("GET", url_locale, true);
//	httpn.onreadystatechange = function(){response_save_sms_code(phone_num_final)};
//	httpn.send(null);
//}

//function response_save_sms_code(phone_num)
//{
//	alert("j12jlkjkl");
//	alert(httpn.readyState);
//
//	if (httpn.readyState==4) {
//		alert("kjkl");
//		if (httpn.status == 200) {
//			// ׼�����Ͷ���
//	 		var obj=httpc.responseXML ;
//			var errnos = obj.getElementsByTagName("errno") ;
//			var errno = errnos[0].firstChild ;
//			alert(errno);
//			return ;
//			if (errno != 0) {
//		 		// ���Ͷ��������ַ
//		 		var url_base = "https://sandboxapp.cloopen.com:8883/2013-12-26/Accounts/aaf98f894a85eee5014a998477fe08f6/SMS/TemplateSMS?sig=" ;
//				var datestr = new Date.Format("yyyyMMddhhmmss");
//		 		var sigpmr = MD5("aaf98f894a85eee5014a998477fe08f6" + "fd7b80ae865343eaa7d6e7fe82bd018f" + datestr);
//		 		var url = url_base + sigpmr;
//
//		 		var auth = Base64("aaf98f894a85eee5014a998477fe08f6:" + datestr);
//         httpc = getHTTPObject();
//
//		 		httpc.open("POST", url, true);
//
//		 		// ���ð�ͷ
//		 		httpc.setRequestHeader("Accept", "application/xml");
//		 		httpc.setRequestHeader("Content-Type", "application/xml;charset=utf-8");
//		 		httpc.setRequestHeader("Authorization", auth);
//
//		 		// ���ð���
//		 		var body = "<TemplateSMS> <to>" + phone_num + "</to> <appId>aaf98f894a85eee5014a998a9ee208fa</appId> <templateId>10614</templateId> " +
//					"<datas> <data>" + errno + "</data> <data>1</data> </datas> </TemplateSMS>";
//
//		 		httpc.onreadystatechange = response_send_sms;
//        httpc.send(body);
//			} else {
//		  	if(lang==1)
//	     		alert("Sorry, Failed to produce SMS code, please try again later!");
//		    else
//	 				alert("�Բ������ɶ�����֤��ʧ�ܣ����Ժ�����!");
//			}
//		}
//		//alert(httpn.readyState);
//	}
//	alter("xxxx");
//	return ;
//}

function response_send_sms()
{
	alert("wait for message");
	alert(httpc.readyState);
  if (httpc.readyState==4){
	  alert(httpc.status);
		if(httpc.status == 200){
			alert("recive message ");
	 		var obj=httpc.responseXML ;
			var rstatu = obj.getElementsByTagName("statusCode") ;
			var statu = rstatu[0].firstChild ;
			var info ;
			if( errno.nodeValue != 0 )
			{
		    	if(lang==1)
	         	alert("Sorry, Failed to send SMS. Please check your phone number!");
		    	else
	 					alert("�Բ��𣬶��ŷ���ʧ�ܣ����������ֻ������Ƿ���ȷ!");
			} else {
					var get_code_btn = document.getElementById("send_sms");
					get_code_btn.onclick=function(){time(this);}
					//get_code_btn.innerHTML = "<button onclick=\"send_sms()\"><% ap_rprintf(r, "%s", F_00254_I[ARR_LANG]); /* ��֤���ѷ��� */ %></button>";
	 				//alert("���ŷ��ͳɹ�,����ע�����^_^");
			}
		}
  }
}

// create the object of httpRequest base on the browse using
function getHTTPObject(){
	var xmlhttp = false;
	if(window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
		if(xmlhttp.overrideMimeType){
			xmlhttp.overrideMimeType('text/xml');
		}
	}
	else{
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e){
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(E){
				xmlhttp = false;
			}
		}
	}
	return xmlhttp;
}

//xml part
loadXML    = function(xmlFile)
{
	var xmlDoc;
	if(window.ActiveXObject)
	{
		xmlDoc    = new ActiveXObject('Microsoft.XMLDOM');
		xmlDoc.async    = false;
		xmlDoc.load(xmlFile);
	}
	else if (document.implementation&&document.implementation.createDocument)
	{
		xmlDoc    = document.implementation.createDocument('', '', null);
		xmlDoc.load(xmlFile);
	}
	else
	{
		return null;
	}

	return xmlDoc;
}

// ���ȶ�xml��������ж�
checkXMLDocObj    = function(xmlFile)
{
	var xmlDoc    = loadXML(xmlFile);
	if(xmlDoc==null)
	{
		alert('�����������֧��xml�ļ���ȡ,���Ǳ�ҳ���ֹ���Ĳ���,�Ƽ�ʹ��IE5.0���Ͽ��Խ��������!');
		window.location.href='/newindex/index.php';
	}
	return xmlDoc;
}
//test timer
function btn_dcount(obj,sec) {
	var millisec = sec ;
	var j=0;
	obj.value= "�ٴη��ͣ�[" + sec + "]";
	obj.disabled=true;
	millisec--;
	ret_t=setInterval(function(){countDown(obj,millisec);millisec--},  1000);

	ret_o=setTimeout(function(){TimeOver(obj);}, sec*1000);

}

function countDown(obj,wt) {
	var pntNum=wt;
	obj.value = "" + pntNum+ "�������»�ȡ";
}

function TimeOver(obj) {
	obj.value= "�ٴη���";
	obj.disabled=false;
	clearTimeout(ret_t);
}

