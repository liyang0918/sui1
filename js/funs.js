// 所有的2级标签： 1表示需要分页
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
    return false;
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
    return (info["domain"]=="club" || info["domain"]=="news");
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
                url = "/mobile/forum/request/" + id + ".php" + "?extra=" + extra;
                break;
            case "dianping":
                url = "/mobile/forum/request/" + id + ".php" + "?city=" + getCookie_wap("dp_city");
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
            if(have_carouselfigure(obj.id)) {
                var tag = document.getElementById("carouselfigure");
                tag.setAttribute("style", 'display:block');
                if (ret_json.carouselfigure != undefined) {
                    tag.outerHTML = ret_json.carouselfigure;
                }
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

function sec_category_auto_dp(queryString) {
    var current_active=getCookie_wap("sec_category");
    var obj=document.getElementById(current_active);
    //obj.className="active";
    //ajax 请求部分
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
                            more_text.innerHTML = "已加载全部内容";
                        else
                            more_text.innerHTML = "点击加载更多内容";

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
    //ajax 请求部分
    // 查询接口复用 dp_near
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
                        more_text.innerHTML = "已加载全部内容";
                    else
                        more_text.innerHTML = "点击加载更多内容";

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

    /* end_flag 为1表示数据已经到尾 */
    if (getCookie_wap("end_flag") != "0")
        return;
    var page = parseInt(getCookie_wap("current_page"))+1;
    document.cookie = "current_page=" + page;
    var url = request_url_generate("dp_near");
    var more_text = document.getElementById("current_next_page");
    more_text.innerHTML = "正在加载中...";
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
                    more_text.innerHTML = "已加载全部内容";
                else {
                    more_text.innerHTML = "点击加载更多内容"
                }
                if (kws != "") {
                    more_text.setAttribute("onclick", "getMoreShop(\""+kws+"\")");
                } else {
                    more_text.setAttribute("onclick", "getMoreShop()");
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

function getMoreArticleCommon() {
    /* end_flag 为1表示数据已经到尾 */
    if (getCookie_wap("end_flag") != "0")
        return;
    var page = parseInt(getCookie_wap("current_page"))+1;
    document.cookie = "current_page=" + page;
    var url = request_url_generate(getCookie_wap("sec_category"));
    var more_text = document.getElementById("current_next_page");
    more_text.innerHTML = "正在加载中...";
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
                    more_text.innerHTML = "已加载全部内容";
                else {
                    more_text.innerHTML = "点击加载更多内容"
                }
                more_text.setAttribute("onclick", "getMoreArticleCommon()");
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
                    more_text.innerHTML = "已加载全部内容";
                else
                    more_text.innerHTML = "点击加载更多内容";

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
    /* end_flag 为1表示数据已经到尾 */
    if (getCookie_wap("end_flag") != "0")
        return;
    var page = parseInt(getCookie_wap("current_page"))+1;
    document.cookie = "current_page=" + page;
    var article_type = getCookie_wap("article_type");
    var url = "/mobile/forum/request/myarticle.php?article_type=" + article_type;
    var more_text = document.getElementById("current_next_page");
    more_text.innerHTML = "正在加载中...";
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
                    more_text.innerHTML = "已加载全部内容";
                else {
                    more_text.innerHTML = "点击加载更多内容"
                }
                more_text.setAttribute("onclick", "getMoreArticleOwn()");
            }

            // js.js设置点击时的效果
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
     *      6 收藏版面
     *      1 收藏版面文章
     *      2 收藏俱乐部文章
     *
     *
     * */

    var url = "/mobile/forum/request/collect_by_type.php";
    var para = "type="+type;
    if (type == 6) {
        var board_id = arguments[2];

        para = para+"&board_id="+board_id;
    } else if (type == 1) {
        var board_id = arguments[2];
        var group_id = arguments[3];
        var title = arguments[4];

        para = para+"&board_id="+board_id+"&group_id="+group_id+"&title="+title;
    } else if (type == 2) {
        var club_id = arguments[2];
        var group_id = arguments[3];
        var title = arguments[4];

        para = para+"&club_id="+club_id+"&group_id="+group_id+"&title="+title;
    }

    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
                var ret_json = eval("("+ret.responseText+")");

                if (type == 6) {
                    // 当收藏成功时修改图标
                    if (ret_json.result != undefined && ret_json.result == "0") {
                        var arr = obj.id.split("_");
                        if (arr[1] == "1") {
                            // 取消收藏
                            obj.firstChild.src = "img/star1.png";
                            obj.id = arr[0]+"_0";
                        } else {
                            // 添加收藏
                            obj.firstChild.src = "img/star2.png";
                            obj.id = arr[0]+"_1";
                        }
                    } else {
                        Alert(ret_json.msg, 1);
                    }
                } else if (type == 1) {
                    if (ret_json.result != undefined && ret_json.result == "0") {
                        var arr = obj.id.split("_");
                        var img = document.getElementById("collect_img");
                        var span = document.getElementById("collect_span");
                        if (arr[1] == "1") {
                            // 取消收藏
                            img.removeAttribute("hidden");
                            span.innerHTML = "收藏";

                            obj.id = arr[0]+"_0";
                        } else {
                            // 添加收藏
                            img.setAttribute("hidden", "hidden");
                            span.innerHTML = "已收藏";

                            obj.id = arr[0]+"_1";
                        }
                    } else {
                        Alert(ret_json.msg, 5);
                    }
                } else if (type == 2) {
                    if (ret_json.result != undefined && ret_json.result == "0") {
                        var arr = obj.id.split("_");
                        var img = document.getElementById("collect_img");
                        var span = document.getElementById("collect_span");
                        if (arr[1] == "1") {
                            // 取消收藏
                            img.removeAttribute("hidden");
                            span.innerHTML = "收藏";

                            obj.id = arr[0]+"_0";
                        } else {
                            // 添加收藏
                            img.setAttribute("hidden", "hidden");
                            span.innerHTML = "已收藏";

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
                window.location.href = window.location.href;
            } else {
                Alert("操作失败,"+ret.responseText, 1);
            }
            },
            onFailure: function (ret) {
                Alert("请求失败!", 1);
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
                    window.location.href = window.location.href;
                    Alert("删除成功", 2);
                } else {
                    Alert("操作失败,"+ret.responseText, 1);
                }
            },
            onFailure: function (ret) {
                Alert("请求失败!", 1);
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
                    window.location.href = window.location.href;
                    Alert("删除成功", 2);
                } else {
                    Alert("操作失败,"+ret.responseText, 1);
                }
            },
            onFailure: function (ret) {
                Alert("请求失败!", 1);
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
                    window.location.href = window.location.href;
                    Alert("恢复邮件成功", 2);
                } else {
                    Alert("操作失败,"+ret.responseText, 1);
                }
            },
            onFailure: function (ret) {
                Alert("请求失败!", 1);
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
                        // 粉丝列表的加关注按钮
                        var tag = document.getElementById("add_focus_"+userid);
                        parent_node = tag.parentNode;
                        parent_node.removeChild(tag);
                        var span = document.createElement("span");
                        span.innerHTML = "互相关注";
                        parent_node.appendChild(span);
                        Alert("关注成功", 1);
                    } else if (page_type == 2) {
                        // 个人信息列表的加关注按钮
                        var tag = document.getElementById("add_focus");
                        tag.value = "已关注";
                        tag.setAttribute("class", "margin_right button_disable");
                        tag.setAttribute("onclick", "return false");
                        Alert("关注成功", 1);
                    }
                } else {
                    Alert("添加关注失败,"+ret.responseText, 2);
                }
            },
            onFailure: function (ret) {
                Alert("请求失败!",1);
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
                        // 个人信息列表的加好友按钮
                        var tag = document.getElementById("add_friend");
                        tag.value = "已申请";
                        tag.setAttribute("class", "search_btn1 button_disable");
                        tag.setAttribute("onclick", "return false");
                        Alert("已发送好友申请", 1);
                    }
                } else {
                    Alert("申请失败,"+ret.responseText, 2);
                }
            },
            onFailure: function (ret) {
                Alert("请求失败!",1);
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

function post_article(board, title, reid, content){
    /*  可选参数
    * club_flag: arguments[4],作用是标识文章类型, 默认为 0,表示在版面发表文章
    * jumpto: arguments[5],作用是发表成功后跳转地址,默认为空字符串,发表成功不跳转
    * */
    var club_flag = arguments[4]?arguments[4]:"0";
    var jumpto = arguments[5]?arguments[5]:"";
    var url = "/mobile/forum/request/post_article.php";
    var text_id="text_"+reid;
    var para = "";
    if (club_flag == 0)
        para = "board="+board+"&title="+title+"&reid="+reid+"&content="+content;
    else
        para = "club="+board+"&title="+title+"&reid="+reid+"&content="+content;

    send_article(url, para, jumpto, true);
}

function send_article(url, para, jumpto, is_reply) {
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            onSuccess: function (ret) {
            if (is_reply == true) {
                if (ret.responseText == true) {
                    alert("回复成功");
                    if (jumpto.length)
                        window.location.href = jumpto;
                } else {
                    Alert(ret.responseText,2);
                }
            } else {
                if (ret.responseText == true) {
                    alert("发文成功");
                    if (jumpto.length)
                        window.location.href = jumpto;
                } else {
                    Alert(ret.responseText, 2);
                }
            }
            },
            onFailure: function (x) {

            }
        });
}

