// ���е�2����ǩ�� 1��ʾ��Ҫ��ҳ
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
    },
    "dianping":{
        "dp_recommend":"0",
        "dp_near":"1",
        "dp_search":"0",
        "dp_rank":"0"
    },
    "jiaye":{
        "jiaye":"0",
        "focus":"0",
        "fans":"0",
        "discuss":"0",
        "club":"0",
        "dianping":"0",
        "article":"0",
        "collect":"0",
        "friend":"0",
        "black":"0",
        "message":"0",
        "email":"0"
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

function have_carouselfigure(id) {
    if (id == "top" || id == "i_column")
        return true;

    var info = get_info_by_id(id);
    return (info["domain"] == "club");
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
        switch (info["domain"]) {
            case "index":
            case "immigration":
            case "dianping":
                url = "/mobile/forum/request/" + id + ".php" + "?extra=" + extra;
                break;
            case "news":
            case "club":
                url = "/mobile/forum/request/" + info["domain"] + ".php?type=" + id;
                break;
            default:
                break;
        }

    return url;
}

function need_page(id) {
    var info = get_info_by_id(id);
    return (info["needpage"] == "1");
}

function sec_select(obj) {
    if (arguments[1] != undefined)
        document.cookie = "sec_category=" + arguments[1];
    document.cookie = "extra=" + obj.id;
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
    //ajax ���󲿷�
//    sec_category_auto();
}

function sec_category_auto(){
    var current_active=getCookie_wap("sec_category");
    var obj=document.getElementById(current_active);
    //obj.className="active";
    //ajax ���󲿷�
    var url = request_url_generate(obj.id);
    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: null
            , asynchronous: false
            , onSuccess: function (ret) {
            var ret_json = eval("(" + ret.responseText + ")");
            if(have_carouselfigure(obj.id)) {
                var tag = document.getElementById("carouselfigure");
                tag.setAttribute("style", 'display:block');
                if (ret_json.carouselfigure != undefined)
                    tag.outerHTML = ret_json.carouselfigure;
            } else {
                var tag = document.getElementById("carouselfigure");
                if (tag)
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
                    more_text.setAttribute("onclick", "getMoreArticleCommon()");
                    if (getCookie_wap("end_flag") == "1")
                        more_text.innerHTML = "�Ѽ���ȫ������";
                    else
                        more_text.innerHTML = "������ظ�������";

                    document.getElementById("pagebox").appendChild(more_text);
                }
            }

            // js.js���õ��ʱ��Ч��
            setEffect();
        }
            , onFailure: function (x) {
            alert("fail to get data from server " +
            ",please check your connection;");
        }
        });
}

function sec_category_auto_dp(queryString) {
    var current_active=getCookie_wap("sec_category");
    var obj=document.getElementById(current_active);
    //obj.className="active";
    //ajax ���󲿷�
    var url = request_url_generate(obj.id);
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: queryString,
            asynchronous: false,
            onSuccess: function (ret) {
                var ret_json = eval("(" + ret.responseText + ")");
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
                        more_text.setAttribute("onclick", "getMoreShop()");
                        if (getCookie_wap("end_flag") == "1")
                            more_text.innerHTML = "�Ѽ���ȫ������";
                        else
                            more_text.innerHTML = "������ظ�������";

                        document.getElementById("pagebox").appendChild(more_text);
                    }
                } else {
                    tag.innerHTML = "";
                }
            },
            onFailure: function (x) {

            }
        }
    );
}

