<?php
namespace App\Libraries;

class Wechatci
{
    const API_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin';
    const MEDIA_GET_URL = '/media/get?';

    public      $wx_api  = 'https://api.weixin.qq.com';
    protected $appid;
    protected $woaap_api_url;
    protected $woaap_api_ip;
    protected $woaap_api_token;
    protected $appkey;
    protected $access_token;

    public function __construct()
    {
        $this->ci = & get_instance();
        $this->appid            = $this->ci->config->item('appid');
        $this->woaap_api_url    = $this->ci->config->item('woaap_api_url');
        $this->woaap_api_ip     = $this->ci->config->item('woaap_ip');
        $this->woaap_api_token  = $this->ci->config->item('woaap_api_token');
        $this->appkey           = $this->ci->config->item('appkey');


    }


    /**
     * 获取临时素材(认证后的订阅号可用)
     * @param string $media_id 媒体文件id
     * @param boolean $is_video 是否为视频文件，默认为否
     * @return raw data
     */
    public function getMedia($media_id,$is_video=false){
        if (!$this->getAccesstoken()) return false;
        $url_prefix = $is_video?str_replace('https','http',self::API_URL_PREFIX):self::API_URL_PREFIX;
        $url = $url_prefix.self::MEDIA_GET_URL.'access_token='.$this->access_token.'&media_id='.$media_id;
        return $url;


    }

    public function  saveMedia($url,$dir='comment'){
        if(empty($dir)) $dir = 'comment';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $media = array_merge(array('mediaBody' => $package), $httpinfo);

        //求出文件格式
        preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);

        $fileExt = $extmatches[1];
        $filename = time().rand(100,999).".{$fileExt}";

