<?php
namespace App\Http\Controllers\Admin\Index;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Validator;
use App\Model\AdminUser;
use App\Model\AdminGroup;
use App\Model\AdminPermis;
use App\Libraries\Common;
class UserController extends AdminController
{
    protected  $common;
    public function __construct()
    {
        parent::__construct();
        $this->common = Common::getInstance();
    }

    /**
     * 用户列表
     */
    public function index(Request $request)
    {
        $name   = trim($request->input('name'));
        $group  = trim($request->input("group"));
        $permis = trim($request->input('permis'));
        $lists = AdminUser::leftJoin('group',function ($join){
            $join->on('user.group_id', '=', 'group.id');
        })->leftJoin("permis",function($join){
            $join->on("user.permis_id",'=','permis.id');
        })->select(['user.*','group.name as gname','permis.name as pname'])
            ->when($name, function ($query) use ($name) {
            return $query->where(function ($query) use ($name) {
                $query->where('user.name','like',"%$name%");
                });
            })
            ->when($group, function ($query) use ($group) {
                return $query->where(function ($query) use ($group) {
                    $query->where('user.group_id',$group);
                });
            })
            ->when($permis, function ($query) use ($permis) {
                return $query->where(function ($query) use ($permis) {
                    $query->where('user.permis_id',$permis);
                });
            })
            ->where("user.deleted",0)
            ->orderByDesc('user.id')->paginate(10);
//        $lists->withPath(secure_url('product'));//dd($lists);exit;
        $grouplist  = AdminGroup::select(['id','name','description','code','types','status','create_time','update_time'])
            ->where("deleted",0)
            ->get()->toArray();
        $permislist = AdminPermis::select(['id','name','parent_id','description','status','create_time','update_time'])
            ->where("deleted",0)
            ->get()->toArray();
        $lists->appends(compact('name'));
        return view('admin.user.index',compact('name',"lists","grouplist","permislist","group","permis"));
    }

    /**
     * 新增数据
     */
    public function store(Request $request)
    {
        $input = $request->all();
        if(!isset($input))
        {
            return $this->ajaxReturn('参数不正确',1);
        }
        if(empty($input['name']))
        {
            return $this->ajaxReturn('请输入用户名称',2);
        }
        if(empty($input['password'])){
            return $this->ajaxReturn('请输入密码',3);
        }
        if(!empty($input['password']) && $input['password']!=$input['pwd'])
        {
            return  $this->ajaxReturn('确认密码和密码不一致',4);
        }
        if(!empty($input['mobile']))
        {
            if($this->common->checkphone($input['mobile']) == false)
            {
                return  $this->ajaxReturn('手机号格式不正确',5);
            }
        }
        $user  = AdminUser::where("name",$input['name'])->first();
        if(!empty($user)){
            return $this->ajaxReturn('用户名称已存在',6);
        }
        $input['password']  =   $this->common->pwd(trim($input['password']));
        $input['tel']       =   $input['tel2'];
        unset($input['pwd']);
        unset($input['tel2']);
        $param = $input;
        $param['create_time'] = date("Y-m-d H:i:s",time());
        if(AdminUser::insert($param)){
            return $this->ajaxReturn('添加成功',200);
        }else{
           return $this->ajaxReturn('添加失败',0);
        }

    }

    /**
     * @编辑数据
     */
    public function update(Request $request)
    {
        $input = $request->all();
        if(!isset($input)){
           return  $this->ajaxReturn('参数不正确',1);
        }
        if(empty($input['name'])){
           return $this->ajaxReturn('请输入用户名称',2);
        }
        if(!empty($input['mobile'])){
            if($this->common->checkphone($input['mobile']) == false)
            {
                return  $this->ajaxReturn('手机号格式不正确',5);
            }
        }
        $user  = AdminUser::where("name",$input['name'])->first();
        if(!empty($user)  && $user->id !=$input['id'] ){
            return $this->ajaxReturn('用户名称已存在',6);
        }
        $input['tel']   =   $input['tel2'];
        unset($input['tel2']);
        $param = $input;
        $param['update_time'] = date("Y-m-d H:i:s",time());
        if(AdminUser::where("id",$input['id'])->update($param)){
            return $this->ajaxReturn('修改成功',200);
        }else{
           return  $this->ajaxReturn('修改失败',0);
        }
    }
    /*
     * @param删除数据
     * */
    public function deluser(Request $request)
    {
        $input  =   $request->all();
        if(AdminUser::where("id",trim($input['id']))->update(array('deleted'=>1)))
        {
            return $this->ajaxReturn('删除成功',200);
        }else{
            return $this->ajaxReturn('删除失败',1);
        }
    }
    /**
     * @param获取修改数据
     */
    public function edit(Request $request)
    {
        $input = $request->all();
        $data = AdminUser::where("id",trim($input['id']))->first();
        return $this->ajaxReturn($data,200);
    }
    /*
     * @param修改密码
     * */
    public function editpass(Request $request)
    {
        $input = $request->all();
        if(!isset($input))
        {
            return $this->ajaxReturn('参数不正确',1);

        }
        if(empty($input['password'])){
            return $this->ajaxReturn('请输入用户密码',2);

        }
        if(empty($input['newpwd'])){
            return $this->ajaxReturn('请输入新密码',3);

        }
        if(empty($input['upwd'])){
            return $this->ajaxReturn('请输入确认密码',4);

        }
        if($input['upwd']!=$input['newpwd']){
            return $this->ajaxReturn('两次输入密码不一致',5);

        }
        $user = AdminUser::where("id",$input['id'])->first();

        $pwd                = $this->common->pwd($input['password']);
        $input['password']  = $this->common->pwd($input['newpwd']);
        if($user->password != $pwd)
        {
            return $this->ajaxReturn('原始密码输入不正确',6);
        }
        unset($input['newpwd']);
        unset($input['upwd']);
        $param = $input;
        $param['update_time'] = date("Y-m-d H:i:s",time());
        if(AdminUser::where("id",$input['id'])->update($param)){
            return $this->ajaxReturn('修改成功',200);
        }else{
            return $this->ajaxReturn('修改失败',0);
        }
    }
    /**
     * @param禁用启用
     */
    public function enable(Request $request)
    {
        $input = $request->all();
        if(!isset($input['status']))
        {
            return back()->with('errmsg','参数不完整');
        }
        $id              = trim($input['id']);
        $param           = array();
        $param['status'] = ($input['status']==1)?0:1;

        AdminUser::where("id",$id)->update($param);
        return redirect('admin/User/index');
    }

}