function sec_category_auto_dp_search(queryString) {
    //ajax ���󲿷�
    // ��ѯ�ӿڸ��� dp_near
    var url = request_url_generate("dp_near");
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: queryString,
            asynchronous: false,
            onSuccess: function (ret) {
                var ret_json = eval("(" + ret.responseText + ")");
                if(ret_json.detail != undefined) {
                    var kws = document.getElementById("kws").innerHTML;

                    var tag = document.getElementById("detail");
                    tag.innerHTML = ret_json.detail;
                    var pagebox = document.getElementById("current_next_page");
                    if(pagebox)
                        pagebox.parentNode.removeChild(pagebox);

                    var pageNext = document.createElement("div");
                    pageNext.setAttribute("id", "current_page2");
                    tag.appendChild(pageNext);

                    var more_text = document.createElement("h3");
                    more_text.setAttribute("id", "current_next_page")
                    more_text.setAttribute("align", "center");
                    if (kws != "") {
                        more_text.setAttribute("onclick", "getMoreShop(\""+kws+"\")");
                    } else {
                        more_text.setAttribute("onclick", "getMoreShop()");
                    }

                    if (getCookie_wap("end_flag") == "1")
                        more_text.innerHTML = "�Ѽ���ȫ������";
                    else
                        more_text.innerHTML = "������ظ�������";

                    document.getElementById("pagebox").appendChild(more_text);
                } else {
                    tag.innerHTML = "";
                }

                setEffect();
            },
            onFailure: function (x) {

            }
        }
    );
}

function getMoreShop() {
    var kws = "";
    if (arguments.length == 1)
        kws = arguments[0];

    /* end_flag Ϊ1��ʾ�����Ѿ���β */
    if (getCookie_wap("end_flag") != "0")
        return;
    var page = parseInt(getCookie_wap("current_page"))+1;
    document.cookie = "current_page=" + page;
    var url = request_url_generate("dp_near");
    var more_text = document.getElementById("current_next_page");
    more_text.innerHTML = "���ڼ�����...";
    more_text.removeAttribute("onclick");
    var near_type = document.getElementById("near_type").innerHTML;
    var food_class_type = document.getElementById("food_class_type").innerHTML;
    var order_type = document.getElementById("order_type").innerHTML;
    var queryString = "";
    if (kws != "") {
        queryString = "near_type="+near_type+"&food_class_type="+food_class_type+"&order_type="+order_type+"&page="+page+"&kws="+kws;
    } else {
        queryString = "near_type="+near_type+"&food_class_type="+food_class_type+"&order_type="+order_type+"&page="+page;
    }

    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: queryString
            , asynchronous: false
            , onSuccess: function (ret) {
            var ret_json = eval("(" + ret.responseText + ")");

            if(ret_json.detail!= undefined) {
                var end_flag = getCookie_wap("end_flag");
//                var page = parseInt(getCookie_wap("current_page"));
                var pageCurrent  = document.getElementById("current_page"+page);
                var tag = document.getElementById("detail");
                pageCurrent.innerHTML = ret_json.detail;

                if (end_flag != "1") {
                    var pageNext = document.createElement("div");
                    pageNext.setAttribute("id", "current_page"+(page+1));
                    tag.appendChild(pageNext);
                }

                more_text.innerHTML = "";
                if (end_flag == "1")
                    more_text.innerHTML = "�Ѽ���ȫ������";
                else {
                    more_text.innerHTML = "������ظ�������"
                }
                if (kws != "") {
                    more_text.setAttribute("onclick", "getMoreShop(\""+kws+"\")");
                } else {
                    more_text.setAttribute("onclick", "getMoreShop()");
                }
            }

            // js.js���õ��ʱ��Ч��
            setEffect();
        }
            , onFailure: function (x) {
            alert("fail to get data from server " +
                ",please check your connection;")
        }
        });
}

function getMoreArticleCommon() {
    /* end_flag Ϊ1��ʾ�����Ѿ���β */
    if (getCookie_wap("end_flag") != "0")
        return;
    var page = parseInt(getCookie_wap("current_page"))+1;
    document.cookie = "current_page=" + page;
    var url = request_url_generate(getCookie_wap("sec_category"));
    var more_text = document.getElementById("current_next_page");
    more_text.innerHTML = "���ڼ�����...";
    more_text.removeAttribute("onclick");
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
                    more_text.innerHTML = "�Ѽ���ȫ������";
                else {
                    more_text.innerHTML = "������ظ�������"
                }
                more_text.setAttribute("onclick", "getMoreArticleCommon()");
            }

            // js.js���õ��ʱ��Ч��
            setEffect();
            }
            , onFailure: function (x) {
            alert("fail to get data from server " +
                ",please check your connection;")
            }
        });
}

function myarticle(obj) {
    var current_active = getCookie_wap("article_type");
    if (current_active != "") {
        var act_obj = document.getElementById(current_active);
        act_obj.className = "none";
    }
    obj.className = "active";
    document.cookie = "article_type="+obj.id;
    document.cookie = "current_page=1";
}

