var main = require('main');
function Jsbox(){
    _this = this;
    this.main = function(){
        this.event(this,'click','j-click');
        $(window).resize(_this.jsbox_csh);
        this.init();
        //var data = {"type":"7"}
        //$('body').append(tplArr['index'](data));
    }
    this.event = function(_this,type,name){
        $(document).on(type,'['+name+']',function(e){
          //var ths = $(this)[0];
          var event = $($(this)[0]).attr(name);
          var Fun= event.split(',');
          _this[Fun[0]]($($(this)[0]),Fun[1],e);
        });     
    }
    this.optionInit = function(_options){
    	this.options_ = $.extend({
				 onlyid:"",//弹出层ID
				 content:false,//内容
				 url:"",//数据地址
				 url_css:false,//样式表地址
				 iframe:false,//使用iframe
				 ajax:false,//使用ajax
				 loads:false,//使用load
				 title:false,//标题
				 footer:false,//底部
			     drag:false,//拖动
			     slide:false,//弹出向下滚动
				 conw:200,//宽度
				 //conh:400,//高度
				 FixedTop:false,//弹出层出现位置
				 FixedLeft:false,//弹出层出现位置
				 Opacity:.4,//透明度
				 mack:false,//遮罩
				 range:false,//移动范围
				 Save_button:"",//保存按钮
				 Ok_button:"",//确定按钮
				 sd:"slow",//弹出速度
				 Close:true,
				 buttonCon:false,
				 functions:false,//返回函数
				 Fun:false,//加载完毕回调方法
				 FunData:false,
				 loadIcon:'cj/jsbox/images_jsbox/loading.gif',//加载提示图片路径
				 error:'<h3>Error 404</h3>'//ajax报错信息
		  }, _options || {});

		  this.zw = document.documentElement.clientWidth || document.body.clientWidth;
		  this.zh = document.documentElement.clientHeight || document.body.clientHeight;
		  this.optionsID = new Date().getTime();
		  //var options = ".jsbox";
		  this.options = ".jsbox"+this.optionsID;
    }
    this.show = function(_options){
    	var _this = this;
    	this.optionInit(_options);

    	$("#"+this.options_.onlyid).remove();

		  var wc="";var mw = "";
		  (_this.options_.FixedLeft!=false)?wc = _this.options_.FixedLeft:wc = "50%";mw = _this.options_.conw/2;
		  console.log(mw);
		  var hc="";
		  (_this.options_.FixedTop!=false)? hc = _this.options_.FixedTop:hc = this.zh/2- 150;

		  (_this.options_.buttonCon!=false)? _this.options_.buttonCon = _this.options_.buttonCon:_this.options_.buttonCon = "确定";

		  var jsboxContent = $('.jsboxContent');
		  var loading = $('<div class="loading"></div>');
		  //var urlcss = $('<link rel="stylesheet" type="text/css" href="../../../../js/plug-in/jsbox/'+_this.options_.url_css+'.css" />');
		  var save_button = $("<div class='jsboxAn_save'><input class='btn btn-primary btn-flat btn-block' type='button' "+_this.options_.Save_button+" value='"+_this.options_.buttonCon+"'></div>");
		  var ok_button = $("<div class='jsboxAn_ok'><input class='btn btn-primary btn-flat btn-block' type='button' "+_this.options_.Ok_button+" value='"+_this.options_.buttonCon+"'></div>");
		  if(_this.options_.Close == true){
		       var Close = '<a href="javascript:void(0)" title="关闭" mid="'+_this.options_.onlyid+'" oid="'+_this.options+'" j-click=\"jsboxClose\" class="jsbox_close">';
		  }else{
			   var Close = '<a style="display:none;" href="javascript:void(0)" title="关闭" mid="'+_this.options_.onlyid+'" oid="'+_this.options+'" j-click=\"jsboxClose\" class="jsbox_close">';
			  }
		  var boxtitle = $('<h2 class="jsboxTitle">'+_this.options_.title+'</h2>'+Close+'</a>');
		  var boxfooter = $("<div class='jsboxFooter'><div mid='"+_this.options_.onlyid+"' oid=\""+_this.options+"\" j-click=\"jsboxAnCancel\" class='jsboxAn_Cancel'><input class='Cancel btn btn-default btn-flat btn-block' type='button' value='取消'></div></div>");
		  var zon = "<div class=\"popupComponent "+_this.options_.onlyid+"_lightBox\" id=\"lightBox\"><div class=\"popupCover\"></div></div>";
		  var con = "<div id='"+_this.options_.onlyid+"' class='jsbox jsbox"+this.optionsID+"'>"+
					"<div class='jsboxContent' style='width:"+_this.options_.conw+"px;height:"+_this.options_.conh+"px;'></div>"+
					"</div>";

		  if(_this.options_.mack != false){
		  	  var isclass = $('.popupComponent').is("."+_this.options_.onlyid+"_lightBox");
		  	  if(!isclass){
                  var leng = $('.popupComponent').length+1;
	              var $zon = $(zon).appendTo('body').fadeTo("500", 1);
	              $zon.css({'zIndex':(leng*100+1000)-10});
	              var html_h = $("body").height();
	              var wid_h = $(window).height();
	              var mack_h = '';
	              if(html_h>wid_h){mack_h=html_h}else{mack_h=wid_h};
	              $('.'+_this.options_.onlyid+'_lightBox').show().height(mack_h);
		  	  }

		  }

          var Tollp = $("html").scrollTop() || document.body.scrollTop | document.documentElement.scrollTop;
          var leng = $('.jsbox').length+1;
          var $con = $(con).appendTo('body');
          $con.css({'zIndex':(leng*100+1000)});
          //$('body').css('overflow','hidden');


          $(_this.options).css({top:hc+Tollp,left:wc,marginLeft:-mw}); //修改左定位：left:wc
          var t = hc+Tollp-50;
          //$('.jsboxContent').css('margin-top',t+'px');
          $(".topLeft,.topCenter,.topRight,.centerLeft,.centerRight,.bottomLeft,.bottomCenter,.bottomRight").fadeTo(20, _this.options_.Opacity);


		  var iframeh;
		  if(_this.options_.title != false && _this.options_.footer != false){
			  $('.jsboxContent',_this.options).append(boxtitle);
			  $('.jsboxContent',_this.options).append(boxfooter);
			  if(_this.options_.Save_button != ""){$(".jsboxFooter",_this.options).prepend(save_button);}
			  if(_this.options_.Ok_button != ""){$(".jsboxFooter",_this.options).prepend(ok_button);}
			  iframeh = _this.options_.conh - 83;
		  }else if(_this.options_.title != false){
			  $('.jsboxContent',_this.options).append(boxtitle);
			  iframeh = _this.options_.conh - 30;
		  }else if(_this.options_.footer != false){
			  $('.jsboxContent',_this.options).append(boxfooter);
			  iframeh = _this.options_.conh - 48;
			  if(_this.options_.Save_button != ""){$(".jsboxFooter",_this.options).prepend(save_button);}
			  if(_this.options_.Ok_button != ""){$(".jsboxFooter",_this.options).prepend(ok_button);}
		  }else{iframeh = _this.options_.conh;}

		  var iframe = $('<iframe name="jsboxFrame" class="iframebox" style="width:100%; height:'+iframeh+'px;" frameborder="no" border="0"></iframe>');
		  var ajaxcon = $('<div class="jtycom" style="width:100%; height:'+iframeh+'px;"></div>');
		  var loaddiv = $('<div class="loaddiv" style="display:block; height:'+iframeh+'px;"></div>');
		  var content = $('<div class="loaddiv" style="display:block; height:'+iframeh+'px;">'+_this.options_.content+'</div>');

		    if(_this.options_.url != false && _this.options_.iframe != false){
				$('.jsboxContent',_this.options).append(loading);

				if(_this.options_.footer != false){
				    $(".jsboxFooter",_this.options).before(iframe);
				}else{
				    $('.jsboxContent',_this.options).append(iframe);
				}

				$('.iframebox',_this.options).hide().attr("src",_this.options_.url);
				$('.iframebox',_this.options).load(function(){
				    $(this).show();
					$(".jsboxFooter",_this.options).show();
					loading.fadeTo(500,0).hide();
				});
		    }else if(_this.options_.url != false && _this.options_.ajax != false){
				$('.jsboxContent',_this.options).append(loading);

				$.ajax({
					   url:_this.options_.url,
					   type:'GET',
					   dataType:'json',
					   error:function(){
						 $('.jsboxContent',_this.options).html(_this.options_.error);
					   },
					   success:function(date){

						   if(_this.options_.url_css != false){
							   //加载样式表
							   if ($("link[href$='"+_this.options_.url_css+".css']").length == 0){
								 var css_href = _this.options_.url_css+'.css';
								 var styleTag = document.createElement("link");
								 styleTag.setAttribute('type', 'text/css');
								 styleTag.setAttribute('rel', 'stylesheet');
								 styleTag.setAttribute('href', css_href);
								 $("head")[0].appendChild(styleTag);
								}
						   }

						   $('.jsboxContent',_this.options).append(ajaxcon);
						   loading.fadeTo(500,0).hide();
						   if(_this.options_.footer != false){
							    $(".jsboxFooter",_this.options).show();
								$('.jsboxContent',_this.options).append(boxfooter);
						   }else{
								$('.jsboxContent',_this.options).append(ajaxcon);
						   }
						   if(_this.options_.content!=false){_this.options_.content(date)};
						}
			    });

		    }else if(_this.options_.url != false && _this.options_.loads != false){
			    //if(_this.options_.url_css!=false){$('head').append(urlcss)}
				$('.jsboxContent',_this.options).append(loading);
				if(_this.options_.url_css != false){
					//加载样式表
					if ($("link[href$='"+_this.options_.url_css+".css']").length == 0){
					 var css_href = _this.options_.url_css+'.css';
					 var styleTag = document.createElement("link");
					 styleTag.setAttribute('type', 'text/css');
					 styleTag.setAttribute('rel', 'stylesheet');
					 styleTag.setAttribute('href', css_href);
					 $("head")[0].appendChild(styleTag);
					}
				}

				if(_this.options_.footer != false){
				    $(".jsboxFooter",_this.options).before(loaddiv);
				}else{
				    $('.jsboxContent',_this.options).append(loaddiv);
				}

				//$('.jsboxContent',options).append(loading);
				$('.loaddiv',_this.options).load(_this.options_.url,function(){
					   loading.hide();
					   $(".jsboxFooter",_this.options).show();

					   if(_this.options_.Fun){
					   	  if(_this.options_.FunData){
					   	     alert(_this.options_.FunData);
                             _this.options_.Fun(_this.options_.onlyid,_this.options_.FunData);
					   	  }else{
					   	  	 _this.options_.Fun(_this.options_.onlyid);
					   	  }
					   }
					   if(_this.options_.functions != false){
						    //loadfun();
							$('.loaddiv').css({"background":"none"});
					   }

					});
			  }else{

				   if(_this.options_.footer != false){
					    $(".jsboxFooter",_this.options).before(content);
					}else{
					    $('.jsboxContent',_this.options).append(content);
					}
				    $(".jsboxFooter",_this.options).show();
			  }

			if(_this.options_.Fun){
				  if(_this.options_.FunData){
			         _this.options_.Fun(_this.options_.onlyid,_this.options_.FunData);
			   	}else{
			   	  	 _this.options_.Fun(_this.options_.onlyid);
			   	}
			}

		   //if(!$show.is(":animated") ){
			 //if(_this.options_.drag != false){_this.jsbox_yd()}else{_this.jsbox_hd(_this.options_.sd)}
			 //if(_this.options_.slide != false){_this.jsbox_hdx()}else{_this.jsbox_hd()}
		   //}
    }
    this.jsboxAnCancel = function(ths){
  		this.closeBox(ths);
    }
    this.jsboxClose = function(ths){
    	this.closeBox(ths);
    }
    this.closeBox = function(ths){
    	var mid = ths.attr('mid');
    	var oid = ths.attr('oid');
		$(ths).parents(oid).remove();
		$('.'+mid+'_lightBox').remove();
        $('body').css('overflow','auto');
    }
   	this.jsbox_csh = function(){
		var zw = document.documentElement.clientWidth || document.body.clientWidth;
	    var zh = document.documentElement.clientHeight || document.body.clientHeight;
	}
    //移动
	this.jsbox_yd = function(){
		var _move = false;//移动标记
		var _x,_y;//鼠标离控件左上角的相对位置

		$(".jsboxTitle",_this.options).mousedown(function(e){
			_move=true;
			_x=e.pageX-parseInt($(_this.options).css("left"));
			_y=e.pageY-parseInt($(_this.options).css("top"));

			//z-index
			if($(".index_z").length == 0){
			   $("body").append('<input class="index_z"type="hidden" value="1300"/>');
			}
			var zzs = $(".index_z").val()*1+1;
			var zjleng = $(".index_z").val(zzs);
			$(_this.options).css({"z-index":zzs});


			$('.ud').text(_y);

		});

		var zsw = $(_this.options).width();
		var zsh = $(_this.options).height();

		var zws = document.documentElement.clientWidth || document.body.clientWidth;
		var zhs = document.documentElement.clientHeight || document.body.clientHeight;
		var obje = $(_this.options);
		$(document).mousemove(function(e){
			if(_move){

				var ws = zws-zsw;
				var hs = zhs-zsh;
				var x=e.pageX-_x;//移动时根据鼠标位置计算控件左上角的绝对位置
				var y=e.pageY-_y;
				if(_this.options_.range!=false){
					if(x <= 0){x = 0}
					if(x >= ws){x = ws}
					if(y <= 0){y = 0}
					if(y >= hs){y = hs}
				}
				obje.css({top:y,left:x});//控件新位置
			}
		}).mouseup(function(){
		   _move=false;
		   return false;
		});
	}

	this.jsbox_hd = function(sd){
		$(_this.options).fadeIn(sd);
	}
	this.jsbox_hdx = function(){
		$(_this.options).fadeIn('slow').animate({opacity:'100',top:"+=50"},'slow');
	}

    this.init = function(){
        // this.create_con();
        // if(userData.showlist == "0" && listId != null){
        //     _this.showlist(listId);
        // }
    }
    return this.main();
};

var jsbox = new Jsbox();
//module.exports = function(){return 123};
module.exports = jsbox;