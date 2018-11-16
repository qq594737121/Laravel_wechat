<?php
namespace App\Services\Carousel;
use DB;
use App\Services\BaseService;
use Illuminate\Support\Facades\Cache;
use App\Model\BannerImage;
/*
 * @param lei.wang
 * */
class CarouselimageService extends BaseService
{


    protected static $_instance;

    public static function getInstance()
    {
        if(isset(self::$_instance)) {
            return self::$_instance;
        }
        self::$_instance = new self();
        return self::$_instance;
    }

    /*
     * @param新增子分类轮播图
     * */
    public function add_image_info($data)
    {
        if(isset($data['id']) && $data['id'] != 0){
            BannerImage::where("id",$data['id'])->update($data);
        }else{
            BannerImage::insert($data);
        }
    }

    /*
     * @param获取数据
     * */
    public function get_group_caroudel_by_id($id)
    {
        return BannerImage::where("id",$id)->first();
    }

    /*
     * @param获取对应的轮播图数据
     * */
    public function get_carousel_images($show_page)
    {
        $res = BannerImage::select(["id","sort"])
            ->where("parent_id",0)
            ->where("is_show",2)
            ->where("show_page",$show_page)
            ->orderBy("sort")
            ->get()->toArray();
        $data = array();
        foreach ($res as $value)
        {
            $data[] = BannerImage::select(["title","image_name","image_url","jump_url"])
                ->where("parent_id",$value['id'])
                ->where("parent_id","!=",0)
                ->where("is_show",2)
                ->orderBy("sort")->get()->toArray();
        }
        return $data;
    }

    public function show_carousel_images($id)
    {
        BannerImage::where("id",$id)->update(array("is_show"=>2));
    }

    public function no_show_carousel_images($id)
    {
        BannerImage::where("id",$id)->update(array("is_show"=>1));
    }

    /*
     * @param新增
     * */
    public function save_group_carousel($data)
    {
        $data['title'] = trim($data['title']);
        $data['sort']  = trim($data['sort']);
        $data['show_page'] = $data['show_page'];
        if(empty($data['id']))
        {
            $data['parent_id'] = 0;
            $data['add_time']  = date("Y-m-d H:i:s");
            BannerImage::insert($data);
        }else
        {
            BannerImage::where("id",$data['id'])->update($data);
        }
    }

    public function get_carousel_image_by_id($id)
    {
        return BannerImage::where("id",$id)->first();
    }


}
