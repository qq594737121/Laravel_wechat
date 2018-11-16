<?php
namespace App\Http\Controllers\Admin\Index;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Validator,DB;
use Illuminate\Support\Facades\Storage;
use App\Model\BannerImage;
use App\Services\Carousel\CarouselimageService;
class CarouselImageController extends AdminController
{
    protected $CarouselImage;
    public function __construct()
    {
        parent::__construct();
        error_reporting(0);
        $this->CarouselImage = CarouselimageService::getInstance();
    }

    /*
     * @param轮播图首页
     * */
    public function index(Request $request)
    {

        $post_data = $request->all();
        $title     = trim($post_data['title']);
        $is_show   = $post_data['is_show'];
        $data = BannerImage::select("*")->where("parent_id",0)
            ->when($title, function ($query) use ($title) {
            return $query->where(function ($query) use ($title) {
                $query->where('title','like',"%$title%");
            });
        })->when($is_show, function ($query) use ($is_show) {
            return $query->where(function ($query) use ($is_show) {
                $query->where('is_show',$is_show);
            });
        })->orderByDesc('id')->paginate(10);
        $data->appends(compact('title',"is_show"));
        return view('admin.banner.group_carousel_image_view',compact("data","title","is_show"));
    }


    /*
     * @param新增页面
     * */
    public function add_group_carousel(Request $request)
    {
        $id = $request->input("id");
        if(!empty($id))
        {
           $data = $this->CarouselImage->get_group_caroudel_by_id($id);
        }
        return view('admin.banner.add_group_carousel_view',compact("data"));
    }

    /*
     * @param保存数据
     * */
    public function save_group_carousel(Request $request)
    {
        $post_data = $request->all();
        $this->CarouselImage->save_group_carousel($post_data);
        return redirect("admin/CarouselImage/index");
    }



    /*
     * @param显示子分类轮播图
     * */
    public function get_child_carousel(Request $request)
    {
        $post_data = $request->all();
        $title     = trim($post_data['title']);
        $is_show   = $post_data['is_show'];
        $parent_id = $post_data['id'];
        $data = BannerImage::select("*")->where("parent_id",$parent_id)
            ->when($title, function ($query) use ($title) {
            return $query->where(function ($query) use ($title) {
                $query->where('title','like',"%$title%");
            });
        })->when($is_show, function ($query) use ($is_show) {
            return $query->where(function ($query) use ($is_show) {
                $query->where('is_show',$is_show);
            });
        })->orderByDesc('id')->paginate(10);
        $data->appends(compact('title'));
        return view('admin.banner.carousel_image_list_view',compact("data","title","is_show","parent_id"));
    }

    /*
     * @param子分类轮播图编辑页面
     * */
    public function add_child_carousel(Request $request)
    {
        $get_data = $request->all();
        if(isset($get_data['id']) && $get_data['id'] != 0){
            $child_carousel = $this->CarouselImage->get_carousel_image_by_id($get_data['id']);
        }

        $parent_id = isset($get_data['parent_id']) && $get_data['parent_id'] !=0 ?$get_data['parent_id']:0;

        return view('admin.banner.add_carousel_image_view',compact("child_carousel","parent_id"));
    }

    /*
     * @param子分类轮播图新增
     * */
    public function upload_image(Request $request)
    {
        ini_set('memory_limit', '1024M');
        $post_data = $request->all();

        if($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            if ($file->isValid()) {
                $fileName = 'sjf/swellfun_laravel/' . date('Ymd') . '/' . substr(uniqid(rand()), -6) . mt_rand(10000, 99999) . '.jpg';
                $disk = Storage::disk('qiniu');
                if ($disk->put($fileName, file_get_contents($file))) {
                    $image_url = env('QINIU_DOMAIN') . '/' . $fileName;
                }
            }
        }
        $username = $request->session()->get("cdc_current_user_id")->name;
        if($post_data['id'] == 0)
        {
            $data = array(
                'parent_id'  =>  $post_data['parent_id'],
                'image_url'  =>  $image_url,
                'jump_url'   =>  $post_data['jump_url'],
                'title'      =>  $post_data['title'],
                'sort'       =>  $post_data['sort'],
                'show_page'  =>  0,
                'add_time'   =>  date('Y-m-d H:i:s'),
                'create_user'=> $username
            );
        }else{
            $data['id']       = $post_data['id'];
            $data['title']    = $post_data['title'];
            $data['jump_url'] = $post_data['jump_url'];
            $data['sort']     = $post_data['sort'];
            $data['update_user']= $username;
            if(!empty($image_url)){
                $data['image_url'] = $image_url;
            }
        }
        $this->CarouselImage->add_image_info($data);
        return redirect("admin/CarouselImage/get_child_carousel?id=".$post_data['parent_id']);
    }
    /*
    * @param展示
    * */
    public function show(Request $request)
    {
        $params = $request->all();
        $id        = $params['id'];
        $parent_id    = $params['parent_id'];

        $this->CarouselImage->show_carousel_images($id);
        if($parent_id == 0){
            return redirect("admin/CarouselImage/index");
        }else{
            return redirect("admin/CarouselImage/get_child_carousel"."?id=".$parent_id);
        }
    }
    /*
     * @param不展示
     * */
    public function no_show(Request $request)
    {
        $params = $request->all();
        $id            = $params['id'];
        $parent_id    = $params['parent_id'];

        $this->CarouselImage->no_show_carousel_images($id);
        if($parent_id == 0){
           return  redirect("admin/CarouselImage/index");
        }else{
           return  redirect("admin/CarouselImage/get_child_carousel"."?id=".$parent_id);
        }
    }

}