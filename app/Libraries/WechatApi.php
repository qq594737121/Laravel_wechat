<?php
namespace App\Libraries;

/**
 * 微信公众平台接口
 * Class WoaapApi.
 */
class WechatApi extends Http
{
    protected static $_instance;
    // appid
    const APPID = 'wx18a261d746a371ff';
    // appkey
    const APPKEY = 'b2e290a46181f089ca2a234aaa5b139b';
    // 用户信息
    const API_USER_INFO_URI         = 'https://api.weixin.qq.com/cgi-bin/user/info';
    // 查询门店列表
    const API_GET_POI_LIST_URI      = 'https://api.weixin.qq.com/cgi-bin/poi/getpoilist';
    // 获取门店信息列表
    const API_GET_STORE_LIST_URI    = 'https://api.weixin.qq.com/wxa/get_store_list';
    // Code解码接口
    const API_CARD_CODE_DECRYPT_URI = 'https://api.weixin.qq.com/card/code/decrypt';
    // 微信模板消息接口(中控)
    const API_TEMPLATE_SEND_URL     = 'http://api.woaap.com/api/message-template-send';
    // 小程序模板消息(微信官方)
    const API_MINI_TEMPLATE_SEND_URL= 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send';

     public static function getInstance()
     {
         if(isset(self::$_instance)) {
             return self::$_instance;
         }
         self::$_instance = new self();
         return self::$_instance;
     }
    /**
     * 获取用户基本信息
     * @param $accessToken
     * @param $openid
     * @return bool|mixed
     */
    public  function getUserInfo($accessToken, $openid)
    {
        $query = [
            'access_token' => $accessToken,
            'openid' => $openid,
            'lang' => 'zh_CN',
        ];

        return parent::parseJSON('get', [self::API_USER_INFO_URI,$query]);
    }

    /**
     * 查询门店列表
     * @param     $accessToken
     * @param int $begin
     * @param int $limit
     * @return bool|mixed
     */
    public  function getPoiList($accessToken, $begin = 0, $limit = 20)
    {
        return parent::parseJSON('json', [
            self::API_GET_POI_LIST_URI,
            [
                'begin' => $begin,
                'limit' => $limit,
            ],
            [
                'access_token' => $accessToken,
            ],
        ]);
    }

    /**
     * 获取门店信息列表
     * @param     $accessToken
     * @param int $offset
     * @param int $limit
     * @return bool|mixed
     */
    public  function getStoryList($accessToken, $offset = 0, $limit = 20)
    {
        return parent::parseJSON('json', [
            self::API_GET_STORE_LIST_URI,
            [
                'offset' => $offset,
                'limit' => $limit,
            ],
            [
                'access_token' => $accessToken,
            ],
        ]);
    }

    /**
     * Code解码接口
     * @param $accessToken
     * @param $encryptCode
     * @return bool|mixed
     */
    public  function cardCodeDecrypt($accessToken, $encryptCode)
    {
        return parent::parseJSON('json', [
            self::API_CARD_CODE_DECRYPT_URI,
            [
                'encrypt_code' => $encryptCode,
            ],
            [
                'access_token' => $accessToken,
            ],
        ]);
    }

    /**
     *@param微信模板消息接口(中控)
     */
    public function send_template($data,$number,$array=array(),$ackey)
    {
        $items['touser']      = $data['openid'];
        $items['template_id'] = $data['template_id'];
        $items['url']         = $data['url'];
        $items['data']['first']['value'] = $data['first'] ;
        $items['data']['first']['color'] = '#173177';
        for($i=0;$i<$number;$i++){
            $items['data'][$array[$i]]['value'] = $data[$array[$i]];
            $items['data'][$array[$i]]['color'] = '#173177';
        }
        $items['data']['remark']['value'] = $data['remark'];
        $items['data']['remark']['color'] = '#173177';
        return parent::parseJSON('json',[
            self::API_TEMPLATE_SEND_URL,
            $items,
            [
                'ackey'=>$ackey
            ]
        ]);
    }
    /*
     * @param小程序模板消息(微信官方)
     * $mini_openid
     * $data
     * $formid
     * $template_id
     * $page
     * */
    public function send_mini_message($params,$access_token)
    {
        $data = $params['data'];
        for($i=1;$i<= count($params['data']);$i++)
        {
            $data_arr['keyword'.$i]['value'] = $data['keyword' . $i];
            $data_arr['keyword'.$i]['color'] = '#173177';
        }
        //准备小程序模版消息data
        $post_data["touser"]        = $params['mini_openid'];  //用户小程序的openID，可用过 wx.getUserInfo 获取
        $post_data["template_id"]   = $params['template_id'];  //小程序后台申请到的模板编号
        $post_data["form_id"]       = $params['formid'];       //第一步里获取到的 formID
        $post_data["data"]          = $data_arr;               //消息内容
//      $post_data["emphasis_keyword"] = "keyword2.DATA";      //需要强调的关键字，会加大居中显示
        if(!empty($params['page'])){
            $post_data['page'] = $params['page'];
        }
        return parent::parseJSON('json',[
                self::API_MINI_TEMPLATE_SEND_URL,
                $post_data,
                [
                    "access_token" => $access_token
                ]
            ]);
    }



}
