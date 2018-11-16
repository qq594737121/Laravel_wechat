<?php
namespace App\Libraries;

use DB;
/**
 * 工具类
 * Class Tools
 * @package App\Libraries
 */
class Interfacelog
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
        * @param安课程接口
        * @author  lei.wang@etocrm.com
        * */
    public function getPreferStore($cardNumber,$route)
    {
        $url = env('IKEA_CRM_URL') . 'getPreferStore';
        $postData['card'] = $cardNumber;
        $postData['signature'] = strtoupper(md5($cardNumber . env('IKEA_CRM_KEY')));
        //开始
        $interface_log['request_time']  = date("Y-m-d H:i:s",time());
        $jsoninfo = $this->common->postJson($url, json_encode($postData));

        $interface_log['back_time']     = date("Y-m-d H:i:s",time());
        $interface_log['name']          = $route;//路由地址
        $interface_log['request_url']   = $url;//接口地址
        $interface_log['keys']          = "安课程Coupon模板查询接口";
        $interface_log['request_data']  = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $interface_log['back_data']     = $jsoninfo;//返回数据
        $res = json_decode($jsoninfo,true);
        $interface_log['back_code']     = $res['resCode'];
        $interface_log['back_msg']      = $res['resMsg'];
        $this->interfacelog_data($interface_log);
        return $res;
    }



    /**
     *@param接口记录
     */
    public  function interfacelog_data($log)
    {
        $now_time = time();
        $use_time = $now_time - strtotime($log['request_time']);
        $log['use_time']  = $use_time;
        DB::table("general_interface_log")->insert($log);
    }

}
