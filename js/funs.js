function sec_category(obj){
    var current_active=getCookie_wap("sec_category");
    if(current_active!=""){
        var act_obj=document.getElementById(current_active);
        act_obj.className="none";
    }
    obj.className="active";
    document.cookie="sec_category="+obj.id;
    //ajax ���󲿷�
}
function sec_category_auto(){
    var current_active=getCookie_wap("sec_category");
    var obj=document.getElementById(current_active);
    //obj.className="active";
    //ajax ���󲿷�
    var url="/mobile/forum/request/"+obj.id+".php";

    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: null
            , asynchronous: false
            , onSuccess: function (ret) {
            var ret_json = eval("(" + ret.responseText + ")");
            if(ret_json.carouselfigure != "undefined") {
                var tag = document.getElementById("carouselfigure");
                tag.outerHTML = ret_json.carouselfigure;
            }
            if(ret_json.detail!= undefined) {
                var tag = document.getElementById("detail");
                tag.outerHTML = ret_json.detail;
            }
        }
            , onFailure: function (x) {
            alert("fail to get data from server " +
            ",please check your connection;")
        }
        });
}
function getCookie_wap(name){
    var strCookie=document.cookie;
    var arrCookie=strCookie.split("; ");
    for(var i=0;i<arrCookie.length;i++){
        var arr=arrCookie[i].split("=");
        if(arr[0]==name)return arr[1];
    }
    return "";
}