function myarticle_auto() {
    var article_type = getCookie_wap("article_type");
    var current_page = getCookie_wap("current_page");

    var url = "/mobile/forum/request/myarticle.php?article_type=" + article_type;
    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: null
            , asynchronous: false
            , onSuccess: function (ret) {
            var ret_json = eval("(" + ret.responseText + ")");
            if (ret_json.detail != undefined) {
                var tag = document.getElementById("detail");
                tag.innerHTML = ret_json.detail;
                var pagebox = document.getElementById("current_next_page");
                if(pagebox)
                    pagebox.parentNode.removeChild(pagebox);

                var pageNext = document.createElement("div");
                pageNext.setAttribute("id", "current_page2");
                tag.appendChild(pageNext);

                var more_text = document.createElement("h3");
                more_text.setAttribute("id", "current_next_page")
                more_text.setAttribute("align", "center");
                more_text.setAttribute("onclick", "getMoreArticleOwn()");
                if (getCookie_wap("end_flag") == "1")
                    more_text.innerHTML = "�Ѽ���ȫ������";
                else
                    more_text.innerHTML = "������ظ�������";

                document.getElementById("pagebox").appendChild(more_text);

            }

            setEffect();
        }
            , onFailure: function (x) {
            alert("fail to get data from server " +
                ",please check your connection;")
            }
        });
}

function getMoreArticleOwn() {
    /* end_flag Ϊ1��ʾ�����Ѿ���β */
    if (getCookie_wap("end_flag") != "0")
        return;
    var page = parseInt(getCookie_wap("current_page"))+1;
    document.cookie = "current_page=" + page;
    var article_type = getCookie_wap("article_type");
    var url = "/mobile/forum/request/myarticle.php?article_type=" + article_type;
    var more_text = document.getElementById("current_next_page");
    more_text.innerHTML = "���ڼ�����...";
    more_text.removeAttribute("onclick");
    var myAjax = new Ajax.Request(url,
        {
            method: 'post',
            parameters: null,
            asynchronous: false,
            onSuccess: function (ret) {
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
                    more_text.innerHTML = "�Ѽ���ȫ������";
                else {
                    more_text.innerHTML = "������ظ�������"
                }
                more_text.setAttribute("onclick", "getMoreArticleOwn()");
            }

            // js.js���õ��ʱ��Ч��
            setEffect();
            },
            onFailure: function (x) {
            alert("fail to get data from server " +
                ",please check your connection;")
            }
        });
}

function collect_by_type(type, obj) {
    /* type:
     *      1 �ղذ���
     *      6 �ղذ�������
     *      7 �ղؾ��ֲ�����
     *
     *
     * */

    var url = "/mobile/forum/request/collect_by_type.php";
    var para = "";
    if (type == 1) {
        var board_id = arguments[2];
        para = "type=1&board_id="+board_id;
    }

    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
                var ret_json = eval("("+ret.responseText+")");
                if (type == 1) {
                    // ���ղسɹ�ʱ�޸�ͼ��
                    if (ret_json.result != undefined && ret_json.result == "0") {
                        var arr = obj.id.split("_");
                        if (arr[1] == "1") {
                            obj.firstChild.src = "img/star1.png";
                            obj.id = arr[0]+"_0";
                        } else {
                            obj.firstChild.src = "img/star2.png";
                            obj.id = arr[0]+"_1";
                        }
                    } else {
                        Alert(ret_json.msg, 1);
                    }

                }

            },
            onFailure: function (x) {

            }
        }

    );


    if (type == 1) {
        // ���ղسɹ�ʱ�޸�ͼ��
        var arr = obj.id.split("_");
        if (arr[1] == "1") {
            obj.firstChild.src = "img/star1.png";
            obj.id = arr[0]+"_0";
        } else {
            obj.firstChild.src = "img/star2.png";
            obj.id = arr[0]+"_1";
        }

    }

    return false;
}

