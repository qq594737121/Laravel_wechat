<?php
namespace App\Libraries;
use App\Model\AdminMenu;
use App\Model\AdminUser;
use App\Model\AdminPermisModule;
class PermissionLogic
{
    protected static $login;
    protected static $_instance;
    protected $login_userid_key = 'cdc_current_user_id';
    /**
     * @return
     */
    public static function getInstance()
    {
        if(isset(self::$_instance)) {
            return self::$_instance;
        }
        self::$_instance = new self();
        return self::$_instance;
    }

    /*
     * @param初始化
     * */
    public function _init($login)
    {
        if(!empty($login))
        {
            self::$login = $login;
        }
    }


    /**
     * 判断是否登录
     */
    public function is_login($id=0)
    {
        $user = self::$login;
        if(!empty($user)){
            return true;
        }
        return false;
    }

    /**
     * 权限判断
     */
    public function checkPermission($route = '', $userid = 0)
    {

        if ($userid > 0)
        {
            $user = AdminUser::where("id",$userid)->first();
            if(0 == $user->status)
            {
                return 'no';
            }
        }else
        {
            $user = self::$login;
        }
        //超级管理员
        if ($this->isAdmin($user) == 'yes')
        {
            return 'yes';
        }
        $ispermis = 'no';
        //获取分组对应的权限列表
        if (isset($user['permis_id']) && !empty($user['permis_id']))
        {
            $permis_list = $this->getPermisModuleByGroupId($user['permis_id']);

            if(isset($permis_list[$route]))
            {
                return 'yes';
            }
        }
//        dd($ispermis);
        return $ispermis;
    }
    /**
     * 获取权限组对应的权限模块
     */
    public function getPermisModuleByGroupId($permis_id=0)
    {
        if(empty($permis_id)){
            return array();
        }
        $field = ['id','permis_id','name','status'];
        $rows = AdminPermisModule::select($field)
            ->where("permis_id",$permis_id)
            ->orderBy("id")
            ->get()
            ->toArray();
            $permis_list = array();
            foreach ($rows as $row)
            {
                $permis_list[$row['name']] = $row;
            }
            return $permis_list;
        return array();
    }

    /**
     * 是否为管理员
     * @param Array $user 账号信息数组
     */
    public function isAdmin($user = array())
    {
        $isadmin = 'no'; //非管理员
        if (empty($user)) {
            $user = self::$login;
        }
        //超级管理员，拥有所有权限
        if (1 == $user['is_admin']) {
            $isadmin = 'yes';
        }
        return $isadmin;
    }


    /**
     * 获取模块权限列表
     */
    public function getPermisModuleList($permis_module=array())
    {
        if(empty($permis_module)) return array();
        $permis_list = array();
        foreach ($permis_module as $permis) {
            if($permis['parent_id'] > 0){//二级
                $permis_list[$permis['parent_id']]['list'][$permis['id']]['info'] = $permis;
            }else{//一级
                $permis_list[$permis['id']]['info'] = $permis;
            }
        }
        return $permis_list;
    }

    /**
     * 设置权限组权限时，处理传递的参数值
     */
    public function setPermisList($group_id=0,$permis_module_id=array(),$permis_module=array())
    {
        if(empty($group_id) || empty($permis_module_id) || empty($permis_module)){
            return array();
        }
        $params = array();
        foreach ($permis_module_id as $id) {
            if(!isset($permis_module[$id])){
                continue;
            }
            if(empty($permis_module[$id]['url'])){
                continue;
            }
            $param = array();
            $param['permis_id'] = $group_id;
            $param['name'] = $permis_module[$id]['url'];
            $params[] = $param;
        }
        return $params;
    }
    /**
     * 获取分组对应的权限
     */
    public function getPermisNameByList($permis_list=array()){
        if(empty($permis_list)){
            return array();
        }
        $permislist = array();
        foreach ($permis_list as $id=>$permis) {
            $permisname = explode('/',$permis['name']);
            $module     = strtolower($permisname[0]);
            $controller = strtolower($permisname[1]);
            $method     = strtolower($permisname[2]);
            $permislist[$module][$controller][$method] = $permis['id'];
        }
        return $permislist;
    }
    /**
     * 获取当前用户可以看到的菜单
     * @author
     */
    public function getMenuInfo($url)
    {
        $menus = AdminMenu::select('*')->get()->toArray();
        $menu_list = array();
        foreach ($menus as $menu)
        {
            if(1 != $menu['status'])
            {
                continue;//已禁用
            }
            if( $menu['type']!=1){
                continue;//不展示
            }
            //如果是二级菜单
            if($menu['parent_id'] > 0){
                //判断权限
                if('yes' != $this->checkPermission($menu['url'])){
                    continue;
                }
                $menu_list[$menu['parent_id']]['list'][$menu['id']]['info'] = $menu;
            }else
            {
                $menu_list[$menu['id']]['info'] = $menu;
            }
        }
        if(!empty($menu_list)){
            $menuinfo = array();
            foreach ($menu_list as $key=>$menu) {
                if(!isset($menu['list'])) continue;
                $menuinfo[$key] = $menu;
            }
            return $menuinfo;
        }
        return array();
    }

    /*
     * @param注销登陆
     * */
    public function destoryLoginUserInfo($request)
    {
        return $request->session()->flush();
    }


    /**
     * 删除权限组下面所有的权限
     */
    public function deletePermisByGroupId($permis_id=0)
    {
        return AdminPermisModule::where("permis_id",$permis_id)->delete();
    }

    /*
     * @param新增权限分组
     * */
    public function newManyData($param=array())
    {
        return AdminPermisModule::insert($param);
    }

    /*
     * @param保存登陆用户信息
     * */
    public function setLoginUserInfo($request,$user)
    {
        $request->session()->put($this->login_userid_key,$user);
    }



}
?>