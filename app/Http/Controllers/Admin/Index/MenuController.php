<?php
namespace App\Http\Controllers\Admin\Index;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Validator;
use App\Libraries\Common;
use App\Model\AdminMenu;
class MenuController extends AdminController
{

    protected  $common;
    public function __construct()
    {
        parent::__construct();
        $this->common = Common::getInstance();
    }
    /**
     * 菜单列表
     */
    public function index(Request $request)
    {
        $name = trim($request->input('name'));
        $lists = AdminMenu::select("*")->when($name, function ($query) use ($name) {
            return $query->where(function ($query) use ($name) {
                $query->where('name','like',"%$name%");
            });
        })->get()->toArray();
        $menu_list = array();
        foreach ($lists as $menu) {
            if($menu['parent_id'] > 0){
                $menu_list[$menu['parent_id']]['list'][$menu['id']]['info'] = $menu;
            }else{
                $menu_list[$menu['id']]['info'] = $menu;
            }
        }
//        $lists->withPath(secure_url('product'));//dd($lists);exit;
//        $lists->appends(compact('name'));
        return view('admin.menu.index',compact('name',"menu_list"));
    }
    /**
     * @param创建页面
     */
    public function create(Request $request)
    {
        $input = $request->all();
        $id    = $input['id'];
        if($id > 0){
            $menu = AdminMenu::where("id",$id)->first();
        }else{
            $menu = array();
        }
        return view('admin.menu.create',compact('menu',"id"));
    }
    /**
     * @param新增数据
     */
    public function store(Request $request)
    {
        $input = $request->all();
        if(!isset($input['menu']))
        {
            return back()->with('errmsg','参数不正确');
        }
        if(empty($input['menu']['name']))
        {
            return back()->with('errmsg','请输入菜单姓名');
        }
        $menu = AdminMenu::where("name",$input['menu']['name'])->first();
        if(!empty($menu))
        {
            return back()->with('errmsg','菜单名已存在');
        }
        $param         = $input['menu'];
        $param['type'] = 1;
        AdminMenu::insert($param);
        return redirect('admin/Menu/index');die;

    }
    /**
     * @param编辑页面
     */
    public function edit(Request $request)
    {
        $id   = $request->input('id');
        $menu = AdminMenu::where("id",$id)->first();
        if($menu->parent_id > 0)
        {
            $parent = AdminMenu::where("id",$menu->parent_id)->first();
        }else{
            $parent = array();
        }
        return view('admin.menu.edit',compact('menu',"id","parent"));
    }
    /**
     * @param编辑数据
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $id    = $input['id'];
        if(!isset($input['menu']))
        {
            return back()->with('errmsg','参数不正确');
        }
        if(empty($input['menu']['name'])){
            return back()->with('errmsg','请输入菜单姓名.');
        }
        $menu = AdminMenu::where("name",$input['menu']['name'])->first();
        if(!empty($menu) && $id != $menu->id)
        {
            return back()->with('errmsg','菜单名已存在');
        }
        $param = $input['menu'];
        AdminMenu::where("id",$id)->update($param);
        return redirect('admin/Menu/index');
    }
    /**
     *@param禁用启用
     */
    public function enable(Request $request)
    {
        $input = $request->all();
        if(!isset($input['status'])){
            return back()->with('errmsg','参数不正确');
        }
        $param = array();
        $param['status'] = ($input['status']==1)?0:1;
        AdminMenu::where("id",$input['id'])->update($param);
        $menu = AdminMenu::where("id",$input['id'])->first();
        if($menu->parent_id == 0)
        {
            AdminMenu::where("parent_id",$input['id'])->update($param);
        }
        return redirect('admin/Menu/index');
    }

}