function clearTrash() {
    var url = "/mobile/forum/request/mail_action.php";
    var para = "option=clear&dir=d";
    var myAjax = new Ajax.Request(url,
        {
            method: 'post',
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
            if (ret.responseText == true) {
                location.reload();
            } else {
                Alert("����ʧ��,"+ret.responseText, 1);
            }
            },
            onFailure: function (ret) {
                Alert("����ʧ��!", 1);
            }
        });
}

function clearEmail(msg) {
    var arr = msg.split("_");
    if (arr.length < 2)
        return false;

    var type = arr[0];
    var mailid = arr[1];

    var s_dir = "";
    switch (type) {
        case "unread":
            s_dir = "r";
            break;
        case "total":
            s_dir = "r";
            break;
        case "send":
            s_dir = "s";
            break;
        case "delete":
            s_dir = "d";
            break;
        default:
            break;
    }

    var url = "/mobile/forum/request/mail_action.php";
    var para = "option=move&d_dir=d&s_dir="+s_dir+"&mailid="+mailid;
    var myAjax = new Ajax.Request(url,
        {
            method: 'post',
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
                if (ret.responseText == true) {
                    location.reload();
                    Alert("ɾ���ɹ�", 2);
                } else {
                    Alert("����ʧ��,"+ret.responseText, 1);
                }
            },
            onFailure: function (ret) {
                Alert("����ʧ��!", 1);
            }
        });
}

function delEmail(msg) {
    var arr = msg.split("_");
    if (arr.length < 2)
        return false;

    var type = arr[0];
    var mailid = arr[1];

    var dir = "";
    switch (type) {
        case "unread":
            dir = "r";
            break;
        case "total":
            dir = "r";
            break;
        case "send":
            dir = "s";
            break;
        case "delete":
            dir = "d";
            break;
        default:
            break;
    }

    var url = "/mobile/forum/request/mail_action.php";
    var para = "option=del&dir="+dir+"&mailid="+mailid;
    var myAjax = new Ajax.Request(url,
        {
            method: 'post',
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
                if (ret.responseText == true) {
                    location.reload();
                    Alert("ɾ���ɹ�", 2);
                } else {
                    Alert("����ʧ��,"+ret.responseText, 1);
                }
            },
            onFailure: function (ret) {
                Alert("����ʧ��!", 1);
            }
        });
}

function recoverEmail(msg) {
    var arr = msg.split("_");
    if (arr.length < 2)
        return false;

    var type = arr[0];
    var mailid = arr[1];

    var s_dir = "";
    switch (type) {
        case "unread":
            s_dir = "r";
            break;
        case "total":
            s_dir = "r";
            break;
        case "send":
            s_dir = "s";
            break;
        case "delete":
            s_dir = "d";
            break;
        default:
            break;
    }

    var url = "/mobile/forum/request/mail_action.php";
    var para = "option=move&d_dir=r&s_dir="+s_dir+"&mailid="+mailid;
    var myAjax = new Ajax.Request(url,
        {
            method: 'post',
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
                if (ret.responseText == true) {
                    location.reload();
                    Alert("�ָ��ʼ��ɹ�", 2);
                } else {
                    Alert("����ʧ��,"+ret.responseText, 1);
                }
            },
            onFailure: function (ret) {
                Alert("����ʧ��!", 1);
            }
        });
}

function add_focus(userid, page_type) {
    var url = "/mobile/forum/request/friend_action.php";
    var para = "option=add&type=1&userid="+userid;

    var myAjax = new Ajax.Request(url,
        {
            method: 'post',
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
                if (ret.responseText == true) {
                    if (page_type == 1) {
                        // ��˿�б�ļӹ�ע��ť
                        var tag = document.getElementById("add_focus_"+userid);
                        parent_node = tag.parentNode;
                        parent_node.removeChild(tag);
                        var span = document.createElement("span");
                        span.innerHTML = "�����ע";
                        parent_node.appendChild(span);
                        Alert("��ע�ɹ�", 1);
                    } else if (page_type == 2) {
                        // ������Ϣ�б�ļӹ�ע��ť
                        var tag = document.getElementById("add_focus");
                        tag.value = "�ѹ�ע";
                        tag.setAttribute("class", "margin_right button_disable");
                        tag.setAttribute("onclick", "return false");
                        Alert("��ע�ɹ�", 1);
                    }
                } else {
                    Alert("��ӹ�עʧ��,"+ret.responseText, 2);
                }
            },
            onFailure: function (ret) {
                Alert("����ʧ��!",1);
            }
        });
    return false;
}

