function setEffect() {

//forum_elite.htm/forum_recommend.htmlҳ���еĵ����ʾ����Ч����
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

//news.html��ɾ��>cancel���ӵ���ʾ����
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
//replyNew_reply�ķ��ذ�ť��
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
    /* ҳ��ready��ajax����ɹ��������setEffect(),�������ִ��setInterval(),
     * ready��ִ��,ͼƬ����λ��ajax������,���Ե�һ��iLenΪ-1(�б���li����Ϊ0 size-1=-1 )
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

//dianping_near.html�С��������ȵ���������˵�����ʾ���أ�
    if( $('.nav_open yp_img') ) {
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
//write_dp>��������д����������������Ч��
    if( $('.write_dp')){
        $('.comm-star').click(function(){
            $('.comm-star').removeClass('star-cur');
            $(this).prevAll().addClass('star-cur');
            $(this).addClass('star-cur');
        });
    }
}
//���navone_menuԪ��ʵ�ֵ���������������˵������Ա�����Ч����
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
//jiaye_megs_friend.html������ͬ�⡱����ĵ���
function _alertToBeFriend()
{
    if(confirm('�Ƿ���Ӵ˺��ѣ�')){

    } else{

    }
}
//jiaye_megs_club.html������ͬ�⡱����ĵ���
function _alertInClub()
{
    if(confirm('�Ƿ�������˼��룿')){

    } else{

    }
}
//jiaye_megs_club.html������ͬ�⡱����ĵ���
function _alertToInClub()
{
    if(confirm('�Ƿ�˼�����ֲ���')){

    } else{

    }
}
//jiaye_email_trash.html��������ա�����ĵ���
function _alertClearTrash()
{
    if(confirm('ȷ�����������?')){
        clearTrash();
    } else{

    }

    return false;
}
//jiaye_email_new_content.html���������������ĵ���
function _alertClearEmail(obj)
{
    // obj.id��ʽ: type_mailid
    if(confirm('ȷ��ɾ�����ʼ�?')){
        clearEmail(obj.id);
    } else{

    }

    return false;
}
//iaye_email_trash_content.html����������ɾ��������ĵ���
function _alertDelEmail(obj)
{
    // obj.id��ʽ: type_mailid
    if(confirm('ȷ������ɾ�����ʼ�?')) {
        delEmail(obj.id);
    } else {

    }

    return false;
}

//  "�ָ�"����ĵ���
function _alertRecoverEmail(obj) {

    // obj.id��ʽ: type_mailid
    if(confirm('ȷ�������ʼ��ƶ����ռ���?')) {
        recoverEmail(obj.id);
    } else {

    }

    return false;
}

//�����������ʧȥ����Ч����
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

$(document).ready(downMenu($('.navone_menu'), $('.navone_ul')));
$(document).ready(setEffect());
