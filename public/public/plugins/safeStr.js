// 验证不能输入特殊字符
function validate(value,obj) {
    var pattern = /[`~!@#$%^&*()_+<>?:"{},\/;'[\]]/im;
    if (value === '' || value === null) return false;
    if (pattern.test(value)) {
        // alert("非法字符！");
        // console.log(obj.val());
        let _v = obj.val().replace(/[`~!@#$%^&*()_+<>?:"{},\/;'[\]]/im,'');
        console.log(_v);
        obj.val(_v);
        return false;
    }
    return true;
};

function filterSqlStr(value) {
    var str = "and,delete,or,exec,insert,select,union,update,count,*,',join,>,<";
    var sqlStr = str.split(',');
    var flag = true;
    
    for (var i = 0; i < sqlStr.length; i++) {
        if (value.toLowerCase().indexOf(sqlStr[i]) != -1) {
            flag = false;
            break;
        }
    }
    alert(flag);
    return flag;
};

$(document).on('input propertychange', '.str_script', function(event) {
	var _this = $(this);

	let _type = $(this).attr('data-intype');
	let _limit = $(this).attr('data-inlimit');

	var val = $(this).val(); 

	// // 不能为负数
	// if (_type=="number") {
	// 	if(val<0){
	// 		alert("不能为负数！");
	// 		$(this).val("0");
	// 		return false;
	// 	}
	// };

	// console.log(_limit);
	// // 长度限制
	// if (_limit) {
	// 	let limit = parseInt(_limit);
	// 	if (val.length>limit) {
	// 		// alert("超出长度限制");
	// 		let cc = val.substring(0,limit);
	// 		// console.log(cc);
	// 		$(this).val(cc);
	// 		return false;
	// 	}
	// }

	validate(val,_this);
});