// 所有的2级标签 ： 1表示需要分页
var label_list = {
    "index":{
        "top":"1",
        "hot":"0",
        "classical":"0",
        "classes":"0"
    },
    "news":{
        "news_mix":"1",
        "news_military":"1",
        "news_international":"1",
        "news_sport":"1",
        "news_recreation":"1",
        "news_science":"1",
        "news_finance":"1"
    },
    "club":{
        "club_handpick":"1",
        "club_emotion":"1",
        "club_woman":"1",
        "club_sport":"1",
        "club_game":"1",
        "club_recreation":"1",
        "club_music":"1",
        "club_hobby":"1",
        "club_life":"1",
        "club_finance":"1",
        "club_schoolfellow":"1",
        "club_hisfellow":"1",
        "club_politics":"1",
        "club_science":"1",
        "club_literature":"1",
        "club_art":"1",
        "club_other":"1"
    },
    "immigration":{
        "i_column":"0",
        "i_lawyer":"0",
        "i_news":"1",
        "i_visa":"1",
        "i_discussion":"0"
    }
};

function go_last_page() {
    window.history.go(-1);
}

function get_info_by_id(id) {
    var app_type = getCookie_wap("app_type");
    var domain = label_list[app_type];

    var info = [];
    if (domain) {
        if (domain[id]) {
            info["domain"] = app_type;
            info["needpage"] = domain[id]
        }
    }
    return info;
}

function request_url_generate(id) {
    var info = get_info_by_id(id);
    var url = "/404.php";
    var extra = getCookie_wap("extra");
    if (extra == "") {
        document.cookie = "extra=" + "0|all";
        extra = "0";
    }

    if (info["domain"] != undefined)
        if ((info["domain"] == "index") || (info["domain"] == "immigration"))
            url = "/mobile/forum/request/" + id + ".php" + "?extra=" + extra;
        else
            url = "/mobile/forum/request/" + info["domain"] + ".php?type=" + id;
    return url;
}

function need_page(id) {
    var info = get_info_by_id(id);
    return (info["needpage"] == "1");
}

function sec_select(obj) {
    document.cookie = "extra="+obj.id;
    document.cookie = "current_page=1";
}

function sec_category(obj) {
    var current_active = getCookie_wap("sec_category");
    if(current_active != "") {
        var act_obj = document.getElementById(current_active);
        act_obj.className = "none";
    }
    obj.className = "active";
    document.cookie = "sec_category="+obj.id;
    document.cookie = "current_page=1";
    //ajax 请求部分
//    sec_category_auto();
}
function sec_category_auto(){
    var current_active=getCookie_wap("sec_category");
    var obj=document.getElementById(current_active);
    //obj.className="active";
    //ajax 请求部分
    var url = request_url_generate(obj.id);
    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: null
            , asynchronous: false
            , onSuccess: function (ret) {
            var ret_json = eval("(" + ret.responseText + ")");
            if(ret_json.carouselfigure != undefined) {
                var tag = document.getElementById("carouselfigure");
                tag.setAttribute("style", 'display:block');
                tag.outerHTML = ret_json.carouselfigure;
            } else {
                var tag = document.getElementById("carouselfigure");
                tag.setAttribute("style", 'display:none');
            }

            if(ret_json.detail != undefined) {
                var tag = document.getElementById("detail");
                tag.innerHTML = ret_json.detail;
                var pagebox = document.getElementById("current_next_page");
                if(pagebox)
                    pagebox.parentNode.removeChild(pagebox);

                if (need_page(obj.id)) {
                    var pageNext = document.createElement("div");
                    pageNext.setAttribute("id", "current_page2");
                    tag.appendChild(pageNext);

                    var more_text = document.createElement("h3");
                    more_text.setAttribute("id", "current_next_page")
                    more_text.setAttribute("align", "center");
                    more_text.setAttribute("onclick", "getMoreArticle()");
                    if (getCookie_wap("end_flag") == "1")
                        more_text.innerHTML = "已加载全部内容";
                    else
                        more_text.innerHTML = "点击加载更多内容";

                    document.getElementById("pagebox").appendChild(more_text);
                }
            }

            // js.js设置点击时的效果
            setEffect();
        }
            , onFailure: function (x) {
            alert("fail to get data from server " +
            ",please check your connection;");
        }
        });
}

