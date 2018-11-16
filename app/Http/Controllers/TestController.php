<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Model\WechatApp;
use App\Libraries\WechatApi;
use App\Libraries\Common;
class TestController extends ApiController
{
    protected $wechatapi;
    protected $common;
    public function __construct()
    {
        $this->wechatapi = WechatApi::getInstance();
        $this->common    = Common::getInstance();
    }
    /*
   * @param富文本编辑器
   * */
    public function ueditor()
    {
        return view('home.index.ueditor');
    }
    /*
     * @param发邮件
     * */
    public function send()
    {
        $openid = "oW_q_uCfLs9GNCX9oBBx74Aph97s";
        $enco =  $this->common->encodeOpenId($openid);
        $deco =  $this->common->decodeOpenId($enco);
        var_dump($enco);var_dump($deco);
        exit;
        $message = 'dear wang';
        Mail::raw(json_encode($message, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), function ($message) {
            $to = 'lei.wang@etocrm.com';
            $message->to($to)->subject('服务器异常');
        });
    }

    /*
     * @param获取用户信息
     * */
    public function userinfo()
    {
        $accessToken = WechatApp::where("appid",WechatApi::APPID)->value("access_token");
        $openid      = "o0RNKw60JqKP6yh-_IyLay3Px274";
        $res = $this->wechatapi->getUserInfo($accessToken,$openid);
        dd($res);
    }

    /*
     * @param发送微信模板消息
     * */
    public function send_template()
    {
        $data = array();
        $data['template_id'] = "kZ6YInEmxH3f4hlyLPq2dcALWvSgeLTK4rSySihmDHU";
        $data['first']       = "尊敬的水井坊会员，您已成功绑定会员账号，详情如下：";
        $data['keyword1']    = "12345";//
        $data['keyword2']    = date('Y-m-d H:i:s');//
        $data['remark']      ="更多会员相关信息请点击进入“悦坊会”--会员中心查看";
        $data['openid']      = "oW_q_uCfLs9GNCX9oBBx74Aph97s";
        $data['url']         = "";
        $param               = array('keyword1','keyword2');
        $ackey               = "f561db90333a73765ce1fd7991ffa9365be275896097drcjRTfOVl3mgTa9BvAWK";
        $res  = $this->wechatapi->send_template($data,2,$param,$ackey);
        dd($res);
    }
    /*
     * @param发送小程序模板消息
     * */
    public function send_mini_message()
    {
        $params['mini_openid'] = "abc123";
        $params['data']        = array(
            'keyword1' => "card_name",
            'keyword2' => "2018-09",
            'keyword3' => "business_name",
            'keyword4' => '用券前请致电餐厅，预约席位。如有疑问，欢迎在在公众号【水井坊】留言或周一至周五10:00-17:00拨打悦坊会会员专线4009199600'
        );
        $params['formid']      = 'formid';
        $params['page']        = "pages/memberCenter/my/index";
        $params['template_id'] =  "exchange_template_id";
        $access_token          = "15_j1sh5AP0CPm7kfRMDxgz8XnH-wDCMXBlwKn7iNFv-VI6G61zq3TDeSq2FfMLpI-LgLcwTZ30_rz-3J3rGvVEwA13CUhyYVxq0LwA48DCoHv67U6MfKvOvmSxVgZ5uAyr_fAFngNQKwBqjjkzPSTbAGALOP";
        $res = $this->wechatapi->send_mini_message($params,$access_token);
        dd($res);
    }

    public function api_route(Request $request)
    {
        dd($request->all());
    }
    /**
     * 自动登陆
     */
    public function autoLogin()
    {
        $miniUser = WechatMiniUser::find(1);
        if ($miniUser) {
            Cache::put($miniUser['session_id'], $miniUser->toArray(), 1200);
        }

        return $this->success($miniUser);
    }
}
