<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Libraries\UserAuth;
use App\Libraries\PermissionLogic;
use App\Libraries\Common;
class MenuController extends Controller
{
    public function __construct(Request $request)
    {
        $this->permis =  PermissionLogic::getInstance();
        $this->common = Common::getInstance();
        $login = UserAuth::login($request);
        if(!empty($login))
        {
            $this->permis->_init($login);
        }
    }

    /*
    *@param获取菜单数据
    */
    public function getMenuInfo(Request $request)
    {
        $url        = strtolower($request->input("url"));//获取当前页面URL
        $menus      = $this->permis->getMenuInfo($url);
        $iconarr    = array('fa fa-dashboard','fa fa-cubes','fa fa-table','fa fa-money','fa fa-gift','fa fa-home','fa fa-group','fa fa-key','fa fa-cogs');
        $idx        = 0;
        $menuinfo   = array();

        $active = array();
        foreach ($menus as $menu)
        {
            $menuarr = array();
            $menuarr['id']    = $menu['info']['id'];
            $menuarr['title'] = $menu['info']['name'];
            if(!isset($iconarr[$idx])){
                $idx = 0;
            }
            $menuarr['icon'] = $iconarr[$idx];
            $menuarr['url'] = '';
            $idx ++;

            if(!isset($menu['list']) && empty($menu['info']['url']))
            {
                continue;
            }
            foreach ($menu['list'] as $list)
            {
                $menulist = array();
                $menulist['id']     = $list['info']['id'];
                $menulist['title']  = $list['info']['name'];
                $menulist['url']    = URL($list['info']['url']);
                //如果当前等于当前URL
                if(strtolower($url)             == strtolower($list['info']['url']))
                {
                    $active[] = $menu['info']['id'];
                    $active[] = $list['info']['id'];
                }
                $menuarr['list'][] = $menulist;
            }
            $menuinfo[] = $menuarr;
        }
        if(!empty($menuinfo))
        {
            return json_encode(array('active'=>$active,'dataList'=>$menuinfo));
        }

    }
}