function add_friend(userid, page_type) {
    var url = "/mobile/forum/request/friend_action.php";
    var para = "option=add&type=0&userid="+userid;

    var myAjax = new Ajax.Request(url,
        {
            method: 'post',
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
                if (ret.responseText == true) {
                    if (page_type == 1) {

                    } else if (page_type == 2) {
                        // ������Ϣ�б�ļӺ��Ѱ�ť
                        var tag = document.getElementById("add_friend");
                        tag.value = "������";
                        tag.setAttribute("class", "search_btn1 button_disable");
                        tag.setAttribute("onclick", "return false");
                        Alert("�ѷ��ͺ�������", 1);
                    }
                } else {
                    Alert("����ʧ��,"+ret.responseText, 2);
                }
            },
            onFailure: function (ret) {
                Alert("����ʧ��!",1);
            }
        });
    return false;
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
    var club_flag = arguments[3]?arguments[3]:"0";
    var url = "/mobile/forum/request/post_article.php";
    var text_id="text_"+reid;
    var content=document.getElementById("text_"+reid);
    var para = "";
    if (club_flag == 0)
        para = "board="+board+"&title="+title+"&reid="+reid+"&content="+content.value;
    else
        para = "club="+board+"&title="+title+"&reid="+reid+"&content="+content.value;

    send_article(url, para, true);
}

function send_article(url, para, re_flag) {
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            onSuccess: function (ret) {
            if (re_flag == true) {
                if (ret.responseText == true) {
                    Alert("���ĳɹ�",1);
                    remove_node(document.getElementById("text_"+reid));
                    remove_node(document.getElementById("btn_"+reid));
                    location.reload();
                } else {
                    Alert(ret.responseText,5)
                }
            } else {
                if (ret.responseText == true) {
                    Alert("���ĳɹ�", 1);
                    // �������� δ��Ч
                    var form_article = document.getElementById("form_article");
                    var input = form_article.getElementsByTagName("input");
                    var textarea = form_article.getElementsByTagName("textarea");
                    input[0].value = "";
                    textarea[0].value = "";
                    textarea[0].innerHTML = "";
                    input[0].focus();

                    // ����ϴ�ͼƬ������ͼ δ��Ч
                    var img_count = document.getElementsById("img_count");
                    var count = parseInt(img_count.value);
                    for (var i = 0; i < count; i++) {
                        remove_node(document.getElementsById("img_"+i));
                    }
                    img_count.value = "0";
                }
            }
            },
            onFailure: function (x) {

            }
        });
}

function post_email(mailto, title, content) {
    // email_type: 0 ��ͨ�ż� 1 ��ʦ��ѯ
    var email_type = 0;
    if (arguments.length == 4) {
        if (arguments[3] != 0)
            email_type = 1;
    }

    var url = "/mobile/forum/request/sendmail.php";
    if (email_type == 0)
        var para = "owner="+mailto+"&title="+title+"&content="+content;
    else
        var para = "owner="+mailto+"&title="+title+"&content="+content;

    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
            if (ret.responseText == true) {
                Alert("�ʼ����ͳɹ�", 1);
            } else {
                alert(ret.responseText, 5);
            }
            },
            onFailure: function (x) {
                Alert("����ʧ��!", 1);
            }
        }
    );

}

function location_callback(position) {
    // position.coords.latitude
    // position.coords.longitude
    var url = "/mobile/forum/request/location.php";
    var para = "result=success&lon="+position.coords.longitude+"&lat="+position.coords.latitude;
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {

            },
            onFailure: function (x) {

            }
        }
    );
}

function location_error(error) {

    switch (error.code) {
        case error.PERMISSION_DENIED:
            // �û��ܾ�
            break;
        case error.POSITION_UNAVAILABLE:
            break;
        case error.TIMEOUT:
            break;
        case error.UNKNOWN_ERROR:
            break;
    }

    var url = "/mobile/forum/request/location.php";
    var para = "result=failed";
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            onSuccess: function (ret) {

            },
            onFailure: function (x) {

            }
        }
    );
}

