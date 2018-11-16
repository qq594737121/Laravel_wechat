<?php
/**
 * Created by PhpStorm.
 * User: lucky.li
 * Date: 2017/10/31
 * Time: 10:21
 */
namespace App\Libraries;
use Cache;

/**
 * 企业微信接口使用类
 * 微信工具类
 */
class WechatJssdk{
    private static $app_name = 'ikea';
    //企业ID
    public $corpid;
    //应用的凭证密钥
    public $corpsecret;
    //企业应用的id
    public $AgentId;

    /**
     * 从配置文件获取
     * WechatJssdk constructor.
     */
    public function __construct($data)
    {
        //$data = config('wechat.etocrmMsg');
        if(!$data || !is_array($data)){
            return false;
        }
        $this->corpid = array_get($data,'corpid');
        $this->corpsecret = array_get($data,'corpsecret');
        $this->AgentId = array_get($data,'AgentId');
    }
    /**
     * 获取access_token
     * @return mixed
     */
    public function getAccessToken(){
        $key = md5($this->corpid . $this->corpsecret);
        //判断access_token是否存在
        if ($access_token = Cache::get($key)){
            return $access_token;
        }
        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=".$this->corpid."&corpsecret=".$this->corpsecret;
        $resData = httpGet($url);
        $res = json_decode($resData,true);
        //access_token获取失败
        if (array_get($res,'errcode')!=0){
            return false;
        }
        //获取成功
        $access_token = array_get($res,'access_token');
        Cache::put($key,$access_token,60);
        return $access_token;
    }

    /**
     * 获取jsapi_ticket
     * @return bool|mixed
     */

    public function getJsApiTicket()
    {
        $js_key = md5($this->corpid . $this->corpsecret . "js_api_ticket");
        //如果jsapiticket存在，直接返回
        if ($jsApiTicket = Cache::get($js_key)){
            return $jsApiTicket;
        }
        //获取jsApiTicket
        $access_token = $this->getAccessToken();
        if (!$access_token){
            return false;
        }
        $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token={$access_token}";
        $resData = httpGet($url);
        $res = json_decode($resData,true);
        //获取失败有错误
        if (array_get($res,'errcode')!=0){
            return false;
        }
        //获取成功，缓存jsApiTicket
        $jsApiTicket = array_get($res,'ticket');
        Cache::put($js_key,$jsApiTicket,5);
        return $jsApiTicket;

    }
    /**
     * 创建随机字符串
     * @param int $length
     * @return string
     */
    public function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 调用企业微信获取jssdk
     * @param $url
     * @return array
     */
    public function jssdk($url)
    {
        $timestamp = (string)time();
        $nonceStr = $this->createNonceStr();
        $jsapi_ticket = $this->getJsApiTicket();
        if (!$jsapi_ticket){
            return false;
        }
        $string = "jsapi_ticket={$jsapi_ticket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
        $signPackage = array(
            //"debug"	=>false,
            "appId"     => $this->corpid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => sha1($string),
            "rawString" => $string,
            "jsApiList" =>array(
                "scanQRCode"
            )
        );
        return $signPackage;
    }

}
