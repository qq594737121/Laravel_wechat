
var url = require('url');
var template = require('templateConfig');
var main = require('main');

// //模板配置
var tplArr = [];
var indexTpl =require('raw!../template/index.txt');
tplArr['index'] = template.compile(indexTpl.replace(/^\s*|\s*$/g, ""));

function Index(){
    _this = this;
    this.main = function(){
        main.event(this,'click','i-click');
        main.event(this,'change','i-change');
        this.init();
    }
    //
    this.del_tr= function (ths) {
        ths.parents("tr").remove();
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
var index = new Index();