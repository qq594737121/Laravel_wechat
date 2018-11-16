//主菜单数据
module.exports.menuData = {
    'active':[1,11],
    'dataList':[{
                  'id':1,
                  'title':'营销管理',
                  'icon':'fa fa-line-chart',
                  'url':'',
                  'list':[{
                              'id':11,
                              'title':'品鉴会活动管理',
                              'url':'../manage/active_list.html'
                          },{
                              'id':12,
                              'title':'短信群发',
                              'url':'../manage/msg_list.html'
                          }]
                  },
                  {
                  'id':2,
                  'title':'系统管理',
                  'icon':'fa fa-fw fa-cog',
                  'url':'',
                  'list':[{
                              'id':21,
                              'title':'用户管理',
                              'url':'../system/user_manage.html'
                          },{
                              'id':22,
                              'title':'角色管理',
                              'url':'../system/role_manage.html'
                          },{
                              'id':23,
                              'title':'机构管理',
                              'url':'../system/reg_manage.html'
                          }]
                  },                               
                  ]
        }

var getdata = "getdata/";//区分服务器接口
//var getdata = "http://cdcdemo.woaap.com/";//区分服务器接口
var getdata = "../../";//区分服务器接口   

//后端接口路径
module.exports.url = {
    //业务结构    
    "structAll":getdata+"/struct/all",//业务结构整体全部
    "structView":getdata+"/struct/view",//业务结构查看
    "structDel":getdata+"/struct/del",//业务结构删除
    "structAdd":getdata+"/struct/add",//业务结构添加
    "structEdit":getdata+"/struct/edit",//业务结构编辑
}
module.exports.devUrl = "http://translite.woaap.com";