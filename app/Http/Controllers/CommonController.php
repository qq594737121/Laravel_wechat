<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Libraries\Common;
class CommonController extends Controller
{
    protected  $common;
    public function __construct()
    {

        $this->common = Common::getInstance();
    }

    /*
    *@param七牛上传
    */
    public function qiniu_upload(Request $request)
    {
        if($request->hasFile('file'))
        {
            $file = $request->file('file');
            if ($file->isValid()) {
//                $file = public_path("img/1524622354.jpg");
                $fileName = 'sjf/swellfun_laravel/' . date('Ymd') . '/' .substr(uniqid(rand()),-6). mt_rand(10000, 99999) . '.jpg';
                $disk = Storage::disk('qiniu');
                if($disk->put($fileName, file_get_contents($file)))
                {
                    return $this->common->ajaxReturn(env('QINIU_DOMAIN') . '/' . $fileName,200);
                }
                return $this->common->ajaxReturn("文件上传失败",3);
            }
            else
            {
                return $this->common->ajaxReturn("文件不合法",2);
            }
        }else
        {
            return $this->common->ajaxReturn("文件不存在",1);
        }
    }

    /*
     * @param解决微信图片防盗链机制
     * */
    public function GetUrl()
    {
        header('Content-Type: image/jpg');
        $url = $_GET['url'];
        echo file_get_contents($url);
    }

    /**
     * 代码发布
     */
    public function publish()
    {
        chdir('..');
        system('git checkout .');
        system('git pull');
    }

    /**
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downLog(Request $request)
    {
        $file = $request->input('file');
        if (empty($file)) {
            return '文件名不能为空';
        }
        $path = realpath(storage_path('api/exception/') . $file);
        if (is_file($path)) {
            return response()->download($path, basename($path));
        } else {
            return '文件名不存在';
        }
    }

    /**
     * 生成小程序码，可接受 path 参数较长，生成个数受限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCode(Request $request)
    {
        $miniProgram = app('wechat.mini_program');
        $path = $request->input('path');
        if (empty($path)) {
            return $this->common->ajaxReturn("参数不完整",1);
        }
        $resp = $miniProgram->app_code->get($path, $request->only([
            'width',
            'auto_color',
            'line_color',
            'is_hyaline',
        ]));
        $filename = uniqid(date('YmdHis').mt_rand(10000, 99999)).'.jpg';
        $resp->saveAs(public_path('appcode'), $filename);

        return response()->download(public_path('appcode/'.$filename));
    }

    /**
     * 适用于需要的码数量极多的业务场景
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCodeUnlimit(Request $request)
    {
        $miniProgram = app('wechat.mini_program');
        $scene = $request->input('scene');
        if (empty($scene)) {
            return $this->common->ajaxReturn("参数不完整",1);
        }
        $resp = $miniProgram->app_code->getUnlimit($scene, $request->only([
            'page',
            'width',
            'auto_color',
            'line_color',
            'is_hyaline',
        ]));
        $filename = uniqid(date('YmdHis').mt_rand(10000, 99999)).'.jpg';
        $resp->saveAs(public_path('appcode'), $filename);

        return response()->download(public_path('appcode/'.$filename));
    }

    /**
     * 生成二维码，可接受 path 参数较长，生成个数受限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQrCode(Request $request)
    {
        $miniProgram = app('wechat.mini_program');
        $path = $request->input('path');
        if (empty($path)) {
            return $this->common->ajaxReturn("参数不完整",1);
        }
        $resp = $miniProgram->app_code->getQrCode($path, $request->input('width'));
        $filename = uniqid(date('YmdHis').mt_rand(10000, 99999)).'.jpg';
        $resp->saveAs(public_path('appcode'), $filename);

        return response()->download(public_path('appcode/'.$filename));
    }

    public function poiRefresh()
    {
        system('php ../artisan user:poi-refresh');
    }

    public function composerInstall()
    {
        system('cd ../ && composer install');
    }

    public function orderStatusRefresh()
    {
        system('php ../artisan user:order-status-refresh');
    }

    public function cardTransaction()
    {
        system('php ../artisan user:card-transaction force');
    }

    public function cardTransactionFail()
    {
        system('php ../artisan user:card-transaction-fail');
    }

}
