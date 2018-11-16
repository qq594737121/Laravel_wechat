<?php
namespace App\Http\Controllers\Admin\Index;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Validator;
use App\Libraries\Common;
use App\Libraries\PermissionLogic;
use App\Model\AdminPermis;
use App\Model\AdminMenu;
class PermisController extends AdminController
{
    protected  $common;
    protected  $permis_logic;
    public function __construct()
    {
        parent::__construct();
        $this->common           = Common::getInstance();
        $this->permis_logic     = PermissionLogic::getInstance();
    }


    /**
     * @param角色列表
     */
    public function index(Request $request)
    {
        $name = trim($request->input('name'));
        $lists = AdminPermis::select("*")->when($name, function ($query) use ($name) {
            return $query->where(function ($query) use ($name) {
                $query->where('name','like',"%$name%");
            });
        })->orderByDesc('id')->paginate(10);
//        $lists->withPath(secure_url('product'));//dd($lists);exit;
        $lists->appends(compact('name'));
        return view('admin.permis.index',compact('name',"lists"));
    }
    /**
     * @param创建页面
     */
    public function create(){
        $this->assign['view'] = 'admin/permis/create';
        $this->load->view('admin/common/frame',$this->assign);
    }
    /**
     * @param 新增角色
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            if(!isset($input))
            {
                return $this->ajaxReturn('参数不正确',1);
            }
            if(empty($input['name']))
            {
                return $this->ajaxReturn('请输入角色名称',2);
            }
            $permis =  AdminPermis::where("name",$input['name'])->first();
            if(!empty($permis)){
                return $this->ajaxReturn('角色名称已存在',3);
            }
            $param = $input;
            $param['create_time'] = date("Y-m-d H:i:s");
            if(AdminPermis::insert($param)){
                return $this->ajaxReturn('添加成功',200);
            }else{
                return $this->ajaxReturn('添加失败',0);
            }
        }catch (Exception $e)
        {
            return $this->ajaxReturn($e->getMessage(),$e->getCode());
        }
    }
    /**
     * 编辑数据
     */
    public function edit(Request $request)
    {
        $input = $request->all();
        $menu  = AdminPermis::where("id",trim($input['id']))->first();
        return $this->ajaxReturn($menu,200);
    }
    /**
     * 编辑角色
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $input['name']  = trim($input['name']);
        if(!isset($input))
        {
            return $this->ajaxReturn('参数不正确',1);
        }
        if(empty($input['name'])){
            return $this->ajaxReturn('请输入角色名称',2);
        }
        $permis = AdminPermis::where("name",$input['name'])->first();
        if(!empty($permis) && $permis->id!=$input['id'])
        {
            return $this->ajaxReturn('角色名称已存在',3);

        }
        if(AdminPermis::where("id",$input['id'])->update(array("name"=>$input['name'])))
        {
            return $this->ajaxReturn('修改成功',200);
        }else
        {
            return $this->ajaxReturn('修改失败',0);
        }

    }
    /**
     * 禁用启用
     */
    public function enable(Request $request)
    {
        $input = $request->all();
        if(!isset($input['status']))
        {
            return back()->with('errmsg','参数不完整');
        }
        $id = trim($input['id']);
        $param = array();
        $param['status'] = ($input['status']==1)?0:1;

        AdminPermis::where("id",$id)->update($param);
        return redirect('admin/Permis/index');
    }

    /**
     * 编辑权限
     */
    public function setpermis(Request $request)
    {
        $id = $request->input("id");
        $permis_module_list = AdminMenu::select(['id','parent_id','name','url','remark','status','sort','type'])
            ->get()
            ->toArray();
        $permis_module_list = $this->permis_logic->getPermisModuleList($permis_module_list);//全部菜单
        $permis_list        = $this->permis_logic->getPermisModuleByGroupId($id);//全部权限
        $row                = AdminPermis::where("id",$id)->first();
        $permis_id = $id;
//        dd($permis_module_list);/
        return view('admin.permis.permis',compact("permis_module_list","row","permis_list","permis_id"));
    }
    /**
     * 设置权限
     */
    public function permismodule(Request $request)
    {
        $input = $request->all();
        $id    = $input['id'];
        if(!$input['permis_module'])
        {
            return back()->with('errmsg','参数不完整');
        }
        $permis_module_id = $input['permis_module_id'];
        $permis_module    = $input['permis_module'];

        //先删除
        $this->permis_logic->deletePermisByGroupId($id);
        //再插入
        $param = $this->permis_logic->setPermisList($id, $permis_module_id,$permis_module);
        if(!empty($param)){
          $this->permis_logic->newManyData($param);
        }

        return redirect('admin/Permis/index');
    }

}