function select_city() {
    $('#near').hide();
    $('#search').hide();
    $('#rank').hide();
    var url = "/mobile/forum/request/dp_getcity.php";
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: null,
            asynchronous: false,
            onSuccess: function (ret) {
                var ret_json = eval("(" + ret.responseText + ")");
                var detail = document.getElementById("detail");
                if (ret_json.detail != undefined) {
                    if (detail != undefined) {
                        detail.innerHTML = ret_json.detail;
                    }
                } else {
                    if (detail != undefined) {
                        var new_tag = document.createElement("h2");
                        new_tag.innerHTML = "Ŀǰû�г�����Ϣ!";
                        detail.appendChild(new_tag);
                    }
                }
            },
            onFailure: function (x) {
                Alert("����ʧ��", 1);
            }
        }
    );

    return false;
}

function set_city(obj) {
    var val = obj.id.split("|");
    document.cookie = "extra=0|" + val[1];

    if (val[1] != "all")
        document.getElementById("city_name").innerHTML = obj.innerHTML;

    document.getElementById("detail").innerHTML = "";
    document.getElementById(val[0]).click();
    return true;
}

function shop_search(event) {
    if(!event)
        event = window.event;//������� window.event
    if((event.keyCode || event.which) != 13)
        return true;

    var tag = document.getElementById("search_kws");
    var search_kws = tag.value;
    var jumpto = "shop_list.php?kws="+search_kws;
//    jumpto = encodeURIComponent(jumpto);

    window.location.href = jumpto;
    return false;
}

function select_food_class() {
    $('#search').hide();
    var url = "/mobile/forum/request/dp_getfoodclass.php";
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: null,
            asynchronous: false,
            onSuccess: function (ret) {
                var ret_json = eval("(" + ret.responseText + ")");
                var detail = document.getElementById("detail");
                if (ret_json.detail != undefined) {
                    if (detail != undefined) {
                        detail.innerHTML = ret_json.detail;
                    }
                } else {
                    if (detail != undefined) {
                        var new_tag = document.createElement("h2");
                        new_tag.innerHTML = "Ŀǰû��ʳ�����!";
                        detail.appendChild(new_tag);
                    }
                }
            },
            onFailure: function (x) {
                Alert("����ʧ��", 1);
            }
        }
    );

    return false;
}

function go_back_dp_search() {
    $('#detail').html("");
    $('#search').show();
}

function shop_list_request(labeltype) {
    var queryString;

    var near_type = document.getElementById("near_type").innerHTML;
    var food_class_type = document.getElementById("food_class_type").innerHTML;
    var order_type = document.getElementById("order_type").innerHTML;

    queryString = "near_type="+near_type+"&food_class_type="+food_class_type+"&order_type="+order_type;

    if (labeltype == "dp_near") {
        sec_category_auto_dp(queryString);
    } else if (labeltype == "dp_shoplist") {
        var kws = document.getElementById("kws").innerHTML;
        if (kws != "")
            queryString = queryString+"&kws="+kws;
        sec_category_auto_dp_search(queryString);
    }
}

function dp_set_shop_type(obj, tag_name) {
    var labeltype = "dp_near";
    if (arguments.length == 3)
        labeltype = arguments[2];

    switch (tag_name) {
        case "near_type":
        case "food_class_type":
        case "order_type":
            var tag = document.getElementById(tag_name);
            if (tag != undefined) {
                var arr = obj.id.split("_");
                if (arr.length >= 1) {
                    tag.innerHTML = arr[arr.length-1];
                }
            }
            $('.box_mask').hide();
            $('.dn_box').hide();

            shop_list_request(labeltype);
            break;
        default:
            break;
    }

    return false;
}

