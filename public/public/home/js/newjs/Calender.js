$('document').ready(function(){
	
	$.ajax({
		type: 'post',
		dataType : 'json',
		url: "areaController.do?getProvince=1",
		success: function(result){
			var listcont;
			 if(result!=null){
				for(var p=0;p<result.length;p++){
					listcont = listcont + "<option value='"+result[p].code+"'>"+result[p].area_name+"</option>";
				};
			 }
			 $('select[name=province]').html(listcont);//生成年份下拉菜单
			 
		  }
	});
	
	$('select[name=province]').change(function(){
		var selp =  $('select[name=province]').val();
		$.ajax({
			type: 'post',
			dataType : 'json',
			url: "areaController.do?getCity=1&code="+selp,
			success: function(result){
				var listcont;
				 if(result!=null){
					for(var p=0;p<result.length;p++){
						listcont = listcont + "<option value='"+result[p].code+"'>"+result[p].area_name+"</option>";
					};
				 }
				 $('select[name=city]').html(listcont);//生成年份下拉菜单
				 $('select[name=province]').css("color","rgb(0, 0, 0)");//生成年份下拉菜单
			  }
		});
	});
	
	$('select[name=city]').change(function(){
		var selp1 =  $('select[name=city]').val();
		$.ajax({
			type: 'post',
			dataType : 'json',
			url: "areaController.do?getDistrict=1&code="+selp1,
			success: function(result){
				var listcont;
				 if(result!=null){
					for(var p=0;p<result.length;p++){
						listcont = listcont + "<option value='"+result[p].code+"'>"+result[p].area_name+"</option>";
					};
				 }
				 $('select[name=district]').html(listcont);//生成年份下拉菜单
				 $('select[name=city]').css("color","rgb(0, 0, 0)");//生成年份下拉菜单
			  }
		});
	});
	
	$('select[name=district]').change(function(){
		$('select[name=district]').css("color","rgb(0, 0, 0)");//生成年份下拉菜单
	})
	
    /*
     * 生成级联菜单
     */
    var i=1940;
    var date = new Date();
    year = date.getFullYear();//获取当前年份
    var dropList;
    for(i;i<2016;i++){
        if(i==1940){
            dropList = dropList + "<option value='出生年' selected>出生年</option>"
        }
        if(i==1970){
        	 dropList = dropList + "<option id='seleid' value='"+i+"'>"+i+"</option>";
        }else{
        	 dropList = dropList + "<option value='"+i+"'>"+i+"</option>";
        }
    }
    $('select[name=year]').html(dropList);//生成年份下拉菜单
    var monthly;
    for(month=0;month<13;month++){
        if(month==0){
            monthly = monthly + "<option value='月' selected>月</option>";
        }
        else{
            monthly = monthly + "<option value='"+month+"'>"+month+"</option>";
        }
    }
    $('select[name=month]').html(monthly);//生成月份下拉菜单
    var dayly;
    for(day=0;day<=31;day++){
        if(day==0){
            dayly = dayly + "<option value='日' selected>日</option>";
        }else{
            dayly = dayly + "<option value='"+day+"'>"+day+"</option>";
        }
    }
    $('select[name=day]').html(dayly);//生成天数下拉菜单
    /*
     * 处理每个月有多少天---联动
     */
    $('select[name=month]').change(function(){
        var currentDay;
        var Flag = $('select[name=year]').val();
        var currentMonth = $('select[name=month]').val();
        switch(currentMonth){
            case "1" :
            case "3" :
            case "5" :
            case "7" :
            case "8" :
            case "10" :
            case "12" :total = 31;break;
            case "4" :
            case "6" :
            case "9" :
            case "11" :total = 30;break;
            case "2" :
                if((Flag%4 == 0 && Flag%100 != 0) || Flag%400 == 0){
                    total = 29;
                }else{
                    total = 28;
                }
            default:break;
        }
        currentDay += "<option value='日'>"+日+"</option>";
        for(day=1;day <= total;day++){
            currentDay = currentDay + "<option value='"+day+"'>"+day+"</option>";
        }
        $('select[name=day]').html(currentDay);//生成日期下拉菜单
        })
})