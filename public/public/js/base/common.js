//common add by golan 2017-3-12
function Common(){
	__this= this;
	this.main= function(){  
	}
	//注册页面方法
	this.event= function(__this,type,name){
		$(document).on(type,'['+name+']',function(e){
			var event= $($(this)[0]).attr(name);
			var Fun= event.split(',');
			__this[Fun[0]]($($(this)[0]),Fun[1],e);
		});
	}
	//公共get方法
	this.get= function(url, data, func){
		$.get(url, data, function(res){
			func(res)
		})
	}
	//公共post方法
	this.post= function(url, data, func){
		$.post(url, data, function(res){
			func(res)
		})
	} 
	//获取url属性值
    this.GetQueryString = function(name){
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var url = decodeURI(window.location.search);
		var r = url.substr(1).match(reg);
		if(r!=null)return  unescape(r[2]); return null;
   }
	//初始化上传
	this.uploadInit= function(_json){
		if(!_json.obj) return;
		if(!_json.url) _json.url= '/materialnew/upload/material-image';
		if(!_json.allowedTypes) _json.allowedTypes= 'image/*';
		 _json.obj.dmUploader({
          url: _json.url,
          dataType: 'json',
          allowedTypes: _json.allowedTypes,  
          onNewFile: function(id,file){
            //$.danidemo.addFile('.waterfall', id, file);
          },
          onUploadSuccess: function(id, data){ 
            if(!!_json.callBack){ 
            	_json.callBack(_json.obj, id, data)
            }
          },
          onUploadError: function(id, message){ 
          	__this.confirmBox({content: "上传失败:"+message})
          	// _json.callBack(id,message)
          },
          onComplete: function(){
            //$('.waterfall').masonry('reload');
          }
        });
	}
	//初始化模板
	this.initTpl= function(tplSrc, callBack){
		var tplArr = {};
		  template.config('escape',false); 
		  for(var i in tplSrc){ 
		      var obj = { src: tplSrc[i], id: i };
		      __this.ajaxrequest(obj.src, 'get', true, null, function (http, obj) {
		        //预编译模版
		        tplArr[obj.id] = template.compile(http.responseText.replace(/^\s*|\s*$/g, ""));
 				callBack(tplArr[obj.id],obj.id)
		      }, obj);
		  } 
		 //  for( var i in tplSrc){
		 //  	var obj = { src: tplSrc[i], id: i };
		 //    var	new_element= document.createElement("script");
			// new_element.setAttribute("type","text/html");
			// new_element.setAttribute("src",obj.src);
			// new_element.setAttribute("id",obj.id);
			// document.body.appendChild(new_element);
		 //  }
	}
	//得到ajax对象
	this.getajaxHttp= function() {
	    var xmlHttp;
	    try {
	      // Firefox, Opera 8.0+, Safari
	      xmlHttp = new XMLHttpRequest();
	    } catch (e) {
	      // Internet Explorer
	      try {
	        xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
	      } catch (e) {
	        try {
	          xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	        } catch (e) {
	          alert("您的浏览器不支持AJAX！");
	          return false;
	        }
	      }
	    }
	    return xmlHttp;
	}
   	//发送ajax请求
	this.ajaxrequest= function(url, methodtype, con, parameter, functionName, obj) {
	    var xmlhttp = __this.getajaxHttp();
	    xmlhttp.onreadystatechange = function () {
	      if (xmlhttp.readyState == 4) {
	        //HTTP响应已经完全接收才调用
	        functionName(xmlhttp, obj);
	      }
	    };
	    xmlhttp.open(methodtype, url, con);
	    xmlhttp.send(parameter);
	}
    //弹出层
    this.confirmBox = function(data){
        if(!data){var data = {"event":"","content":""}
        }
    	var c_div='<h4 style="line-height: 130px; text-align: center; margin:0; font-size: 16px;">', c_div_end='</div>';
        var sh= new jsbox({
            onlyid:"map",
            title:false,
            conw:'300',
            conh:'200',
            FixedTop:70,
            content: c_div+ data.content+ c_div_end,
            footer: true,
            Event_button:data.event,
            range:false,
            mack:true
        }).show();
    }
    //分页
	//obj, total, pn, click, event, url
	this.getPages= function(data){
		// var _obj= data.obj; 
		data.type= "pagination";
		data.list= this.getPageList(data.total, data.page);
		if(!data.list) return false
		__this.pageData= data;
		// _obj.html(tplArr['base'](data));
		return data;
	}
	this.getPageList= function(total, pcenter){ 
		var pcenter= parseInt(pcenter), _e= parseInt(total), ret= [];
		  if(_e<=10){
		     for(var i=0; i<_e; i++) ret.push(i);
		  }
		  else{
		     //中间
		     if(pcenter-5> 0 && pcenter+4< _e) { 
		        for(var i=pcenter-5; i<pcenter+5; i++) {ret.push(i);}
		     } 
		     //末十条
		     else if(pcenter>= _e-10 && pcenter> 5){ 
		        for(var i= _e-10; i< _e; i++) {ret.push(i);}
		      }
		     //头十条
		     else if(pcenter< 10){  
		        for(var i=0; i<10; i++) {ret.push(i);}
		      } 
		     //防错
		     else{ 
		        for(var i= pcenter-5; i< pcenter+5; i++) {ret.push(i);}
		      }
		  }
		return ret;
	}
	//下一页
	this.next= function(ths){
		var data= __this.pageData, pcenter= parseInt(data.page)+ 10;
		if(pcenter> parseInt(data.total)+ 5) return;
		data.page= pcenter;
		var _data= __this.getPages(data); 
		return _data;
	}
	//上一页
	this.prev= function(ths){
		var data= __this.pageData, pcenter= parseInt(data.page)- 10;
		if(pcenter< -5) return;
		data.page= pcenter;
		var _data= __this.getPages(data);
        return _data;
	} 
	return this.main();
}



//获取缓存
function cmtCookies(obj){
	var _obj= $(".cmt_display");
	if(!!obj) {
		_obj= obj;
	}
    var name = sessionStorage.getItem("cmtCheckStorage"); 
    if("0"== name|| 0== name){
        _obj.css("display","none");
    }else{
        _obj.css("display","block");
    }
}

function setCmtCookie(Authority){ 
    if(typeof(Authority)!="undefined"){
        sessionStorage.setItem("cmtCheckStorage",Authority);   //设置缓存
    }
}
//nav link
function createNav(arr, obj){ 
                if(arr&& arr.length>0){
                    var str= "<ul class='navLink'>";
                    $.each(arr, function(i,v){
                        str+= "<li><a target='_blank' href='"+v.url+"'>"+v.title+"</a></li>" 
                    })
                    str+="</ul>";
                    obj.append(str)
                }
            }