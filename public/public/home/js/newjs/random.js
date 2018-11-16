function checkMobile(s) {
    var regu = /^[1][3-8][0-9]{9}$/;
    var re = new RegExp(regu);
    if (re.test(s)) {
        return true;
    } else {
        return false;
    }
}
function checkName(val) {
    reg = /^[a-zA-Z]+$|^[\u4e00-\u9fa5]+$/;
 
 if(!reg.test(val)){
 
   return false;
 
 }else{
 
return true;
 
 }
 
}
function trim(str) {
    str = str.replace(/^(\s|\u00A0)+/, '');
    for (var i = str.length - 1; i >= 0; i--) {
        if (/\S/.test(str.charAt(i))) {
            str = str.substring(0, i + 1);
            break;
        }
    }
    return str;
}
function getquest(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
       return null;
};
