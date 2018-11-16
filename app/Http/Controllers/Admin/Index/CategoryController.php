<?php
namespace App\Http\Controllers\Admin\Index;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Validator,DB;
use App\Model\AdminCategory;

class CategoryController extends AdminController
{
    static public $treeList = array();
    public function __construct()
    {
        parent::__construct();
    }

    static public function list2tree(&$data,$pid = 0,$count = 0) {

        foreach ($data as $key => $value){
            if($value['parent_id']==$pid){
                $value['count'] = $count;
                self::$treeList []=$value;
                unset($data[$key]);
                self::list2tree($data,$value['cat_id'],$value['parent_id']+1);
            }
        }
        return self::$treeList ;
    }
    /*
     * @param显示分类
     * */
    public  function index()
    {
        $list['list'] = AdminCategory::where("is_delete","0")
        ->orderBy("parent_id")
        ->orderBy("sort")
        ->get()->toArray();
        $list['list'] = $this->list2tree($list['list'],0);
        foreach($list['list'] as $key=>$value){
            if($value['deep']==0){
                $list['list'][$key]['prefix_name']=' ├─ ';
            } else{
                $list['list'][$key]['prefix_name']='└─';
            }
        }
//        echo "<pre>";
//        print_r($list);exit;
        return view('admin.category.index',compact("list"));
    }

    /*
     * @param新增
     * */
    public function add(Request $request)
    {
        $post   =   $request->input();
        if(empty($post['name']))
        {
            return $this->ajaxReturn('请输入分类名称',1);
        }
        if(empty($post['sort']))
        {
            return $this->ajaxReturn('请输入排序序号',2);
        }
        $name = $request->session()->get("cdc_current_user_id")->name;

        if(isset($post['fid']) && !empty($post['fid']))
        {
            $post['fid']    =   $post['fid'];
        }else{
            $post['fid']=0;
        }
        $data   =   array(
            'parent_id'      =>$post['fid'],
            'cat_name'       =>$post['name'],
            'sort'           =>$post['sort'],
            'creator'        =>$name,
            'include_in_menu'=>$post['status'],
            'editor'         =>$name,
            'created_at'    =>date('Y-m-d H:i:s'),
            'updated_at'    =>date('Y-m-d H:i:s'),
            'category_type'  =>$post['category_type'],
            'logo'           =>$post['logo'],
            'long_logo'      =>$post['long_logo']
        );
        if(AdminCategory::insert($data))
        {
            //更新
            $id =  DB::getPdo()->lastInsertId();
            if($post['fid']==0)
            {
                $paths=$id;
                $deep=0;
            }else
            {
                $path  = $this->get_path($post['fid']);
                $paths = $path.$id;
                $de    = explode('/',$paths);
                $deep  = sizeof($de)-1;
            }
            $arr    =   array(
                'path' => $paths,
                'deep' => $deep
            );
            AdminCategory::where("cat_id",$id)->update($arr);
        }
        return $this->ajaxReturn("success",200);

    }
    /*
     *@param编辑
     * */
    public function updates(Request $request)
    {
        $post   =   $request->all();
        if(empty($post['name']))
        {

            return $this->ajaxReturn("请输入分类名称",1);
        }
        if(empty($post['sort']))
        {
            return $this->ajaxReturn("请输入排序序号",2);
        }
        $name = $request->session()->get("cdc_current_user_id")->name;
        $data = array(
            'cat_name'       => $post['name'],
            'sort'           => $post['sort'],
            'editor'         => $name,
            'include_in_menu'=> $post['status'],
            'updated_at'    => date('Y-m-d H:i:s'),
            'category_type'  => $post['category_type'],
            'logo'           => $post['logo'],
            'long_logo'      => $post['long_logo']
        );
        AdminCategory::where("cat_id",$post['id'])->update($data);
        return $this->ajaxReturn("success",200);
    }

    /*
     * @param删除
     * */
    public function deletes(Request $request)
    {
        $query = AdminCategory::where("parent_id",$request->input("id"))->where("is_delete",0)->first();
        if(!empty($query)){
            return $this->ajaxReturn("下面有子分类不能删除",1);
        }else{
            AdminCategory::where("cat_id",$request->input("id"))->update(array("is_delete"=>1));
            return $this->ajaxReturn("success",200);
        }

    }

    /*
     * @param编辑默认数据
     * */
    public function xg(Request $request)
    {
        $row = AdminCategory::where("cat_id",$request->input('id'))->first();
        return $this->ajaxReturn($row,200);
    }


    /*
     * @param递归处理path
     * */
    public function get_path($id)
    {
        $here  = "";
        $query = AdminCategory::where("cat_id",$id)->first();
        if($query->parent_id !=0 )
        {
            $here .= $this->get_path($query->parent_id);
        }
        $here.="".$id."/";
        return $here;
    }
}
