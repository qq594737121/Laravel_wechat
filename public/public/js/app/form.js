var url = require('url');
var template = require('template');
var main = require('main');
var modalTpl = require('raw!../template/modal.txt');

//模板配置
var tplArr = [];
template.config('escape', false);
tplArr['modal'] = template.compile(modalTpl.replace(/^\s*|\s*$/g, ""));

//获取验证码
function getCode(_dom, url, id) {
    var _mobile = $('input[name="mobile"]').val();
    var code = $('input[name="code"]').val();
    if (_dom.text() != '获取验证码') {
        return false;
    }
    if (_mobile == "") {
        jsAlert('请输入手机号码');
    } else if (/^1[3|4|5|7|8]\d{9}$/.test(_mobile) == false) {
        jsAlert('手机号码格式有误');
    } else {
        _dom.text("校验中");
        $.post(url, {"user_id": id, "mobile": _mobile}, function (json, textStatus) {
            _json = json;
            if (_json.code == 1) {
                var tm = 60;
                var tmr = setInterval(function () {
                    tm--;
                    if (tm > 0) {
                        _dom.text(tm + "秒");
                    } else {
                        _dom.text('获取验证码');
                        clearInterval(tmr);
                        tm = 60;
                        //_dom.attr('onclick','join.getCode()')
                    }
                }, 1000);

                jsAlert('验证码已发送');
            } else if (_json.code == 2) {
                jsAlert(_json.data);
                _dom.text('获取验证码');
            } else if (_json.code == 200) {
                jsAlert(_json.data);
                _dom.text('获取验证码');
            }
        }, 'json');
    }
}
//弹窗
function jsAlert(text, Fun) {
    if (!Fun) {
        Fun = 'm-click="modalAlertClose"'
    }
    var data = {
        "type": "1",
        "ico": 'warn',
        "text": text,
        "Callback": Fun
    }
    $('body').append(tplArr['modal'](data));
}
//表单数据整理
// function ajaxSubmit_o(obj,module,but) { 

//   //var url = $(obj).attr("action") || window.location.href;
//   var callback_name = $(obj).attr("callback");
//   var url = $(obj).attr("action");
//   var data = {};
//   var ipts = {};
//   $('input[name],textarea[name],select[name]',obj).each(function(item){
//       var name = $(this).attr('name');
//       var value = $(this).val();
//       var ipt = $(this).attr('ipt');
//       var isArray = /\[\]$/;
//       if(isArray.test(name)){
//           if(!data[name]){data[name]=[]}
//           if(!ipts[name]){ipts[name]=[]}
//           data[name].push(value);
//           ipts[name].push(value,ipt);
//       }else{
//           data[name] = value;
//           ipts[name] = [value,ipt];
//       }
//   });

//   if(!yzForm(ipts)){return false;}
//   but.attr('isSubmit','false').text('提交中...');
//   $.ajax({
//       url: url,
//       type: "get",
//       data: data,
//       dataType: "json",
//       success: function(ret){
//           //if(ret.sms_success == "1"){
//               //if(isNotEmpty(callback)) {
//                 //_this.id = ret.success_id;
//                 //jsConfirm('提示','请您确认所有填写的信息准确无误！',callback_name);
//                 module[callback_name](ret);
//               //}
//           //}else if(ret.sms_success == "0"){
//               //jsAlert("验证码错误");
//           //}
//       },
//       error:function(ret){

//       }
//   });
// }
//表单数据整理
function ajaxSubmit(obj, module, but, msg) {   //console.info(module)
    console.log(but)
    var but_val = but.html();
    if (but_val == "提交中...") {
        console.info("频次太快");
        return false;
    }
    //var url = $(obj).attr("action") || window.location.href;
    var callback_name = $(obj).attr("callback");
    var url = $(obj).attr("action");
    var formtype = $(obj).attr("f-type");
    var data = {};
    var ipts = {};
    $('input[name],textarea[name],select[name]', obj).each(function (item) {
        var name = $(this).attr('name');
        var value = $(this).val();
        var ipt = $(this).attr('ipt');
        var isArray = /\[\]$/;
        if (isArray.test(name)) {
            if (!data[name]) {
                data[name] = []
            }
            if (!ipts[name]) {
                ipts[name] = []
            }
            data[name].push(value);
            ipts[name].push(value, ipt);
        } else {
            data[name] = value;
            ipts[name] = [value, ipt];
        }
    });

    //alert(url+JSON.stringify(data))
    if (!yzForm(ipts)) {
        return false;
    }
    but.attr('isSubmit', 'false').html('提交中...');
    $.ajax({
        url: url,//+"?openid_test=ojtKJjq93sF3dOoz4lA12MrxtcUk",
        type: "post",
        data: data,
        dataType: "json",
        success: function (ret) {
            console.info(ret)
            //if(ret.sms_success == "1"){
            //if(isNotEmpty(callback)) {
            //_this.id = ret.success_id;
            //jsConfirm('提示','请您确认所有填写的信息准确无误！',callback_name);
            module[callback_name](ret);
            if (msg) {
                but.html(msg)
            } else {
                but.html(but_val);
            }
            //}
            //}else if(ret.sms_success == "0"){
            //jsAlert("验证码错误");
            //}
        },
        error: function (ret) {
            if (msg) {
                but.html(msg)
            } else {
                but.html(but_val);
            }

            alert(JSON.stringify(ret))
        }
    });
}
//方法验证
function yzForm(data) {
    var Ctrue = true;
    var hour = $('select[name="sendplan_hour"]').val();
    for (ipt in data) {
        if (data[ipt][1] == 'true') {
            continue;
        }
        switch (ipt) {
            case "require":
                if (data[ipt][0] == '') {
                    isCtrue('请输入名字');
                    return false;
                }
                break;
            case 'name':
                if (data[ipt][0] == "") {
                    isCtrue('请输入名字');
                    return false;
                }
                break;

            case 'mobile':
                if (data[ipt][0] == "") {
                    isCtrue('请输入手机号码');
                    return false;
                }
                else if (/^1[3|4|5|7|8]\d{9}$/.test(data[ipt][0]) == false) {
                    isCtrue('手机号码格式有误');
                    return false;
                }
                break;

            case 'address':
                if (data[ipt][0] == "") {
                    isCtrue('请输入详细地址');
                    return false;
                }
                break;

            case 'postcode':
                if (data[ipt][0] == "") {
                    isCtrue('请输入邮政编码');
                    return false;
                }
                break;
        }
    }
    function isCtrue(text) {
        Ctrue = false;
        main.alert("", text);
    };
    return Ctrue;
}

exports.getCode = getCode;
exports.jsAlert = jsAlert;
exports.ajaxSubmit = ajaxSubmit;
exports.yzForm = yzForm;