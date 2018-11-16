<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\UserAuth;
use App\Libraries\PermissionLogic;
use App\Model\AdminMenu;
class CheckAdminAuth
{
    protected $except = [
        //
    ];
    protected $check_login  = true;//是否判断登录
    protected $check_permis = true;//是否判断权限
    protected $permis_logic;
    protected $index_page   = "admin/admin/index";//首页
    protected $allowed  = array("get");

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $login = UserAuth::login($request);

        if(!$login && $this->check_login)
        {
            return redirect('admin/login/index');
        }

        $this->permis_logic =  PermissionLogic::getInstance();
        if($login)
        {
            $this->permis_logic->_init($login);
        }
        if($this->check_permis &&  in_array(strtolower($_SERVER['REQUEST_METHOD'],$this->allowed)))
        {
            //获取菜单，当前页面不在菜单列表中， 就不判断权限
            $field = ['id','parent_id','name','url','remark','status','sort','type'];
            $menus   =  AdminMenu::select($field)
                ->where('type','1')
                ->orderBy('sort')
                ->get()
                ->toArray();

            $currpage = strtolower($request->path());//获取当前页面URL
            $f        = false;
            foreach ($menus as $menu){
                if(strtolower($menu['url']) == $currpage){
                    $f = true;break;
                }
            }
            if($currpage == $this->index_page){
                return $next($request);
            }
            //是否在菜单数据内
            if($f)
            {//在菜单列表中，才进行权限判断
                $permis = $this->permis_logic->checkPermission($currpage);
                if('yes' != $permis)
                {
                    echo "<script>alert('对不起，您没有当前页面的权限，请联系管理员设置当前页面的权限。');history.go(-1);</script>";
                    exit;
                }
            }else
            {
                //在控制器下页面没有加入菜单中的情况，只需要有控制器权限，就可以进入控制器下所有的页面
                echo "<script>alert('对不起，您没有当前页面的权限，请联系管理员设置当前页面的权限。');history.go(-1);</script>";
                exit;
            }
        }
        return $next($request);
    }
}
