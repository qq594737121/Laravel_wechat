var cont = 60;
var btn = true;
var ck = 1;
var sex = 1;
var flg= true;
$(function () {
    var u = navigator.userAgent;
    if (u.indexOf('iPhone') > -1) {
        $(".ctp").focus(function(){
            $("#propic").hide();
        });
        
        $(".ctp").blur(function(){
            $("#propic").show();
        });
    }
    
    
    $("#regbtn").click(regEvent);
    
    $("#btncode").bind("click",codeEvent);

    $(".readme span").bind("click", function () {
        $("#p1").hide();
        $("#p2").fadeIn(300);
    })
    $("#okbtn").bind("click", function () {
        $("#p2").fadeOut(300);
        $("#p1").show();
    })
    $("#closem").bind('click', function () {
        $("#mk,#message").fadeOut(300);
    })
    $("#female").bind('click', function () {

        if (sex == 0) {
        } else {
            sex = 0;
            $(".ckfemale").toggle();
            $(".ckmale").toggle();
        }
    })
    $("#male").bind('click', function () {

        if (sex == 1) {
        } else {
            sex = 1;
            $(".ckmale").toggle();
            $(".ckfemale").toggle();
        }
    })
    $("#xieyi").bind('click', function () {
        $(".ckxy").toggle();
        if (ck == 0) {
            ck = 1;
        } else {
            ck = 0;
        }
    })
    $("#hyxy").bind('click', function () {
        $("#xy").show();
    })
    $("#off").bind('click', function () {
        $("#xy").hide();
    })
    $("#goBack").bind('click', function () {
        $("#xy").hide();
    })
    var currYear = (new Date()).getFullYear();
    

});

function times() {
    if (cont > 0 && cont < 61) {
        var st = setTimeout(function () {
            cont--;
            btn = false;
            $("#btncode").css({"background-color": "#b72a2f", "color": "#fff"});
            $("#btncode").html("( " + cont + " ) 重新发送");
            times();
        }, 1000)
    } else {
        $("#btncode").css({"background-color": "#f4ebc8", "color": "#aa9d69"});
        $("#btncode").html("获取验证码");
        cont = 60;
        btn = true;
    }
}
