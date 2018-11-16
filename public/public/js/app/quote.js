
var url = require('url');
var template = require('templateConfig');
var main = require('main');
var form = require('form');
// //模板配置
// var tplArr = [];
// var indexTpl =require('raw!../template/index.txt');
// tplArr['index'] = template.compile(indexTpl.replace(/^\s*|\s*$/g, ""));

function Quote(){
    _this = this;
    this.main = function(){
        this.init();
        this.formVeri();
    }
    //
    this.del_tr= function (ths) {
        ths.parents("tr").remove();
    }

    this.formVeri = function () {

        // $('#searchBtn').on('click', function(e){
        //     e.preventDefault();
        //     form.ajaxSubmit('.submitForm','' ,$(this),'搜索中');
        // })
        // alert(1)
    }
    //
    // this.add_tr= function(ths) {
    //     var data= {};
    //     var _html= tplArr['index'](data);
    //     $("#tb_release_new tbody").append(_html);
    //     do_sel();
    // }
    this.init = function(){
        //this.createCard();
    }
    return this.main();
};
var quote = new Quote();
module.exports = quote;