function dp_show_secmenu(id) {
    var near = document.getElementById("near");
    var search = document.getElementById("search");
    var rank = document.getElementById("rank");

    switch(id) {
        case "dp_near":
            near.setAttribute("style", "display: block");
            search.setAttribute("style", "display: none");
            rank.setAttribute("style", "display: none");
            break;
        case "dp_search":
            near.setAttribute("style", "display: none");
            search.setAttribute("style", "display: block");
            rank.setAttribute("style", "display: none");
            break;
        case "dp_rank":
            near.setAttribute("style", "display: none");
            search.setAttribute("style", "display: none");
            rank.setAttribute("style", "display: block");
            break;
    }

    setEffect();
    return false;
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
function check_country(obj) {
    var phone=document.getElementById("phone_num");
    if(obj.value!="Not set"&&phone.value!=""){
        check_phone(phone);
    }else{
        document.getElementById('send_sms_btn').disabled=true;
    }

}

function add_read_num(obj) {
    var url = "request/add_read_num.php";
    var pars = "href="+encodeURIComponent(obj.href);
    var myAjax = new Ajax.Request(url,
        {
            method: 'post'
            , parameters: pars
        });
}

function dp_check_cnName(cnName) {
    var prompt1 = document.getElementById("prompt1");
    if (cnName.value == null || cnName.value == "") {
        prompt1.show();
        return false;
    } else {
        prompt1.hide();
        return true;
    }
}

function dp_check_food_class(food_class) {
    var prompt2 = document.getElementById("prompt2");
    if (food_class.value == "10") {
        prompt2.show();
        return false;
    } else {
        prompt2.hide();
        return true;
    }
}

function dp_check_city(city) {
    var prompt3 = document.getElementById("prompt3");
    if (city.value == "10") {
        prompt3.show();
        return false;
    } else {
        prompt3.hide();
        return true;
    }
}

function dp_getLocation(obj) {
    var address = obj.value;
    if (address == "")
        return true;
    var url = "/mobile/forum/request/location.php";
    var para = "search="+address;
    var tag_lat = document.getElementById("pos_lat");
    var tag_lng = document.getElementById("pos_lng");

    var prompt4 = document.getElementById("prompt4");
    prompt4.innerHTML = "���ڲ�ѯλ��...";
    var myAjax = new Ajax.Request(url,
        {
            method:"post",
            parameters:para,
            asynchronous:true,
            onSuccess: function (ret) {

                var ret_json = eval("(" + ret.responseText + ")");
                if (ret_json.result != undefined && ret_json.result == 1) {
                    var prompt4 = document.getElementById("prompt4");
                    prompt4.innerHTML = "��ַ��λ�ɹ�";
                    tag_lat.value = ret_json.location.lat;
                    tag_lng.value = ret_json.location.lng;
                } else {
                    prompt4.innerHTML = "��ַ��λʧ��";
                }

            },
            onFailure: function (x) {
            }
        }
    );

    return true;
}

function dp_check_addshop_form() {
    var cnName = document.getElementById("cnName");
    if (!dp_check_cnName(cnName)) {
        cnName.focus();
        return false;
    }

    var food_class = document.getElementById("food_class");
    if (!dp_check_food_class(food_class)) {
        food_class.focus();
        return false;
    }

    var city = document.getElementById("city_list");
    if (!dp_check_city(city)) {
        city.focus();
        return false;
    }

    document.getElementById("addShopForm").submit();

    return false;
}

function dp_show_rank(obj) {
    var arr = obj.id.split("_");

    var rank_type = document.getElementById("rank_type");
    var active_id = "rank_"+rank_type.innerHTML;
    rank_type.innerHTML = arr[1];
    document.getElementById(active_id).setAttribute("class", "");
    obj.setAttribute("class", "redgo");
    var extra = getCookie_wap("extra");
    var url = "/mobile/forum/request/dp_rank.php?extra="+extra;
    var para = "reason="+arr[1];
    var tag = document.getElementById("detail");
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
                var ret_json = eval("(" + ret.responseText + ")");
                if(ret_json.detail != undefined) {
                    tag.innerHTML = ret_json.detail;
                } else {
                    tag.innerHTML = "";
                }
            },
            onFailure: function (x) {

            }
        }
    );

    return false;
}

function dp_send_comment(url, para, jumpto) {
    var result = false;
    $.ajax({
        type: "POST",
        url: url,
        async:false,
        data: para,
        success: function (ret) {
            var ret_json = eval("(" + ret + ")");
            if (ret_json.result == "0") {
                window.location.href = jumpto;
            } else {
                Alert(ret_json.msg, 1);
            }
        }
    });

    return result;
}
