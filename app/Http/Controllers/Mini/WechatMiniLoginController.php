<?php
namespace App\Http\Controllers\Mini;

use App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Model\WechatMiniUser;
use App\Libraries\Common;
class WechatMiniLoginController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['checkSessionId'])->except(['code']);
        $this->common = Common::getInstance();
    }

    /**
     * 登录code
     */
    public function code(Request $request)
    {
        $code = $request->input('code');
        if (empty($code)) {
            return $this->common->ajaxReturn("参数不完整",1);
        }
        $miniProgram = app('wechat.mini_program');
        $resp = $miniProgram->auth->session($code);
        if (!isset($resp['openid']) || !isset($resp['session_key'])) {
            return $this->common->ajaxReturn("session_key、openid获取失败",2);
        }
        $res['session_id'] = md5($resp['openid'].$resp['session_key']);
        $miniUser = WechatMiniUser::updateOrCreate(['openid' => $resp['openid']], $resp + $res);
        $res['unionid'] = empty($miniUser['unionid']) || empty($miniUser['language']) ? 0 : 1;
        Cache::put($res['session_id'], $miniUser->toArray(), 1200);
        return $this->common->ajaxReturn($res,200);
    }

    /**
     * 用户信息解密
     */
    public function user(Request $request)
    {
        $miniUser    = $request['miniUser'];
        $iv          = $request->input('iv');
        $encryptData = $request->input('encryptedData');
        if (empty($iv) || empty($encryptData)) {
            return $this->common->ajaxReturn("参数不完整",1);
        }
        $miniProgram = app('wechat.mini_program');
        try {
            $decryptedData = $miniProgram->encryptor->decryptData($miniUser['session_key'], $iv, $encryptData);
        } catch (\Exception $e) {
            Log::warning($e->getMessage(), $request->input());
            if (empty($miniUser['unionid'])) {
                return $this->common->ajaxReturn("unionid获取失败", 2);
            }
        }
        if (empty($decryptedData['openId'])) {
            return $this->common->ajaxReturn("openid解密失败",3);
        }
        $decryptedData = array_change_key_case($decryptedData, CASE_LOWER);
        $miniUser = WechatMiniUser::updateOrCreate(['openid' => $decryptedData['openid']], $decryptedData);
        Cache::put($miniUser['session_id'], $miniUser->toArray(), 1200);

        return $this->common->ajaxReturn($miniUser,200);
    }



}