function post_article(board,title,reid){
    var url = "request/post_article.php"
    var text_id="text_"+reid;
    var content=document.getElementById("text_"+reid);
    var para= "board="+board+"&title="+title+"&reid="+reid+"&content="+content.value;
    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: para
            , asynchronous: false
            , onSuccess: function (ret) {
            if(ret.responseText==true){
                Alert("���ĳɹ�",1);
                remove_node(document.getElementById("text_"+reid));
                remove_node(document.getElementById("btn_"+reid));
                location.reload();
            }else{
                Alert(ret.responseText,5)
            }
        }
            , onFailure: function (x) {
        }
        });
}
function remove_node(obj){
    obj.parentNode.removeChild(obj);
// ɾ��Ԫ��
}
function Alert(str,time) {
    closewin();
    var msgw,msgh,bordercolor;
    msgw=350;//��ʾ���ڵĿ��
    msgh=80;//��ʾ���ڵĸ߶�
    titleheight=25 //��ʾ���ڱ���߶�
    bordercolor="#336699";//��ʾ���ڵı߿���ɫ
    titlecolor="#99CCFF";//��ʾ���ڵı�����ɫ
    var sWidth,sHeight;
    //��ȡ��ǰ���ڳߴ�
    sWidth = document.body.offsetWidth;
    sHeight = document.body.offsetHeight;
//    //����div
    var bgObj=document.createElement("div");
    bgObj.setAttribute('id','alertbgDiv');
    bgObj.style.position="absolute";
    bgObj.style.top="0";
    bgObj.style.background="#E8E8E8";
    bgObj.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=25,finishOpacity=75";
    bgObj.style.opacity="0.6";
    bgObj.style.left="0";
    bgObj.style.width = sWidth + "px";
    bgObj.style.height = sHeight + "px";
    bgObj.style.zIndex = "10000";
    document.body.appendChild(bgObj);
    //������ʾ���ڵ�div
    var msgObj = document.createElement("div")
    msgObj.setAttribute("id","alertmsgDiv");
    msgObj.setAttribute("align","center");
    msgObj.style.background="white";
    msgObj.style.border="1px solid " + bordercolor;
    msgObj.style.position = "absolute";
    msgObj.style.left = "50%";
    msgObj.style.font="12px/1.6em Verdana, Geneva, Arial, Helvetica, sans-serif";
    //���ھ������Ͷ��˵ľ���
    msgObj.style.marginLeft = "-225px";
    //���ڱ���ȥ�ĸ�+����Ļ���ù�������/2��-150
    msgObj.style.top = document.body.scrollTop+(window.screen.availHeight/2)-150 +"px";
    msgObj.style.width = msgw + "px";
    msgObj.style.height = msgh + "px";
    msgObj.style.textAlign = "center";
    msgObj.style.lineHeight ="25px";
    msgObj.style.zIndex = "10001";
    document.body.appendChild(msgObj);
    //��ʾ��Ϣ����
    var title=document.createElement("h4");
    title.setAttribute("id","alertmsgTitle");
    title.setAttribute("align","left");
    title.style.margin="0";
    title.style.padding="3px";
    title.style.background = bordercolor;
    title.style.filter="progid:DXImageTransform.Microsoft.Alpha(startX=20, startY=20, finishX=100, finishY=100,style=1,opacity=75,finishOpacity=100);";
    title.style.opacity="0.75";
    title.style.border="1px solid " + bordercolor;
    title.style.height="18px";
    title.style.font="12px Verdana, Geneva, Arial, Helvetica, sans-serif";
    title.style.color="white";
    title.innerHTML="��ʾ��Ϣ";
    document.getElementById("alertmsgDiv").appendChild(title);
    //��ʾ��Ϣ
    var txt = document.createElement("p");
    txt.setAttribute("id","msgTxt");
    txt.style.margin="16px 0";
    txt.innerHTML = str;
    document.getElementById("alertmsgDiv").appendChild(txt);
    //���ùر�ʱ��
    /*
    attachEvt("","click","alertmsgDiv",endwind)
    attachEvt("","click","msgtxt",endwind)
    attachEvt("","click","alertbgDiv",endwind)
    attachEvt("","click","alertmsgTitle",endwind)
     */
    window.setTimeout("closewin()",time*1000);
    var ua=navigator.userAgent.toLowerCase();
    if(ua.indexOf("msie") >= 0) {
        document.getElementById("alertmsgDiv").attachEvent("onclick",endwind);
        document.getElementById("msgtxt").attachEvent("onclick",endwind);
        document.getElementById("alertbgDiv").attachEvent("onclick",endwind);
        document.getElementById("alertmsgTitle").attachEvent("onclick",endwind);

    }else{
        document.getElementById("alertmsgDiv").addEventListener("click",endwind,true);
        document.getElementById("msgtxt").addEventListener("click",endwind,true);
        document.getElementById("alertbgDiv").addEventListener("click",endwind,true);
        document.getElementById("alertmsgTitle").addEventListener("click",endwind,true);
    }
}
/*
function attachEvt(evt,refEventName,refObjID,refFunctionName){
    //how use: attachEvt(event,'click','objID',functionName)    //�������Ʋ�������
    var e=evt||window.event;    //�¼�,����չ��
    var evtName=refEventName;   //�¼�����,�� click, mouseover�ȣ�ע��û��on
    var oID=refObjID;       //Ԫ�ص�ID��
    var fn=refFunctionName;     //��Ӧ�ĺ�������,��Ӧ���������������ⲿ����
    var brsName='ie';       //Ĭ��Ϊie
    var ua=navigator.userAgent.toLowerCase();
    if(ua.indexOf("msie") >= 0)
        brsName='ie';
    else
        brsName='other';
     if(ua.indexOf("msie") != -1) brsName='ie';
     if(ua.indexOf("gecko") != -1) brsName='gecko';
     if(ua.indexOf("opera") != -1) brsName='opera';
    var obj=document.getElementById(oID);
    if("ie"==brsName)  //IE
        obj.attachEvent("on"+evtName,fn);
    else  //not IE
        obj.addEventListener(evtName,fn,false);  //����Ϊ���¼������õĺ������Ƿ�ð�ݣ�
}
*/
function endwind(){
    if(document.getElementById("alertmsgDiv")!=null)
        document.body.removeChild(document.getElementById("alertmsgDiv"));
    if(document.getElementById("alertbgDiv")!=null)
        document.body.removeChild(document.getElementById("alertbgDiv"));
    if(document.getElementById("alertmsgTitle")!=null)
        document.getElementById("alertmsgDiv").removeChild(document.getElementById("alertmsgTitle"));
}
function closewin() {
    if(document.getElementById("alertbgDiv")!=null)
        document.body.removeChild(document.getElementById("alertbgDiv"));
    if(document.getElementById("alertmsgTitle")!=null)
        document.getElementById("alertmsgDiv").removeChild(document.getElementById("alertmsgTitle"));
    if(document.getElementById("alertmsgDiv")!=null)
        document.body.removeChild(document.getElementById("alertmsgDiv"));
}
function passwd_show(btn){
    var obj = document.getElementById("password");
    var val = obj.value;
    if(obj.type=="password"){
        document.getElementById("passwd_btn").text="����";
        var inputps = document.createElement('input');
        inputps.setAttribute("class","reg_input");
        inputps.setAttribute("id","password");
        inputps.setAttribute("name","password");
        inputps.setAttribute("type","text");
        obj.parentNode.replaceChild(inputps,obj);
        inputps.focus();
        inputps.select();
        document.getElementById("password").value=val;
    }else{
        document.getElementById("passwd_btn").text="��ʾ";
        var inputps = document.createElement('input');
        inputps.setAttribute("class","reg_input");
        inputps.setAttribute("id","password");
        inputps.setAttribute("name","password");
        inputps.setAttribute("type","password");
        obj.parentNode.replaceChild(inputps,obj);
        inputps.focus();
        inputps.select();
        document.getElementById("password").value=val;
    }
}
function check_username(obj) {
    var err=document.getElementById("user_err");
    if (obj.value.indexOf(" ") != -1) {
        obj.value = '';
        obj.placeholder= "�û������ܺ��пո�";
        obj.style.backgroundColor="#FFCC80";
        return;
    }
    if (obj.value.length < 3 || obj.value.length > 12){
        obj.value = '';
        obj.placeholder=  "�û�������3-12�ַ�֮��";
        obj.style.backgroundColor="#FFCC80";
        return;
    }
    var user_name= encodeURIComponent(obj.value);
    var url = "request/check_uname.php";
    var pars = "user_name=" + user_name;

    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: pars
            , onComplete: update_user
            , onFailure: request_error
        });

    function update_user(response) {
        var ret_text= response.responseText;
        if(ret_text!=true){
            obj.style.backgroundColor="#FFCC80";
            err.innerHTML=ret_text;
            err.hidden=false;
            //Alert(ret_text,2);
            //obj.placeholder=ret_text;
           //obj.value="";
        }else{
            obj.style.backgroundColor="#FFFFFF";
            err.hidden="hidden";
        }
    }
}

