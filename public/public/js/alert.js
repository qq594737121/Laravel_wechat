/* prompt start */
	var iprompt = function() {};
	var i_callback = function() {};
	iprompt.prototype = {
		alert: function(message, callback) {
			if(typeof(callback) == 'function') {
				i_callback = callback;
			}
			var ipromptDiv = "<div class='iprompt' style='z-index=1000;'>"+
			"<div class='ipromptBox  zoomIn  animated'>" +
			"<p><span>" + message + "</span></p><span class='alertOKBtn'>确定</span></div></div>"
				//将div节点添加到body中
			$("body").append(ipromptDiv);
			//关闭滚动条
			var tally = document.getElementsByTagName("html")[0].getAttribute("tally");
			if(tally == 0 || tally == null) {
				document.getElementsByTagName("html")[0].setAttribute("tally", "1");
				document.getElementsByTagName("html")[0].setAttribute("style", "overflow: hidden;");
			} else {
				document.getElementsByTagName("html")[0].setAttribute("tally", parseInt(tally) + 1);
			};
		},
		confirm: function(message, callback) {
			if(typeof(callback) == 'function') {
				i_callback = callback;
			}
			var ipromptDiv = "<div class='iprompt'><div class='ipromptBox  zoomIn  animated'>"+
			"<p><span>" + message + "</span></p>" +
				"<span class='confirmCancelBtn'>取消</span><span class='confirmOKBtn'>确定</span></div></div>"
				//将div节点添加到body中
			$("body").append(ipromptDiv);
			//关闭滚动条
			var tally = document.getElementsByTagName("html")[0].getAttribute("tally");
			if(tally == 0 || tally == null) {
				document.getElementsByTagName("html")[0].setAttribute("tally", "1");
				document.getElementsByTagName("html")[0].setAttribute("style", "overflow: hidden;");
			} else {
				document.getElementsByTagName("html")[0].setAttribute("tally", parseInt(tally) + 1);
			};
		},
		bubble: function(message) {
			document.getElementsByClassName("bubble")[0] && document.getElementsByTagName("body")[0].removeChild(document.getElementsByClassName("bubble")[0]);
			var bubbleSpan = document.createElement("span");
			bubbleSpan.innerHTML = message;
			//创建p
			var bubbleP = document.createElement("p");
			bubbleP.className = "bubble zoomIn animated";
			bubbleP.appendChild(bubbleSpan);
			//将div节点添加到body中
			document.getElementsByTagName("body")[0].appendChild(bubbleP);
			setTimeout('document.getElementsByClassName("bubble")[0] && document.getElementsByTagName("body")[0].removeChild(document.getElementsByClassName("bubble")[0])', 2000);
		},
		confirmClose: function() {
			var tally = document.getElementsByTagName("html")[0].getAttribute("tally");
			if(tally == 0 || tally == null) {
				document.getElementsByTagName("html")[0].setAttribute("tally", "1");
				document.getElementsByTagName("html")[0].removeAttribute("style", "overflow: hidden;");
			} else {
				document.getElementsByTagName("html")[0].setAttribute("tally", parseInt(tally) - 1);
			};
			//清除节点
			document.getElementsByTagName("body")[0].removeChild(document.getElementsByClassName("iprompt")[0]);
			return false;
		},
		success: function(callback) {
			var tally = document.getElementsByTagName("html")[0].getAttribute("tally");
			if(tally == 0 || tally == null) {
				document.getElementsByTagName("html")[0].setAttribute("tally", "1");
				document.getElementsByTagName("html")[0].removeAttribute("style", "overflow: hidden;");
			} else {
				document.getElementsByTagName("html")[0].setAttribute("tally", parseInt(tally) - 1);
			};
			//清除节点
			document.getElementsByTagName("body")[0].removeChild(document.getElementsByClassName("iprompt")[0]);
			if(typeof(callback) == 'function') {
				callback();
			}
		}
	};
	var ipromptFn = new iprompt();
	//confirm取消
	$("body").on("click", ".confirmCancelBtn", function() {
		ipromptFn.confirmClose();
	});
	//alert确定
	$("body").on("click", ".alertOKBtn", function() {
		ipromptFn.success(i_callback);
	});
	//确认按钮
	$("body").on("click", ".confirmOKBtn", function() {
		ipromptFn.success(i_callback);
	});
	/* prompt end */