function getMoreArticle() {
    /* end_flag 为1表示数据已经到尾 */
    if (getCookie_wap("end_flag") != "0")
        return;
    var page = parseInt(getCookie_wap("current_page"))+1;
    document.cookie = "current_page=" + page;
    var url = request_url_generate(getCookie_wap("sec_category"));
    var more_text = document.getElementById("current_next_page");
    more_text.innerHTML = "正在加载中...";
    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: null
            , asynchronous: false
            , onSuccess: function (ret) {
            var ret_json = eval("(" + ret.responseText + ")");

            if(ret_json.article!= undefined) {
                var end_flag = getCookie_wap("end_flag");
//                var page = parseInt(getCookie_wap("current_page"));
                var pageCurrent  = document.getElementById("current_page"+page);
                var tag = document.getElementById("detail");
                pageCurrent.innerHTML = ret_json.article;

                if (end_flag != "1") {
                    var pageNext = document.createElement("div");
                    pageNext.setAttribute("id", "current_page"+(page+1));
                    tag.appendChild(pageNext);
                }

                more_text.innerHTML = "";
                if (end_flag == "1")
                    more_text.innerHTML = "已加载全部内容";
                else {
                    more_text.innerHTML = "点击加载更多内容"
                }
            }

            // js.js设置点击时的效果
            setEffect();
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
function check_phone(obj){
    var url="request/check_phone.php";
    var para="phone="+obj.value;
    var country=document.getElementById("reg_country_id");
    if(obj.value==""){
        alert("请输入电话号码")
        document.getElementById('send_sms_btn').disabled=true;
        return ;
    }
    if(country.value=="Not set"){
        document.getElementById('send_sms_btn').disabled=true;
        alert("选择国家先")
        return ;
    }

    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: para
            , asynchronous: false
            , onSuccess: function (ret) {
                if(ret.responseText==false) {
                    obj.value = '';
                    obj.placeholder = '已经注册过的电话号';
                } else {
                   document.getElementById('send_sms_btn').disabled=false;
                }
            }
            , onFailure: function (x) {
            }
        });

}
function check_country(obj){
    var phone=document.getElementById("phone_num");
    if(obj.value!="Not set"&&phone.value!=""){
        check_phone(phone);
    }else{
        document.getElementById('send_sms_btn').disabled=true;
    }

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
                Alert("发文成功",1);
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
// 删除元素
}
function Alert(str,time) {
    var msgw,msgh,bordercolor;
    msgw=350;//提示窗口的宽度
    msgh=80;//提示窗口的高度
    titleheight=25 //提示窗口标题高度
    bordercolor="#336699";//提示窗口的边框颜色
    titlecolor="#99CCFF";//提示窗口的标题颜色
    var sWidth,sHeight;
    //获取当前窗口尺寸
    sWidth = document.body.offsetWidth;
    sHeight = document.body.offsetHeight;
//    //背景div
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
    //创建提示窗口的div
    var msgObj = document.createElement("div")
    msgObj.setAttribute("id","alertmsgDiv");
    msgObj.setAttribute("align","center");
    msgObj.style.background="white";
    msgObj.style.border="1px solid " + bordercolor;
    msgObj.style.position = "absolute";
    msgObj.style.left = "50%";
    msgObj.style.font="12px/1.6em Verdana, Geneva, Arial, Helvetica, sans-serif";
    //窗口距离左侧和顶端的距离
    msgObj.style.marginLeft = "-225px";
    //窗口被卷去的高+（屏幕可用工作区高/2）-150
    msgObj.style.top = document.body.scrollTop+(window.screen.availHeight/2)-150 +"px";
    msgObj.style.width = msgw + "px";
    msgObj.style.height = msgh + "px";
    msgObj.style.textAlign = "center";
    msgObj.style.lineHeight ="25px";
    msgObj.style.zIndex = "10001";
    document.body.appendChild(msgObj);
    //提示信息标题
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
    title.innerHTML="提示信息";
    document.getElementById("alertmsgDiv").appendChild(title);
    //提示信息
    var txt = document.createElement("p");
    txt.setAttribute("id","msgTxt");
    txt.style.margin="16px 0";
    txt.innerHTML = str;
    document.getElementById("alertmsgDiv").appendChild(txt);
    //设置关闭时间
    window.setTimeout("closewin()",time*1000);
}
function closewin() {
    document.body.removeChild(document.getElementById("alertbgDiv"));
    document.getElementById("alertmsgDiv").removeChild(document.getElementById("alertmsgTitle"));
    document.body.removeChild(document.getElementById("alertmsgDiv"));
}
    function passwd_show(){

}
