
function setImageEffect(){
    $dragBln = false;
    var arr=["第一个图片","第二个图片","第三个图片"];
    if (arguments.length == 1) {
        arr = arguments[0];
    }
    alert("0000");

    $(".main_image").touchSlider({
        flexible : true,
        speed : 200,
        paging : $(".flicking_con a"),
        counter : function (e){
            $(".flicking_con a").removeClass("on").eq(e.current-1).addClass("on");
            $("#text").html(arr[e.current-1]);
        }
    });
    alert("1111");

    $(".main_image").bind("mousedown", function() {
        $dragBln = false;
    });

    $(".main_image").bind("dragstart", function() {
        $dragBln = true;
    });

    $(".main_image a").click(function(){
        if($dragBln) {
            return false;
        }
    });

    timer = setInterval(function(){
        $("#btn_next").click();
    }, 5000);

    $(".main_visual").hover(function(){
        clearInterval(timer);
    },function(){
        timer = setInterval(function(){
            $("#btn_next").click();
        },5000);
    });

    $(".main_image").bind("touchstart",function(){
        clearInterval(timer);
    }).bind("touchend", function(){
        timer = setInterval(function(){
            $("#btn_next").click();
        }, 5000);
    });

}