function del_article(url, para, jumpto) {
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            asynchronous: true,
            onSuccess: function (ret) {
                var ret_json = eval("(" + ret.responseText + ")");
                if (ret_json.result == 0) {
                    if (ret_json.msg == "jump") {
                        window.location.href = jumpto;
                    } else if (ret_json.msg == "reload") {
                        window.location.href = window.location.href;
                    }
                } else {
                    Alert("删除失败,"+ret_json.msg, 2);
                }
            },
            onFailure: function (x) {
                Alert("请求失败", 1);
            }
        }
    );
}

function post_email(mailto, title, content) {
    // email_type: 0 普通信件 1 律师咨询
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
                Alert("邮件发送成功", 1);
            } else {
                alert(ret.responseText, 5);
            }
            },
            onFailure: function (x) {
                Alert("请求失败!", 1);
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
            // 用户拒绝
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
    $('.dp_list_r').hide();
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
                        new_tag.innerHTML = "目前没有城市信息!";
                        detail.appendChild(new_tag);
                    }
                }
            },
            onFailure: function (x) {
                Alert("请求失败", 1);
            }
        }
    );

    return false;
}

function set_city(obj) {
    var val = obj.id.split("|");
    document.cookie = "dp_city=" + val[1];

    var url = "/mobile/forum/request/dp_setcity.php";
    var para = "city="+val[1];
    var myAjax = new Ajax.Request(url,
        {
            method: "post",
            parameters: para,
            asynchronous: false,
            onSuccess: function (ret) {
                if (val[1] != "all")
                    document.getElementById("city_name").innerHTML = obj.innerHTML;
                document.getElementById("detail").innerHTML = "";
                document.getElementById(val[0]).click();
            },
            onFailure: function (x) {

            }
        }
    );
    return true;
}

