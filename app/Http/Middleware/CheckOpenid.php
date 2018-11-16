<?php
namespace App\Http\Middleware;

use Closure;
use App\Libraries\Wechat;
//use App\Libraries\Common;
class CheckOpenid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        var_dump($request->fullUrl());//获取当前路由全链接地址
//        var_dump((new Common())->encodeOpenId("oW_q_uGebsdPOibjEwE5dZ8hD6gg"));
//        var_dump((new Common())->decodeOpenId("eciAIlUV2f/omfTOj3S1P6ytUs6JlsgfqHB1mIku5jY="));exit;
        if(env('APP_DEBUG') && $request->has('openid'))
            $request->session()->put('openid', $request->get('openid'));

        if($request->session()->has('openid'))
            return $next($request);

        $wechat = new Wechat();
        if(!$userInfo = $wechat->getOauthAccessToken())
            return redirect($wechat->getOauthRedirect($request->fullUrl(), 'swellfun', 'snsapi_base'));

        $request->session()->put('openid', $userInfo['openid']);
        if(isset($userInfo['openid'])) {
            $request->session()->put('openid', $userInfo['openid']);
        }else{
            $userDetail = $wechat->getUserInfo($userInfo['openid']);
            if($userDetail && isset($userDetail['openid'])){
                $request->session()->put('openid', $userDetail['openid']);
            }
        }
        return $next($request);
    }
}