        $time = time();
        $file_path="./upload/".$dir."/".date('Y/m/d',$time).'/';
        $this->createdir($file_path);
        file_put_contents($file_path.$filename,$media['mediaBody']);
        return $file_path.$filename;
    }
    /**
     * 如果文件夹不存在，则递归创建文件夹
     * @param String $path 目录
     * @author Tujt 2013-07-08
     */
    protected function createdir($path){
        if (!file_exists($path)){
            $this->createdir(dirname($path));//递归创建文件夹
            mkdir($path, 0777);
        }
    }
    /**
     * 如果文件不存在，则创建文件
     * @param String $path 目录
     * @author Tujt 2013-07-08
     */
    protected function createfile($route){
        if (!file_exists($route)){
            touch($route);
        }
    }
    /*
     * @param获取access_token
     * */
    public function getAcountToken($appid='',$type = 1)
    {
    	if(empty($appid)) return '';
    	$this->ci->load->database('default',true);
    	$cacheType  = 'accessToken';
    	$mpInfo     = $this->ci->db->select('attr_value')->get_where('woaapkey',array('attr_key'=>$cacheType))->row_array();
    	$attrValue=$mpInfo['attr_value'];
    	if(!$attrValue or $type == 2){
            $url       = $this->woaap_api_ip;
            $ackey_row = $this->ci->db->select('attr_value')->get_where('woaapkey',array('attr_key'=>'ackey'))->row_array();
            $url       = $url.'/api/accesstoken?ackey='.$ackey_row['attr_value'];
            $jsoninfo  = $this->http_get($url);
            $result    = json_decode($jsoninfo,true);
            $attrValue = $result['access_token'];
            $this->ci->db->where('attr_key',$cacheType);
            $arr       =    array
            (
                'attr_value'  => $attrValue,
                'update_time' => date('Y-m-d H:i:s')
            );
            $this->ci->db->update('woaapkey',$arr);
    	}
    	return $attrValue;
    }

    public function createQrcode($userId,$time)
    {
        if(!$qrcode = $this->newgetQRCode($userId, 0,$time))
            return false;

        return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($qrcode['ticket']);
    }
    public function new_createQrcode($userId)
    {
        if(!$qrcode = $this->getQRCode($userId,$type=2,$time))
            return false;

        return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($qrcode['ticket']);
    }
    public function jssdk($url)
    {
        $timestamp	= time();
        $nonceStr = $this->createNonceStr();
        $jsapiTicket = $this->getJsApiTicket();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
        $signPackage = array(
            "debug"	  =>false,
            "appId"     => $this->appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => sha1($string),
            "string" => $string,
            "jsApiList" =>array(
                "onMenuShareTimeline",
                "onMenuShareAppMessage",
                "onMenuShareQQ",
                "onMenuShareWeibo",
                "onMenuShareQZone",
                "startRecord",
                "stopRecord",
                "onVoiceRecordEnd",
                "playVoice",
                "pauseVoice",
                "stopVoice",
                "onVoicePlayEnd",
                "uploadVoice",
                "downloadVoice",
                "chooseImage",
                "previewImage",
                "uploadImage",
                "downloadImage",
                "translateVoice",
                "getNetworkType",
                "openLocation",
                "getLocation",
                "hideOptionMenu",
                "showOptionMenu",
                "hideMenuItems",
                "showMenuItems",
                "hideAllNonBaseMenuItem",
                "showAllNonBaseMenuItem",
                "closeWindow",
                "scanQRCode",
                "chooseWXPay",
                "openProductSpecificView",
                "addCard",
                "chooseCard",
                "openCard"
            )
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    public function getAccesstoken()
    {
        $access_token  = $this->getAcountToken($this->appid,1);
        if ($access_token) {

            return $this->access_token = $access_token;
        }

        return false;
    }
    /**
     * 创建二维码ticket
     * @param int|string $scene_id 自定义追踪id,临时二维码只能用数值型
     * @param int $type 0:临时二维码；1:永久二维码(此时expire参数无效)；2:永久二维码(此时expire参数无效)
     * @param int $expire 临时二维码有效期，最大为1800秒
     * @return array('ticket'=>'qrcode字串','expire_seconds'=>1800,'url'=>'二维码图片解析后的地址')
     */
    private function getQRCode($scene_id, $type = 0, $time)
    {
        if (!$this->getAccesstoken()) return false;
        $type = ($type && is_string($scene_id)) ? 2 : $type;
        $data = array(
            'action_name' => $type ? ($type == 2 ? "QR_LIMIT_STR_SCENE" : "QR_LIMIT_SCENE") : "QR_SCENE",
            'expire_seconds' => $time,
            'action_info' => array('scene' => ($type == 2 ? array('scene_str' => $scene_id) : array('scene_id' => $scene_id)))
        );

        if ($type == 1 || $type == 2) {
            unset($data['expire_seconds']);
        }
        $result = $this->http_post('https://api.weixin.qq.com/cgi-bin/qrcode/create?' . 'access_token=' . $this->access_token, json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }
    /**
     * @param创建临时二维码
     */
    private function newgetQRCode($scene_id, $type = 0, $time)
    {
        if (!$this->getAccesstoken()) return false;

        $data = array(
            'action_name' => "QR_STR_SCENE",
            'expire_seconds' => $time,
            'action_info' => array('scene' =>array('scene_str' =>$scene_id))
        );
        $result = $this->http_post('https://api.weixin.qq.com/cgi-bin/qrcode/create?' . 'access_token=' . $this->access_token, json_encode($data));
        if ($result) {
//            $this->insertLog(array('content'=>$result,'createtime'=>date('Y-m-d H:i:s'),'type'=>666));
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 移动用户分组
     * @param int $groupid 分组id
     * @param string $openid 用户openid
     * @return boolean|array
     */
    public function updateGroupMembers($groupid,$openid){
        if (!$this->getAccesstoken()) return false;

        $data = array(
            'openid'=>$openid,
            'to_groupid'=>$groupid
        );
        $result = $this->http_post('https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token='.$this->access_token,json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取在会员卡激活时填写的数据
     * @param $activateTicket
     * @return bool|mixed
     */
    public function getMembercardActivateTempInfo($activateTicket)
    {
        if (!$this->getAccesstoken()) return false;

        $data = array(
            'activate_ticket'=>$activateTicket,
        );

        $result = $this->http_post('https://api.weixin.qq.com/card/membercard/activatetempinfo/get?access_token='.$this->access_token,json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json['info'];
        }
        return false;
    }

    /**
     * code 解码
     * @param string $encrypt_code 通过 choose_card_info 获取的加密字符串
     * @return boolean|array
     * {
     *  "errcode":0,
     *  "errmsg":"ok",
     *  "code":"751234212312"
     *  }
     */
    public function decryptCardCode($encrypt_code) {
        if (!$this->getAccesstoken()) return false;

        $data = array(
            'encrypt_code' => $encrypt_code,
        );
        $result = $this->http_post('https://api.weixin.qq.com/card/code/decrypt?access_token=' . $this->access_token, json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return $json['code'];
        }
        return false;
    }

    /**
     * 激活/绑定会员卡
     * @param string $data 具体结构请参看卡券开发文档(6.1.1 激活/绑定会员卡)章节
     * @return boolean
     */
    public function activateMemberCard($data) {
        if (!$this->getAccesstoken()) return false;

        $result = $this->http_post('https://api.weixin.qq.com/card/membercard/activate?access_token=' . $this->access_token, json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg  = $json['errmsg'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * GET 请求
     * @param string $url
     */
    public function http_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );

        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    public function http_post($url,$param,$post_file=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }
    /*
     * @param获取ackey
     * */
    public function getACKey($type =1)
    {
    	$this->ci->load->database('default',true);
    	$cacheType = 'ackey';
    	$mpInfo    = $this->ci->db->select('*')->get_where('woaapkey',array('attr_key'=>$cacheType))->row_array();
    	$ackey     = $mpInfo['attr_value'];
    	if(!$ackey or $type == 2){
    		$url   = $this->woaap_api_url."/api/ackey?appid=".$this->appid."&appkey=".$this->appkey;
    		$jsoninfo = $this->http_get($url);
    		$result   = json_decode($jsoninfo,true);
    		$ackey    = $result['ackey'];
    		$this->ci->db->where('attr_key',$cacheType);
    		$arr      = array(
    				'attr_value'=>$ackey,
    				'update_time'=>date('Y-m-d H:i:s')
    		);
    		$this->ci->db->update('woaapkey',$arr);
    	}
    	return  $ackey;
    }
    /*
    * @获得JsApiTicket
    * */
    public function executeJsApiTicket()
    {
        $jsapiTicket = '';
        $ackey = $this->getACKey();
        //正式服务器用woaap_api_url   测试服务器用woapp_api
        $url = $this->woaap_api_url."/api/jsticket?ackey=".$ackey;
        $jsoninfo = $this->http_get($url);

        $result = json_decode($jsoninfo,true);
        if($result['errcode']==0){
            $jsapiTicket = $result['js_ticket'];
        }else {
            $ackey = $this->getACKey(2);
            //正式服务器用woaap_api_url   测试服务器用woapp_api
            $url = $this->woaap_api_url."/api/jsticket?ackey=".$ackey;
            $jsoninfo = $this->http_get($url);
            $result = json_decode($jsoninfo,true);
            $jsapiTicket = $result['js_ticket'];
        }
        $cacheType  =   'jsticket';
        $this->ci->load->database('default',true);
        $this->ci->db->where('attr_key',$cacheType);
        $arr=array(
            'attr_value'=>$jsapiTicket,
            'update_time'=>date('Y-m-d H:i:s')
        );
        $this->ci->db->update('sjf_woaapkey',$arr);
        return  $jsapiTicket;

    }

    //记录日志
    public function insertLog($array)
    {
        $this->ci->load->database('default',true);
        $this->ci->db->insert('sjf_push_log',$array);
    }
    /*
   * @获得JsApiTicket
   * */
    public function getJsApiTicket()
    {
        $jsapiTicket = '';
        $cacheType   ='jsticket';
        $this->ci->load->database('default',true);
        $this->ci->db->where('attr_key',$cacheType);
        $mpInfo     = $this->ci->db->select('*')->get_where('sjf_woaapkey',array('attr_key'=>$cacheType))->row_array();
        $jsapiTicket= $mpInfo['attr_value'];
        return  $jsapiTicket;
    }
    /********************新增******************************/
    public function getCardsignature($cardid,$ac,$type){
        $timestamp  = time();
        $nonceStr = $this->createNonceStr();
        $jsapiTicket = $this->getJsTicket($ac);
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr={$nonceStr}&timestamp={$timestamp}";
        $signature = sha1($string);
        $api_ticket = $this->getApiTickets();

        $html_params = [];
        $params = [];
        array_push ( $params, ( string ) $timestamp );$html_params['timestamp']=$timestamp;
        array_push ( $params, ( string ) $api_ticket );$html_params['api_ticket']=$api_ticket;
        array_push ( $params, ( string ) $cardid );$html_params['cardid']=$cardid;
        array_push ( $params, ( string ) $nonceStr );$html_params['nonceStr']=$nonceStr;
        $card_signature = $this->cardSignature($params);$html_params['card_signature']=$card_signature;

        $cards= [];
        $card_ext = '{"timestamp": "'.$timestamp.'", "signature":"'.$card_signature.'","nonce_str":"'.$nonceStr.'","outer_str":"'.$type.'"}';
        $cards[] = ["cardId"=>$cardid,"cardExt"=>$card_ext];


        $result = array(
            "api_ticket" => $jsapiTicket,
            "js_ticket" => $jsapiTicket,
            "new_api_ticket"=>$api_ticket,
            "timestamp" => $timestamp,
            "nonce_str" => $nonceStr,
            // "outer_str" => $value,
            "card_id" => $cardid,
            "signature" => $signature,
            "card" =>$html_params,
            "card_json" =>$cards
        );
        return $result;
    }
    /*
    * @param核销优惠券
    * */
    public function cancelCard($code,$access_token)
    {
        $url         =   "https://api.weixin.qq.com/card/code/consume?access_token=$access_token";
        $arr['code'] =   $code;
        $res         =   $this->httpPost($url,json_encode($arr));
        $result      =   json_decode($res,true);
        return $result;
    }
    /*
     * @param获取jsticket
     * */
    public function getJsTicket($ac)
    {
        $jsapiTicket = '';
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$ac&type=jsapi";

        $jsoninfo = $this->http_get($url);

        $result = json_decode($jsoninfo, true);
        $jsapiTicket = $result['ticket'];

        return $jsapiTicket;
    }
    /*
     * @param获取api tickets
     * */
    public function getApiTickets()
    {
        $ackey = $this->getACKey();
        //正式服务器用woaap_api_url   测试服务器用woapp_api
        $url = $this->woaap_api_url."/api/apiticket?ackey=".$ackey;
        $jsoninfo = $this->http_get($url);
        $result = json_decode($jsoninfo,true);

        if($result['errcode']==0){
            $apiTicket = $result['api_ticket'];
        }else {
            $ackey = $this->getACKey(2);
            //正式服务器用woaap_api_url   测试服务器用woapp_api
            $url = $this->woaap_api_url."/api/apiticket?ackey=".$ackey;
            $jsoninfo = $this->http_get($url);
            $result = json_decode($jsoninfo,true);
            $apiTicket = $result['api_ticket'];
        }
        return  $apiTicket;
    }
    public function httpPost($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt ( $curl, CURLOPT_POST, 1 );
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 10 );
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data );
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
    /*
     * @param card签名
     * */
    public function cardSignature($params){
        sort ( $params );
        return sha1 ( implode ( $params ) );
    }

    /*
     * @param缓存API-ticket
     * */
    public function executeApiTicket()
    {
        $this->ci->load->database('default',true);
        $cacheType = 'apiticket';
        $ackey     = $this->getACKey();
        //正式服务器用woaap_api_url   测试服务器用woapp_api
        $url        = $this->woaap_api_url."/api/apiticket?ackey=".$ackey;
        $jsoninfo   = $this->http_get($url);
        $result     = json_decode($jsoninfo,true);

        if($result['errcode']==0){
            $apiTicket = $result['api_ticket'];
        }else {
            $ackey = $this->getACKey(2);
            //正式服务器用woaap_api_url   测试服务器用woapp_api
            $url = $this->woaap_api_url."/api/apiticket?ackey=".$ackey;
            $jsoninfo = $this->http_get($url);
            $result = json_decode($jsoninfo,true);
            $apiTicket = $result['api_ticket'];
        }

        $this->ci->db->where('attr_key',$cacheType);
        $arr=array(
            'attr_value'=>$apiTicket,
            'update_time'=>date('Y-m-d H:i:s')
        );
        $this->ci->db->update('woaapkey',$arr);
        return  $apiTicket;
    }
    /**
     * 组装单个卡券信息  新版本
     * @param $card_id
     * @param string $code
     * @param int $diff_time
     * @param int $type
     * @return array
     */
    public function getCardData($card_id, $code = "", $diff_time = 0, $type = 0)
    {
        if (empty($card_id)) {
            return array();
        }

        //获取api_ticket
        $api_ticket = $this->getApiTickets($type);

        //获取nonceStr
        $nonceStr = md5(uniqid());

        //获取时间戳
        $timestamp = time() + $diff_time;

        //生成卡券签名
        $signature_arr = array();
        array_push($signature_arr, ( string )$card_id);
        if (!empty($code)) {
            array_push($signature_arr, ( string )$code);
        }
        array_push($signature_arr, ( string )$timestamp);
        array_push($signature_arr, ( string )$api_ticket);
        array_push($signature_arr, ( string )$nonceStr);
        sort($signature_arr, 2);
        $signature = sha1(implode($signature_arr));

        if (empty($code)) {
            $cardExt = json_encode(['timestamp' => $timestamp, 'nonce_str' => $nonceStr, 'signature' => $signature]);
        } else {
            $cardExt = json_encode(['code' => $code, 'timestamp' => $timestamp, 'nonce_str' => $nonceStr, 'signature' => $signature]);
        }

        $result = array(
            'cardId' => $card_id,
            'cardExt' => $cardExt
        );

        return $result;
    }
    /*
     * @param会员卡积分更新
     * */
    public function  memberCardUpdate($code,$num,$sum,$type=1)
    {
        $appid          =   $this->ci->config->config['appid'];
        $ac             =   $this->getAcountToken($appid,$type);
        $url            =   'https://api.weixin.qq.com/card/membercard/updateuser?access_token='.$ac;
        $postData['code']           =   $code;
        $postData['card_id']        =   MEMBERCARDID1 ;
        $postData['record_bonus']   =   '增加'.$num;
        $postData['add_bonus']      =   $num;
        $postData['bonus']          =   $sum ;
        $jsoninfo = $this->httpPost($url, json_encode($postData,JSON_UNESCAPED_UNICODE));
        $result = json_decode($jsoninfo,true);
        return $result;
    }
    /*
    * @param发送小程序模版消息
    * @param mini_openid
     * @param data
     * @param formid
     * @param mini_appid
     * @param template_id
    * */
    public function send_mini_message($mini_openid,$data,$formid,$mini_appid,$template_id,$page)
    {
        //准备小程序模版消息内容
//        $data_arr = array(
//            'keyword1' => array( "value" => $data, "color" => '173177')  //这里根据你的模板对应的关键字建立数组，color 属性是可选项目，用来改变对应字段的颜色
//        );
        for($i=1;$i<= count($data);$i++)
        {
            $data_arr['keyword'.$i]['value'] = $data['keyword' . $i];
            $data_arr['keyword'.$i]['color'] = '#173177';
        }
        //准备小程序模版消息data
        $post_data = array (
            "touser"           => $mini_openid,  //用户小程序的openID，可用过 wx.getUserInfo 获取
            "template_id"      => $template_id,  //小程序后台申请到的模板编号
//            "page"             => "/pages/check/result?orderID=11",//点击模板消息后跳转到的页面，可以传递参数
            "form_id"          => $formid,   //第一步里获取到的 formID
            "data"             => $data_arr, //消息内容
//            "emphasis_keyword" => "keyword2.DATA"    //需要强调的关键字，会加大居中显示
        );
        if(!empty($page))
        {
            $post_data['page'] = $page;
        }
        $this->ci->load->database('default',true);
        $mpInfo = $this->ci->db->select('access_token')->get_where('sjf_mp_conf',array('appid'=>$mini_appid))->row();
        $url    = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$mpInfo->access_token;//这里替换为你的 appID 和 appSecret
        $data   = json_encode($post_data, true);//将数组编码为 JSON
        $res    = $this->httpPost($url,$data);
        return $res;
    }
    /**
     * 获取单张卡券信息
     */
    public function get_card($card_id="")
    {
        if (!$this->getAccesstoken()) return false;
        $url = "https://api.weixin.qq.com/card/get?access_token=".$this->access_token;
        $data = array("card_id"  => $card_id);
        $res = $this->https_request($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        return json_decode($res, true);
    }

    /**
     * 获取用户已领取卡券接口
     */
    public function get_cardlist($card_id="",$openid="")
    {
        if (!$this->getAccesstoken()) return false;
        $url = "https://api.weixin.qq.com/card/user/getcardlist?access_token=".$this->access_token;
        $data = array("card_id"  => $card_id,"openid" => $openid);
        $res = $this->https_request($url,json_encode($data,JSON_UNESCAPED_UNICODE));

        return json_decode($res, true);
    }

    /**
     * 自定义CODE 导入微信（最多支持100张code）
     */
    public function import_code($card_id='',$code=array())
    {
        if(count($code)>100) return false;
        if (!$this->getAccesstoken()) return false;
        $data = array("card_id"  => $card_id, "code"=>$code);
        $url = "http://api.weixin.qq.com/card/code/deposit?access_token=".$this->access_token;
        $res = $this->https_request($url, json_encode($data,JSON_UNESCAPED_UNICODE));
        return json_decode($res, true);
    }

    /**
     * 查询导入code数目接口
     */
    public function get_code_num($card_id="")
    {
        if (!$this->getAccesstoken()) return false;
        $url = "http://api.weixin.qq.com/card/code/getdepositcount?access_token=".$this->access_token;
        $data = array("card_id"  => $card_id);
        $res = $this->https_request($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        return json_decode($res, true);
    }

    /**
     * 核查code接口
     * exist_code为已经成功存入的code
     * not_exist_code为没有存入的code
     */
    public function check_code($card_id="",$code=array())
    {
        if (!$this->getAccesstoken()) return false;
        $data = array("card_id"  => $card_id, "code"=>$code);
        $url = "http://api.weixin.qq.com/card/code/checkcode?access_token=".$this->access_token;
        $res = $this->https_request($url, json_encode($data,JSON_UNESCAPED_UNICODE));
        return json_decode($res, true);
    }

    /**
     * 核销Code接口
     */
    public function consume_code($card_id="",$code="")
    {
        if (!$this->getAccesstoken()) return false;
        $data = array("card_id"  => $card_id, "code"=>$code);
        $url = "https://api.weixin.qq.com/card/code/consume?access_token=".$this->access_token;
        $res = $this->https_request($url, json_encode($data,JSON_UNESCAPED_UNICODE));
        return json_decode($res, true);
    }

    /**
     * 查询Code接口
     */
    public function get_code($card_id="",$code="")
    {
        if (!$this->getAccesstoken()) return false;
        $url = "https://api.weixin.qq.com/card/code/get?access_token=".$this->access_token;
        $data = array("card_id"  => $card_id,"code" => $code,"check_consume"=>true);
        $res = $this->https_request($url,json_encode($data,JSON_UNESCAPED_UNICODE));

        return json_decode($res, true);
    }
    /**
     * 微信发送模板消息
     * @author lei.wang
     */
    public function send_template_message($appid,$data)
    {
        if(empty($appid) || empty($data)) return array();
        $access_token = $this->getAcountToken($appid,1);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $data = urldecode(json_encode($data));
        $json = $this->https_request($url,$data);
        $res = json_decode($json,true);
        if(isset($res['errcode']) && $res['errcode']==0){//发送成功
            return $res;
        }else{//发送失败
            $access_token = $this->getAcountToken($appid,2);
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
            $data = urldecode(json_encode($data));
            $json = $this->https_request($url,$data);
            return json_decode($json,true);
        }
    }
    /**
     * 上传图片到微信卡券专用
     */
    public function updatePic($access_token,$file_name)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$access_token."&type=image";//上证图片到微信卡券专用
//      $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=image";//新增临时素材
//        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=ACCESS_TOKEN";//新增永久临时素材
        //$wx_res = $this->updatePic($app_id,[$_FILES['cover_photo']['tmp_name'],$_FILES['cover_photo']['type'],$_FILES['cover_photo']['name']]);
        $cfile = new \CURLFile($file_name[0],$file_name[1],$file_name[2]);
        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_SSL_VERIFYPEER] = false;
        $curl_options[CURLOPT_SSL_VERIFYHOST] = false;
        $curl_options[CURLOPT_POST] = true;
        $curl_options[CURLOPT_POSTFIELDS] = array('buffer'=>$cfile);
        $rs = $this->do_curl($curl_options);
        $rs = json_decode($rs,true);
        return $rs;
    }
    /*
     * @param
     * */
    public function do_curl($curl_options)
    {
        if(empty($curl_options)){
            return null;
        }
        $ch = curl_init();var_dump($ch);
        $curl_options[CURLOPT_SSL_VERIFYPEER]=false;
        $curl_options[CURLOPT_SSL_VERIFYHOST]=false;
        $rs = curl_setopt_array($ch,$curl_options);
        if($rs){
            $output = curl_exec($ch);
        }else{
            $output = null;
        }
        return $output;
    }
    /**
     * 解绑会员卡
     */
    public function unactivate($card_id = '', $code = '', $type=0){

        if (!$this->getAccesstoken()) return false;
        $url = $this->wx_api."/card/membercard/unactivate?access_token=".$this->access_token;
        $data = array(
            "card_id" => $card_id,
            "code" => $code
        );
        $json = $this->https_request($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        return json_decode($json, true);
    }

    /*
     * @param获取临时素材图片地址
     * */
    public function getmedias($access_token,$media_id,$foldername)
    {
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        if (!file_exists("./upload/".$foldername)) {
            mkdir("./upload/".$foldername, 0777, true);
        }
        $targetName = './upload/'.$foldername.'/'.date('YmdHis').rand(1000,9999).'.jpg';
        $ch = curl_init($url); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $targetName;
    }
    /*
   *@param上传图片
   *@需要guzll类库
   * */
    public function upload($url, $path, array $queries = [], $isRaw = false)
    {
        if (!file_exists($path) || !is_readable($path)) {
            return false;
        }
        $multipart = array();
        $multipart[] = [
            'name' => 'buffer',
            'contents' => fopen($path, 'r'),
        ];
        $res = $this->https_request($url, [
            'query' => $queries,
            'multipart' => $multipart
        ], $isRaw);

        return $res;
    }

    /**
     * https请求（支持GET和POST）
     */
    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *      客服消息接口     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /**
     * 发送文本消息
     */
    public function sendMessageText($openid="",$content="",$type=0){
        if(empty($openid) || empty($content)) return array();

        if (!$this->getAccesstoken()) return false;
        $url = $this->wx_api."/cgi-bin/message/custom/send?access_token=".$this->access_token;
        $param = array(
            "touser"  => $openid,
            "msgtype" => "text",
            "text"    => array("content"=>$content)
        );
        $json = $this->https_request($url,json_encode($param,JSON_UNESCAPED_UNICODE));
        return json_decode($json, true);
    }

    /**
     * 发送图片消息
     */
    public function sendMessageImage($openid="",$media_id="",$type=0){
        if(empty($openid) || empty($media_id)) return array();

        if (!$this->getAccesstoken()) return false;
        $url = $this->wx_api."/cgi-bin/message/custom/send?access_token=".$this->access_token;
        $param = array(
            "touser"  => $openid,
            "msgtype" => "image",
            "image"   => array("media_id"=>$media_id)
        );
        $json = $this->https_request($url,json_encode($param,JSON_UNESCAPED_UNICODE));
        return json_decode($json, true);
    }

    /**
     * 发送图文消息
     */
    public function sendMessageNews($openid="",$news_arr=array(),$type=0){
        if(empty($openid) || empty($news_arr)) return array();

        if (!$this->getAccesstoken()) return false;
        $url = $this->wx_api."/cgi-bin/message/custom/send?access_token=".$this->access_token;
        $param = array(
            "touser"  => $openid,
            "msgtype" => "news",
            "news"    => array("articles"=>$news_arr)
        );
        $json = $this->https_request($url,json_encode($param,JSON_UNESCAPED_UNICODE));
        return json_decode($json, true);
    }

    /**
     * 发送卡券
     */
    public function sendMessageCard($openid="",$card_id='',$type=0){
        if(empty($openid) || empty($card_id)) return array();

        if (!$this->getAccesstoken()) return false;
        $url = $this->wx_api."/cgi-bin/message/custom/send?access_token=".$this->access_token;
        $param = array(
            "touser"  => $openid,
            "msgtype" => "wxcard",
            "wxcard"  => array("card_id"=>$card_id)
        );
        $json = $this->https_request($url,json_encode($param,JSON_UNESCAPED_UNICODE));
        return json_decode($json, true);
    }

    /**
     * 发送模板消息
     */
    public function sendTemplate($openid="",$template_id="",$template_url="",$data=array(),$type=0){
        if(empty($openid) || empty($template_id) || empty($data)) return array();

        if (!$this->getAccesstoken()) return false;
        $url = $this->wx_api."/cgi-bin/message/template/send?access_token=".$this->access_token;
        $param = array(
            "touser"      => $openid,
            "template_id" => $template_id,
            'topcolor'    => "#000000",
            "url"         => $template_url,
            "data"        => $data
        );
        $json = $this->https_request($url,json_encode($param,JSON_UNESCAPED_UNICODE));
        return json_decode($json, true);
    }

}