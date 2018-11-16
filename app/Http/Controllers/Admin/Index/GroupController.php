<?php
namespace App\Http\Controllers\Admin\Index;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Validator;
use App\Model\AdminGroup;
use App\Model\AdminUser;
use App\Libraries\Common;
class GroupController extends AdminController
{
    protected  $common;
    public function __construct()
    {
        parent::__construct();
        $this->common = Common::getInstance();
    }


    /**
     * 机构列表
     */
    public function index(Request $request)
    {
        $name = trim($request->input('name'));
        $lists = AdminGroup::select("*")->when($name, function ($query) use ($name) {
            return $query->where(function ($query) use ($name) {
                $query->where('name','like',"%$name%");
            });
        })->orderByDesc('id')->paginate(10);
//        $lists->withPath(secure_url('product'));//dd($lists);exit;
        $lists->appends(compact('name'));
        return view('admin.group.index',compact('name',"lists"));
    }
    /**
     * 创建页面
     */
    public function create()
    {
        $this->assign['view'] = 'admin/group/create';
        $this->load->view('admin/common/frame',$this->assign);
    }
    /**
     * 新增权限组
     */
    public function store(Request $request)
    {
        $input = $request->all();
        if(!isset($input)){
            return $this->ajaxReturn('参数不正确',1);

        }
        if(empty($input['name'])){
            return $this->ajaxReturn('请输入机构名称',2);

        }
        $group = AdminGroup::where("name",$input['name'])->first();
        if(!empty($group)){
            return $this->ajaxReturn('机构名称已存在',3);

        }
        $param = $input;
        $param['create_time'] = date("Y-m-d H:i:s",time());
        $param['update_time'] = $param['create_time'];
        $param['name']        = $this->common->safety($param['name']);
        $param['description'] = $this->common->safety($param['description']);
        $param['types']       = $this->common->safety($param['types']);
        if(AdminGroup::insert($param)){
            return $this->ajaxReturn('添加成功',200);
        }else{
            return $this->ajaxReturn('添加失败',0);
        }
    }
    /**
     * 编辑资料
     */
    public function edit(Request $request)
    {
        $input = $request->all();
        $menu  = AdminGroup::where("id",trim($input['id']))->first();
        return $this->ajaxReturn($menu,200);
    }
    /**
     * 新增权限组
     */
    public function update(Request $request)
    {
        try {
            $input = $request->all();
            if(!isset($input))
            {
               return  $this->ajaxReturn('参数不正确',1);
            }
            if(empty($input['name'])){
                return $this->ajaxReturn('请输入机构名称',2);
            }
            $group = AdminGroup::where("name",$input['name'])->first();
            if(!empty($group) && $group->id != $input['id']){
                return $this->ajaxReturn('机构名称已存在',3);
            }
            $param = $input;
            $param['update_time'] = date("Y-m-d H:i:s",time());
            $param['name']        = $this->common->safety($param['name']);
            $param['description'] = $this->common->safety($param['description']);
            $param['types']       = $this->common->safety($param['types']);
            if( AdminGroup::where("id",$input['id'])->update($param)){
               return  $this->ajaxReturn('修改成功',200);
            }else{
               return  $this->ajaxReturn('修改失败',0);
            }
        } catch (Exception $e) {
            return $this->ajaxReturn($e->getMessage(),$e->getCode());
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

        AdminGroup::where("id",$id)->update($param);
        return redirect('admin/Group/index');
    }
    /**
     *@param删除
     */
    public function destroy($id){
        $this->user_model->deleteById($id);
        redirect('admin/User/index');die;
    }
    /*
     * @param查看成员
     * */
    public function seluser(Request $request)
    {

        $id    = trim($request->input('id'));
        $lists = AdminUser::select("*")->when($id, function ($query) use ($id) {
            return $query->where(function ($query) use ($id) {
                $query->where('id',$id);
            });
        })->orderByDesc('id')->paginate(10);
//        $lists->withPath(secure_url('product'));//dd($lists);exit;
        $lists->appends(compact('id'));
        return view('admin.group.seluser',compact('id',"lists"));
    }

}