function check_password(){
    var err=document.getElementById("password_err");
    var password= document.getElementById("password");
    var user_name = document.getElementById("user_id");
    if(password.value.indexOf(" ")!=-1){
        password.value='';
        password.style.backgroundColor="#FFCC80";
        err.innerHTML="���벻�ܺ��пո�";
        err.hidden=false;
        return ;
    }
    var url = "request/check_password.php";
    var var_user_name=encodeURIComponent(user_name.value);
    var var_password=encodeURIComponent(password.value);
    var pars = "user_name="+var_user_name+"&pass_word="+var_password;
    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: pars
            , onComplete: update_password
            , onFailure: request_error
        });

    function update_password(response) {
        var ret_text= response.responseText;
        if(ret_text!=true){
            password.style.backgroundColor="#FFCC80";
            err.innerHTML=ret_text;
            err.hidden=false;
            //obj.placeholder=ret_text;
            //obj.value="";
        }else{
            password.style.backgroundColor="#FFFFFF";
            err.hidden=true;
        }

    }
}
function request_error(ret){
    Alert(ret.responseText,2);
}
function check_confirm(obj){
    var err=document.getElementById("confirm_err") ;
    if(obj.value.length!=4){
        err.innerHTML="������4����λ��֤��";
        err.hidden=false;
        return ;
    }
    err.hidden=true;
}
function check_phone(obj){
    var country=document.getElementById("reg_country_id");
    var err=document.getElementById("phone_err");
    if(obj.value==""){
        err.innerHTML="������绰����";
        err.hidden=false;
        document.getElementById('send_sms_btn').disabled=true;
        return ;
    }
    if(obj.value.length>12){
        err.innerHTML="�绰���볬��";
        err.hidden=false;
        document.getElementById('send_sms_btn').disabled=true;
        return ;
    }
    if(country.value=="Not set"){
        document.getElementById('send_sms_btn').disabled=true;
        err.innerHTML="��ѡ�����";
        err.hidden=false;
        return ;
    }

    var url="request/check_phone.php"
    var pars="phone="+obj.value;
    //var myAjax = new Ajax.Request(url,
    //    {
    //        method: 'post'
    //        , parameters: para
    //        , asynchronous: false
    //        , onSuccess: function (ret) {
    //            if(ret.responseText==false) {
    //                obj.value = '';
    //                obj.placeholder = '�Ѿ�ע����ĵ绰��';
    //            } else {
    //               document.getElementById('send_sms_btn').disabled=false;
    //            }
    //        }
    //        , onFailure: function (x) {
    //                Alert(x.responseText,2);
    //        }
    //    });
    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: pars
            , onComplete: up_phone
            , onFailure: request_error
        });

    function up_phone(response) {
        var ret_text= response.responseText;
        if(ret_text!=true){
            obj.style.backgroundColor="#FFCC80";
            err.innerHTML=ret_text;
            err.hidden=false;
            //obj.placeholder=ret_text;
        }else{
            obj.style.backgroundColor="#FFFFFF";
            document.getElementById('send_sms_btn').disabled=false;
            err.hidden=true;
        }
    }

}
function check_country(obj){
    var phone=document.getElementById("phone_num");
    if(obj.value!="Not set"&&phone.value!=""){
        check_phone(phone);
    }else{
        document.getElementById('send_sms_btn').disabled=true;
    }
}