function shop_search(event) {
    if(!event)
        event = window.event;//火狐中是 window.event
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
                        new_tag.innerHTML = "目前没有食物分类!";
                        detail.appendChild(new_tag);
                    }
                }
            },
            onFailure: function (x) {
                Alert("请求失败", 1);
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
// 删除元素
}
function Alert(str,time) {
    closewin();
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
    bgObj.style.position="fixed";
    bgObj.style.top="0";
    bgObj.style.background="#000000";
    bgObj.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=25,finishOpacity=75";
    bgObj.style.opacity="0.5";
    bgObj.style.left="0";
    bgObj.style.width = "100%";
    bgObj.style.height = "100%";
    bgObj.style.zIndex = "10000";
    document.body.appendChild(bgObj);
    //创建提示窗口的div
    var msgObj = document.createElement("div")
    msgObj.setAttribute("id","alertmsgDiv");
    msgObj.setAttribute("align","center");
    msgObj.style.background="white";
    msgObj.style.border="1px solid " + bordercolor;
    msgObj.style.position = "fixed";
    msgObj.style.left = "50%";
    msgObj.style.top = "200px";
    msgObj.style.font="12px/1.6em Verdana, Geneva, Arial, Helvetica, sans-serif";
    //窗口距离左侧和顶端的距离
    msgObj.style.marginLeft = "-175px";

    //窗口被卷去的高+（屏幕可用工作区高/2）-150
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
    title.style.height="18px"
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
    //how use: attachEvt(event,'click','objID',functionName)    //函数名称不用引号
    var e=evt||window.event;    //事件,待扩展用
    var evtName=refEventName;   //事件名称,如 click, mouseover等，注意没有on
    var oID=refObjID;       //元素的ID号
    var fn=refFunctionName;     //响应的函数名称,响应函数可以另外在外部定义
    var brsName='ie';       //默认为ie
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
        obj.addEventListener(evtName,fn,false);  //参数为（事件，调用的函数，是否冒泡）
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
        document.getElementById("passwd_btn").text="隐藏";
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
        document.getElementById("passwd_btn").text="显示";
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
        obj.placeholder= "用户名不能含有空格";
        obj.style.backgroundColor="#FFCC80";
        return;
    }
    if (obj.value.length < 3 || obj.value.length > 12){
        obj.value = '';
        obj.placeholder=  "用户名需在3-12字符之间";
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
        err.innerHTML="密码不能含有空格";
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
    var verify_tag = document.getElementById("verify_code");
    check_verify(verify_tag);
    if (document.getElementById("verify_err").hidden == false) {
        return;
    }

    if(obj.value.length!=4){
        err.innerHTML="请输入4数字位验证码";
        err.hidden=false;
        return ;
    }
    err.hidden=true;
}
function check_phone(obj){
    var country=document.getElementById("reg_country_id");
    var err=document.getElementById("phone_err");
    if(obj.value==""){
        err.innerHTML="请输入电话号码";
        err.hidden=false;
        document.getElementById('send_sms_btn').disabled=true;
        return ;
    }
    if(obj.value.length>12){
        err.innerHTML="电话号码超长";
        err.hidden=false;
        document.getElementById('send_sms_btn').disabled=true;
        return ;
    }
    if(country.value=="Not set"){
        document.getElementById('send_sms_btn').disabled=true;
        err.innerHTML="请选择国家";
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
    //                obj.placeholder = '已经注册过的电话号';
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

function check_verify(obj) {
    var para = "num_auth="+obj.value;
    var url = "request/check_verify.php";
    var err_tag = document.getElementById("verify_err");

    var myAjax = new Ajax.Request(url,
        {
            method: 'post',
            parameters: para,
            onComplete: function (ret) {
                var ret_json = eval("("+ret.responseText+")");
                if (ret_json.result == 0) {
                    err_tag.innerHTML = "";
                    err_tag.hidden = true;
                } else {
                    err_tag.innerHTML = ret_json.msg;
                    err_tag.hidden = false;
                }
            },
            onFailure: function (x) {
                err_tag.hidden = false;
                err_tag.innerHTML = "请求服务器失败";
            }
        }
    );
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
    prompt4.setAttribute("style", "display: block");
    prompt4.innerHTML = "正在查询位置...";
    var myAjax = new Ajax.Request(url,
        {
            method:"post",
            parameters:para,
            asynchronous:true,
            onSuccess: function (ret) {

                var ret_json = eval("(" + ret.responseText + ")");
                if (ret_json.result != undefined && ret_json.result == 1) {
                    prompt4.innerHTML = "地址定位成功";
                    tag_lat.value = ret_json.location.lat;
                    tag_lng.value = ret_json.location.lng;
                } else {
                    prompt4.innerHTML = "地址定位失败";
                }

            },
            onFailure: function (x) {
                prompt4.innerHTML = "地址定位失败";
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
    var dp_city = getCookie_wap("dp_city");
    var url = "/mobile/forum/request/dp_rank.php?city="+dp_city;
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

function dp_send_imgtag(url, para, jumpto) {
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
        },
        error: function (ret) {
            Alert("请求失败", 1);
        }
    });

    return result;
}

function dp_get_picture(shop_id, type, pic_num, user_num_id) {
    var url = "/mobile/forum/request/dp_picture_single.php";
    var para = {
        "shop_id":shop_id,
        "type":type,
        "pic_num":pic_num,
        "user_num_id":user_num_id
    };

    $.ajax({
        type: "POST",
        url: url,
        async: false,
        data: para,
        success: function (ret) {
            var ret_json = eval("(" + ret + ")");
            if (ret_json.result == "0") {
                $('#imgbox').attr("src", ret_json.img);
                $('#tag_name').html(ret_json.tag_name);
                $('#pic_num').html(pic_num);
                $('#total_num').html(ret_json.total_num);
            } else {
                alert("图片读取错误");
            }
        },
        error: function (ret) {
            Alert("请求失败", 1);
        }
    });
}

/* 俱乐部权限判断 */
function check_user_perm(member_type, club_type) {
    if (member_type == 2) {
        return true;
    } else {
        alert("你还不是该俱乐部成员，没有发表文章的权限！");
        return false;
    }
}

function passwd_reset_check_confirm(confirm_code) {
    var para = {
        "confirm_code": confirm_code,
    };

    var flag = false;
    $.ajax({
        type: "POST",
        url: "/mobile/forum/request/check_confirm.php",
        async: false,
        data: para,
        success: function (ret) {
            var ret_json = eval("(" + ret + ")");
            if (ret_json.result == 0) {
                flag = true;
            } else {
                flag = false;
                Alert(ret_json.msg, 1);
            }
        },
        error: function (x) {
            flag = false;
            Alert("请求失败", 1);
        }
    });

    return flag;
}

function user_passwd_reset (passwd) {
    var para = {
        "passwd": passwd
    };

    $.ajax({
        type: "POST",
        url: "/mobile/forum/request/user_passwd_reset.php",
        async: false,
        data: para,
        success: function (ret) {
            var ret_json = eval("(" + ret + ")");
            if (ret_json.result == 0) {
                alert("密码修改成功");
                window.location.href = "login.php";
            } else {
                Alert(ret_json.msg, 1);
            }
        },
        error: function (x) {
            Alert("请求失败", 1);
        }
    });
}
