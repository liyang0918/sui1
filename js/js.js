function setEffect() {

//forum_elite.htm/forum_recommend.html页面中的点击显示隐藏效果；
    $('.hot_title').each(function (i) {
        $(this).click(function () {
            $('.hot_title').removeClass('action');
            $(this).toggleClass('action');

            if ($('.hot_list_wrap').eq(i).is(":hidden")) {
                $('.hot_list_wrap').hide();
                $('.hot_list_wrap').eq(i).slideDown("200");
                $('.hot_list_wrap').eq(i).show();
                $('.hot_trigle').eq(i).css("background-image", "url(img/uptrigle.png)");
            } else {
                $('.hot_list_wrap').slideUp("200");
                $('.hot_trigle').eq(i).css("background-image", "url(img/downtrigle.png)");
            }
        })
    });

//news.html中删除>cancel盒子的显示隐藏
    if ($('.cancel')) {
        $('.cancel').click(function () {
            $('.news_cancel_wrap').css('display', 'block');
        });
        $('.yes').click(function () {
            $('.news_cancel_wrap').css('display', 'none');
        });
        $('.no').click(function () {
            $('.news_cancel_wrap').css('display', 'none');
        });
    };
//replyNew_reply的返回按钮）
$('.ds_box').find('a').eq(0).click(function(){
    history.back();
});
//club>club_nav
    $('.club_click').click(
        function () {
            $('.club_nav_wrap').fadeIn(600);
        }
    )
    $('.club_btn_up').click(
        function () {
            $('.club_nav_wrap').fadeOut(600);
        }
    );

//club>club_list_wrap
    var n = 0;
    var iL = 0;
    var iW = $('.club_div li').width() + 10;
    var iLen = $('.club_div li').size() - 1;
    var iH = $(document).height();
    /* 页面ready和ajax请求成功都会调用setEffect(),造成两次执行setInterval(),
     * ready先执行,图片加载位于ajax请求中,所以第一次iLen为-1(列表项li个数为0 size-1=-1 )
     * */
    if (iLen > 0)
        setInterval(move, 3000);
    $('.club_nav_wrap').css('height', iH);
    function move() {
        n++;
        if (n > iLen) {
            n = 0;
            $('.club_div').find('ul').css('left', '10px');
            $('.club_dot span').eq(0).addClass('act');
        }
        iL = -n * iW + 10;
        $('.club_div').find('ul').animate({'left': iL}, 'slow');
        $('.club_dot span').each(function () {
            $('.club_dot span').removeClass('act');
        })
        $('.club_dot span').eq(n).addClass('act');
    };

//dianping_near.html中”附近“等点击的下拉菜单的显示隐藏；
    if( $('.nav_open') ) {
        $('.open_01').click(function () {
            $('.box_mask').show();
            $('.dn_box').hide();
            $('.box_01').show();
        })
        $('.open_02').click(function () {
            $('.box_mask').show();
            $('.dn_box').hide();
            $('.box_02').show();
        })
        $('.open_03').click(function () {
            $('.box_mask').show();
            $('.dn_box').hide();
            $('.box_03').show();
        })
        $('.close_01').click(function () {
            $('.box_mask').hide();
            $('.dn_box').hide();
        })
        $('.close_02').click(function () {
            $('.box_mask').hide();
            $('.dn_box').hide();
        })
        $('.close_03').click(function () {
            $('.box_mask').hide();
            $('.dn_box').hide();
        })
        $('.box_mask').click(function () {
            $('.box_mask').hide();
            $('.dn_box').hide();
        })
    };
//write_dp>点评——写点评里的五角星评分效果
    if( $('.write_dp')){
        $('.comm-star').click(function(){
            $('.comm-star').removeClass('star-cur');
            $(this).prevAll().addClass('star-cur');
            $(this).addClass('star-cur');
        });
    }
}
//针对navone_menu元素实现导航点击出来下拉菜单，点旁边隐藏效果；
function downMenu(clickClass,dropMenu){
    $(clickClass).click(function(event){
        event=event||window.event;
        event.stopPropagation();
        $(dropMenu).toggle();
    });
    $(dropMenu).click(function(){
        $(dropMenu).toggle();
    });
    $(document).click(function(e){
        $(dropMenu).hide();
    });
}
//jiaye_megs_friend.html——“同意”点击的弹窗
function _alertToBeFriend()
{
    if(confirm('是否添加此好友？')){

    } else{

    }
}
//jiaye_megs_club.html——“同意”点击的弹窗
function _alertInClub()
{
    if(confirm('是否允许此人加入？')){

    } else{

    }
}
//jiaye_megs_club.html——“同意”点击的弹窗
function _alertToInClub()
{
    if(confirm('是否此加入俱乐部？')){

    } else{

    }
}
//jiaye_email_trash.html——“清空”点击的弹窗
function _alertClearTrash()
{
    if(confirm('确定清空垃圾箱?')){
        clearTrash();
    } else{

    }

    return false;
}
//jiaye_email_new_content.html——“清除”点击的弹窗
function _alertClearEmail(obj)
{
    // obj.id格式: type_mailid
    if(confirm('确定删除此邮件?')){
        clearEmail(obj.id);
    } else{

    }

    return false;
}
//iaye_email_trash_content.html——“彻底删除”点击的弹窗
function _alertDelEmail(obj)
{
    // obj.id格式: type_mailid
    if(confirm('确定彻底删除此邮件?')) {
        delEmail(obj.id);
    } else {

    }

    return false;
}

//  "恢复"点击的弹窗
function _alertRecoverEmail(obj) {

    // obj.id格式: type_mailid
    if(confirm('确定将此邮件移动到收件箱?')) {
        recoverEmail(obj.id);
    } else {

    }

    return false;
}

//所有输入框获得失去焦点效果；
if($('input').attr('type')=='text'){

    var m='';
    $('input').focus(function(){
        m=$(this).attr('placeholder');
        $(this).attr('placeholder','');
    });
    $('input').blur(function(){
        $(this).attr('placeholder',m);
    });
}

//dianping_picList_chosekind.html中点击效果；
if($('.dp_pic_name').length>0){
    $('.dp_pic_name>li').find('span').click(function(){
        //console.log(1);
        $('.dp_pic_name>li').find('span').removeClass('active');
        $(this).addClass('active');
    });
}


$(document).ready(downMenu($('.navone_menu'), $('.navone_ul')));
$(document).ready(setEffect());
