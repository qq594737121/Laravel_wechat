require('../../less/app.less');

var base = require('base');
//模板配置
var tplArr = [];
var template = require('templateConfig');
var modalTpl =require('raw!../template/modal.txt');
tplArr['modal'] = template.compile(modalTpl.replace(/^\s*|\s*$/g, ""));
var baseTpl =require('raw!../template/base.txt');
tplArr['base'] = template.compile(baseTpl.replace(/^\s*|\s*$/g, ""));
function Main(){
    _this = this;
    this.main=function(){
        this.event(this,'tap','m-click');
        this.init();
    }
    //事件封装
    this.event = function(_this,type,name){
        $(document).on(type,'['+name+']',function(e){
          //var ths = $(this)[0];
          var event = $($(this)[0]).attr(name);
          var Fun= event.split(',');
          _this[Fun[0]]($($(this)[0]),Fun[1],e);
        });  
    }
    //区分全半角判断文字长度
    this.getByteLen = function(val){
      if(!val){val=0}
      var len = 0;
      for (var i = 0; i < val.length; i++) {
        if (val.charAt(i).match(/[^\x00-\xff]/ig) != null) //全角
          len += 2; //如果是全角，占用两个字节
        else
          len += 1; //半角占用一个字节
      }
      return len/2;
    }
    //获取url属性值
    this.GetQueryString = function(name){
         var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
         var url = decodeURI(window.location.search);
         var r = url.substr(1).match(reg);
         if(r!=null)return  unescape(r[2]); return null;
    }
    //提示弹出框
    this.alert = function(title, content){
      var data = {
        "Ttype":"alert",
        title: title,
        content: content
      }
      var alert = $(tplArr['modal'](data)).dialog("show");
      $('body').append(alert);
    }   
    //公共get方法
    this.get = function(url,data,Fun){  
      //data.openid_test= "ojtKJjq93sF3dOoz4lA12MrxtcUk";   
      console.info(data)   
      $.get(url,data,function(ret){
        Fun(ret);
      },'json');
    }
    //公共post方法
    this.post = function(url,data,Fun){ 
      //data.openid_test= "ojtKJjq93sF3dOoz4lA12MrxtcUk";
      $.post(url,data,function(ret){
        Fun(ret);
      },'json');
    }
    //提示弹出框
    this.alert = function(options){
      var data = {
        "Ttype":"alert",
        "title": options.title || "标题",
        "closeFun": options.loseFun || 'm-click="alertClose"',
        "content": options.content || "内容",
      }
      console.log(data);
      var alert = $(tplArr['modal'](data)).dialog("show");
      $('body').append(alert);
    }
    //确认弹出框
    this.confirm = function(options){
      var data = {
        "Ttype":"confirm",
        "title": options.title || "标题",
        "eventFun": options.Fun || 'm-click="dialogClose"',
        "closeFun": options.closeFun || 'm-click="dialogClose"',
        "content": options.content || "内容",
      }
      var alert = $(tplArr['modal'](data)).dialog("show");
      $('body').append(alert);
    }
    //题目弹出框
    this.questions = function(options){
      var data = {
        "Ttype":"questions",
        "mark":options.mark,
        "title": options.title || "标题",
        "eventFun": options.Fun || 'm-click="dialogClose"',
        "closeFun": options.closeFun || 'm-click="dialogClose"',
        "content": options.content || "内容",
        "left":options.left || "提交",
        "right":options.right || "取消",
        "starId":options.starId
      }
      var alert = $(tplArr['modal'](data)).dialog("show");
      $('body').append(alert);
    }
    //关闭提示弹出层
    this.dialogClose = function(){
      $('.ui-dialog').remove();
    }
    //关闭上级弹出层
    this.alertClose = function(){
      $('#alert_dialog').remove();
    }
    //创建菜单
    this.createMenu = function(menuData){
      //调接口获取菜单列表
      //if(menuData){
        var data = {
          "Ttype": "menu",
          "menu": menuData
        }
        //console.log(pageMenu);
        if("undefined" == typeof pageMenu){ 
            console.log(menuData);
            pageMenu = {"active":[menuData.dataList[0].id, menuData.dataList[0].list[0].id]}
        }
        data.menu.active = pageMenu.active;
        //$('.wrapper').prepend(tplArr['base'](data)); 
        console.log(data);
        $('.main-sidebar').html(tplArr['base'](data)); 
      //}
      
    }
    //创建公共头
    this.createHeader = function(data){
        var data = {
          "Ttype": "header",
          "data": data,
        }
        $('.main-header').html(tplArr['base'](data)); 
    }
    this.init = function(){
      this.createMenu(base.menuData);
      this.createHeader({"username":"jspsj"});
     //$(function() {
        //attachFastClick.attach(document.body);
     //}); 
    } 
    return this.main();
};
//exports.orientLayer = orientLayer;

var main = new Main();
module.exports = main;