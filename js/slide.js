//$(window).load(function() {
//    $('#slider').nivoSlider({
//        effect:"slideInLeft",
//        slices:15,
//        pauseTime:3000,
//        manualAdvance:false,
//        controlNav:true
//    });
//});


window.onresize = window.onscroll = function() {
    if ($('.main_image').length > 0) {
        var oscrollImg = document.getElementsByClassName('main_image')[0];
        var oLiW = parseInt(document.documentElement.clientWidth);
        console.log(oLiW);
        var iScale = 9 / 16;
        var iH = oscrollImg.style.height = iScale * oLiW + 'px';
        document.title = iH;
    }
}

//$(document).ready(function() {
function setImageEffect(){
        var arr = ["第一个图片","第二个图片","第三个图片"];
        var href = ["", "", ""];
//        if (arguments.length == 1) {
//            arr = arguments[0];
//        }
        arr = arguments[0]?arguments[0]:arr;
        href = arguments[1]?arguments[1]:href;


//    var arr = ["第一个图片", "第二个图片", "第三个图片", "第四个图片", "第五个图片"];

    $(".main_image").touchSlider({
        flexible: true,
        counter: function (e) {
            $(".flicking_con a").removeClass("on").eq(e.current - 1).addClass("on");
            $("#text").html(arr[e.current - 1]);
            $("#text").attr("href", href[e.current - 1]);
        }
    });
}