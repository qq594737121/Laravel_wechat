template.config('openTag','[[');
template.config('closeTag',']]');
function createMenu(data){
    var data = {
      "menu": menuData
    }
    //console.log(pageMenu);
    if("undefined" == typeof pageMenu){ 
        pageMenu = {"active":[menuData[0].id, menuData[0].list[0].id]}
    }
    data.menu.active = pageMenu.active;
    //$('.wrapper').prepend(tplArr['base'](data)); 
    console.log(data);
    var html = template('menutpl',data);
    $('.main-sidebar').html(html); 
}