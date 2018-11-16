<?php
namespace App\Libraries;

/**
 * 公共类 
 */
class Common
{
    private static $_instance;


    /**
     * 初始化方法
     */
    public function __construct()
    {

    }

    /**
     *
     * @return
     */
    public static function getInstance()
    {
        if(isset(self::$_instance)) {
            return self::$_instance;
        }
        self::$_instance = new self();
        return self::$_instance;
    }

    # code
    /**
     * 加密
     * @author Toby.tu 2016-08-30
     */
    public function pwd($code='')
    {
        if(empty($code)){
            return '';
        }
        $key = 'dslr_pwd_encode';
        $pwd = sha1($key.$code.$key);
        $pwd = md5($key.$code.$key);
        return md5($pwd);
    }
    /**
     * 加密openid
     * @author Toby.tu 2016-09-23
     */
    public function encodeOpenId($openid='')
    {
        $key = 'dalr_openid';
        return Data::encode($key,$openid);
    }
    /**
     * 加密openid
     * @author Toby.tu 2016-09-23
     */
    public function decodeOpenId($openid='')
    {
        $key = 'dalr_openid';
        return Data::decode($key,$openid);
    }

    /**
     * 订单编码生成规则
     * @author Toby.tu 2016-08-16
     */
    public function getOrderNo()
    {
        $time = time();
        $this->ci->load->library('PHPDate');
        $mtime = $this->ci->phpdate->mtime();//毫秒时间戳
        $No = substr(uniqid(rand()),-6).rand(1000,9999).date('H',$time).date('md').substr($mtime,-4).date('is');
        return $No;
    }

    /**
     *@param安全过滤
     */
    public static function safety($value='')
    {
        if(empty($value)) return '';
        //$value = htmlspecialchars($value);
        $value = strip_tags($value);
        //'select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile
        $filter = array(
            'insert'=>'','update'=>'','select'=>'','delete'=>'',
            'drop'=>'','create'=>'','truncate'=>'',"'"=>'','"'=>'',
            '%'=>'\%',"\\"=>'','/'=>'','and'=>'','or'=>'','union'=>'',
            'into'=>'','load_file'=>'','outfile'=>'',';'=>'','<script>'=>'');
        $value = strtr($value,$filter);
        return trim($value);
    }
    /*
     * @param对称加密
     * */
    public function parma_encode($string = '', $skey = 'cxphp')
    {
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key < $strCount && $strArr[$key].=$value;
        return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
    }
    /*
    * @param对称解密
    * */
    public function parma_decode($string = '', $skey = 'cxphp')
    {
        $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
        return base64_decode(join('', $strArr));
    }
    /*
     * @param计算年龄
     * 精确计算年龄 精确到天
     * */
    public function verify_birthday($birthday)
    {
        $age = strtotime($birthday);
        if($age === false){
            return false;
        }
        list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));
        $now = strtotime("now");
        list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
        $age = $y2 - $y1;
        if((int)($m2.$d2) < (int)($m1.$d1))
            $age -= 1;
        if($age < 18)
        {
            return false;
        }else
        {
            return true;
        }
    }
    /*
     * @param读取excel
     * */
    public function get_excel_content($file_path){
        $CI = &get_instance();
        $CI->load->library('PHPExcel');
        $CI->load->library('PHPExcel/IOFactory');
        $CI->load->library('PHPExcel/Reader/Excel2007.php');
        $Excel2007 = new Excel2007();
        $objPHPExcel = $Excel2007->load($file_path);
        $sheet = $objPHPExcel->getSheet(0); // 读取第一個工作表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumm = $sheet->getHighestColumn(); // 取得总列数
        /** 循环读取每个单元格的数据 */
        $rooms_info = array();
        $i = 0;
        for ($row = 2; $row <= $highestRow; $row++){//行数是以第1行开始
            for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
                if($column == 'F') //M列和O列是时间
                    $rooms_info[$i][] = gmdate("Y-m-d H:i:s", PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell("$column$row")->getValue()));
                else
                $rooms_info[$i][] = $sheet->getCell($column.$row)->getCalculatedValue();
            }
            $i++;
        }
        return $rooms_info;
    }

    /**
     *@param异步调用 远程请求函数
     */
    public function sock($url)
    {
        $host    = parse_url($url,PHP_URL_HOST);
        $port    = parse_url($url,PHP_URL_PORT);
        $port    = $port ? $port : 80;
        $scheme  = parse_url($url,PHP_URL_SCHEME);
        $path    = parse_url($url,PHP_URL_PATH);
        $query   = parse_url($url,PHP_URL_QUERY);
        if($query) $path .= '?'.$query;
        if($scheme == 'https') {
            $host = 'ssl://'.$host;
        }
        $fp = fsockopen($host,$port,$error_code,$error_msg,1);
        if(!$fp) {
            return array('error_code' => $error_code,'error_msg' => $error_msg);
        }
        else {
            // stream_set_blocking($fp,true);//开启了手册上说的非阻塞模式
            // stream_set_timeout($fp,1);//设置超时
            $header = "GET $path HTTP/1.1\r\n";
            $header.="Host: $host\r\n";
            $header.="Connection: close\r\n\r\n";//长连接关闭
            // fwrite($fp, $header);
            fputs($fp,$header);
            usleep(1000); // 这一句也是关键，如果没有这延时，可能在nginx服务器上就无法执行成功
            fclose($fp);
            return array('error_code' => 0);
        }
    }


    /**
     * 导出csv文件
     * $file_name  导出路径，含文件名
     * $header     头部
     * $data       内容
     * $type       1导出到服务器  2输出到浏览器
     */
    public function makeCsv($file_name,$header=array(),$data=array(),$type=1){

        // 处理头部标题
        $header = implode(',', $header) . PHP_EOL;
        // 处理内容
        $content = '';
        foreach ($data as $k => $v) {
            $content .= implode(',', $v) . PHP_EOL;
        }
        // 拼接
        $csv = $header.$content;
        $csv_data = mb_convert_encoding($csv, "cp936", "UTF-8");

        if($type == 1){//保存到服务器
            // 打开文件资源，不存在则创建
            $fp = fopen($file_name,'w');
            // 写入并关闭资源
            fwrite($fp, $csv_data);
            fclose($fp);
        }else{//输出到浏览器

            $file_name = empty($file_name) ? date('Y-m-d-H-i-s', time()) : $file_name;
            if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) { // 解决IE浏览器输出中文名乱码的bug
                $file_name = urlencode($file_name);
                $file_name = str_replace('+', '%20', $file_name);
            }
            ob_start();
            header("Content-type:text/csv;");
            header("Content-Disposition:attachment;filename=" . $file_name);
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Pragma:public');
            echo $csv_data;
            ob_end_flush();
        }

    }

    /**
     * @param下载远程图片到本地服务器
     */
    public function getImage($url,$save_dir='',$filename='',$type=0)
    {
        if(trim($url)==''){
            return array('file_name'=>'','save_path'=>'','error'=>1);
        }
        if(trim($save_dir)==''){
            $save_dir='./';
        }
        if(trim($filename)==''){//保存文件名
            $ext=strrchr($url,'.');
            if($ext!='.gif'&&$ext!='.jpg'){
                return array('file_name'=>'','save_path'=>'','error'=>3);
            }
            $filename=time().$ext;
        }
        if(0!==strrpos($save_dir,'/')){
            $save_dir.='/';
        }
        //创建保存目录
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
            return array('file_name'=>'','save_path'=>'','error'=>5);
        }
        //获取远程文件所采用的方法
        if($type){
            $ch=curl_init();
            $timeout=5;
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            $img=curl_exec($ch);
            curl_close($ch);
        }else{
            ob_start();
            readfile($url);
            $img=ob_get_contents();
            ob_end_clean();
        }
        //$size=strlen($img);
        //文件大小phf
        $fp2=@fopen($save_dir.$filename,'a');
        fwrite($fp2,$img);
        fclose($fp2);
        unset($img,$url);
        return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
    }
    /*
     * @param过滤字符串
     * */
    public function DeleteHtml($str)
    {
        $str = trim($str); //清除字符串两边的空格
        $str =  preg_replace('/<br\\s*?\/??>/i', '', $str);
        $str = preg_replace("/\t/","",$str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $str = preg_replace("/\r\n/","",$str);
        $str = preg_replace("/\r/","",$str);
        $str = preg_replace("/\n/","",$str);
        $str = preg_replace("/ /","",$str);
        $str = preg_replace("/  /","",$str);  //匹配html中的空格
        $str = preg_replace("/,/","",$str);  //匹配html中的空格
        return trim($str); //返回字符串
    }
    /**
     * 获取访问IP
     * @author Toby.tu 2017-07-28
     */
    public function get_client_ip($type = 0)
    {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实IP
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
    /**
     * 获取毫秒时间戳，默认6位
     */
    public function getMicrotime($length = 6)
    {
        if($length<1 || $length>8){
            $length = 6; //强制获取6位
        }
        $microtime = microtime();
        $time = explode(" ",$microtime);
        $micro = substr($time[0],2,$length);
        return $micro;
    }
    /**
     * 将字符串转换为指定格式
     * @author Tujt 2014-02-10
     */
    public  function setDataToCode($string, $code = 'UTF-8')
    {
        $arr = array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG-5');
        $encode = mb_detect_encoding($string, $arr);
        if (strtolower($encode) != strtolower($code)) {
            $string = iconv($encode, $code, $string);
        }
        return $string;
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
    /**
     * 将xml格式转换成数组
     * @param unknown $xml
     * @return mixed
     */
    public function xmlToArray($xml)
    {
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
    /**
     *@param数组转换为xml
     */
    public function arrayToXml($array=array())
    {
        if(empty($array)) return '';
        if(is_object($array)){
            $array = get_object_vars($array);
        }
        $xml = '<xml>';
        foreach($array as $key => $value){
            $_tag = $key;
            $_id = null;
            if(is_numeric($key)){
                $_tag = 'item';
                $_id = ' id="' . $key . '"';
            }
            $xml .= "<{$_tag}{$_id}>";
            $xml .= (is_array($value) || is_object($value)) ? $this->arrayToXml($value) : htmlentities($value);
            $xml .= "</{$_tag}>";
        }
        $xml.="</xml>";
        return $xml;
    }
    /**
     * xml转成JSON
     * @param unknown $source
     * @return SimpleXMLElement
     */
    public function xml_to_json($source) {
        if(is_file($source)){ //传的是文件，还是xml的string的判断
            $xml_array=simplexml_load_file($source);
        }else{
            $xml_array=simplexml_load_string($source);
        }
        //$json = json_encode($xml_array); //php5，以及以上，如果是更早版本，请查看JSON.php
        return $xml_array;
    }
    /*
     * @param读取csv文件
     * */
    public function input_csv($handle)
    {
        $out = array ();
        $n = 0;
        while ($data = fgetcsv($handle, 10000))
        {
            $num = count($data);
            for ($i = 0; $i < $num; $i++)
            {
//                $out[$n][$i] = $data[$i];
                $out[$n][$i] = iconv('gb2312', 'utf-8', $data[$i]); //中文转码
            }
            $n++;
        }
        return $out;
    }
    /**
     * @param获取token
     * @param redis缓存     * 可存入缓存中
    //        $content = $this->encodeOpenId($content);
    //        $is_ok = $this->redis->set($key,$content);
    //        if($is_ok){
    //            return $content;
    //        }
     */
    public function getToken($key = '')
    {

        $param = array(
            'time' => time(),
            'key'  => $key
        );
        $content = serialize($param);
        return $this->encode($content,$key);
    }
    /*
     * @param校验token
     * */
    public function checkToken($token='',$key = '')
    {
//        if($token == 'swellfun') {
//            return 1;
//        }
        $content = $this->decode($token,$key);
        $param = unserialize($content);
        if($param['key']!=$key){
            return false;  //验证key
        }
        //60秒
        if(time()-$param['time']>60){
            return false;  //验证时间
        }
        return true;
    }

    /**
     * 如果文件夹不存在，则递归创建文件夹
     * @param String $path 目录
     */
    public function createdir($path)
    {
        if (!file_exists($path))
        {
            $this->createdir(dirname($path));//递归创建文件夹
            mkdir($path, 0777);
        }
    }
    /**
     * 如果文件不存在，则创建文件
     * x String $path 目录
     */
    public function createfile($route)
    {
        if (!file_exists($route))
        {
            touch($route);
        }
    }

    /**
     * 还原html实体标签
     */
    public function decodeContent($content)
    {
        // return htmlspecialchars_decode($content);
        $content = htmlspecialchars_decode($content);
        //过滤<span>及其中间的内容
        $content = preg_replace("/<(span.*?)>/si","",$content);
        $content = preg_replace("/<(\/span.*?)>/si","",$content);
        return $content;
    }
     /**
     * ajax返回值
     * @author  lei.wang@etocrm.com
     */
    public function ajaxReturn($data,$code=1)
    {
        header('Access-Control-Allow-Origin: *');//跨域
        header("Content-Type: application/json; charset=utf-8");
        return response()->json(array('code'=>$code,'data'=>$data));
    }
    /*
     * @param httpPost请求
     * */
    public function httpPost($url,$data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt ( $curl, CURLOPT_POST, 1 );

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data );
        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
    /**
     * GET 请求
     * @param string $url
     */
    public function http_get($url)
    {
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
    const EARTH_RADIUS = 6378.137;

    static function  rad($d)
    {
        return $d * pi()/ 180.0;
    }

    static function GetDistance($lat1, $lng1, $lat2, $lng2)
    {
        $radLat1 = self::rad($lat1);
        $radLat2 = self::rad($lat2);

        $a = $radLat1 - $radLat2;

        $b = self::rad($lng1) - self::rad($lng2);
        $s = 2 * asin(sqrt(pow(sin($a/2),2) +
                cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));


        $s = $s * self::EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $s;
    }
    /*
     * @param传入秒数转换为天、时、分、秒
     * */
    public function time2string($second)
    {
        $day    = floor($second/(3600*24));
        $second = $second%(3600*24);//除去整天之后剩余的时间
        $hour   = floor($second/3600);
        $second = $second%3600;//除去整小时之后剩余的时间
        $minute = floor($second/60);
        $second = $second%60;//除去整分钟之后剩余的时间
        //返回字符串
        return $day.'天'.$hour.'小时'.$minute.'分'.$second.'秒';
    }
    /*
     * @param获取图片宽高
     * */
    public function getImageinfo($url)
    {
        $result = array(
            'width' => '',
            'height'=> '',
            'size'  => '',
        );
        $imageInfo        = getimagesize($url);
        $result['width']  = $imageInfo[0];
        $result['height'] = $imageInfo[1];

        $headerInfo       = get_headers($url,true);
        $result['size']   = $headerInfo['Content-Length'];

        return $result;
    }
    /*
     * @param卡券有效期
    */
    public function getCardValidTime($cardId = '')
    {
        $start_time = '';//现在暂时没用到这个字段，之后djl_card_receipt_log数据表和代码中都要完善开始时间的字段
        $valid_time = '';
        $datetime   = date('Y-m-d H:i:s');
        $row        = $this->ci->db->select('type,fixed_begin_term,fixed_term,begin_timestamp,end_timestamp')->get_where('card',array('cardID'=>$cardId))->row_array();
        $type       = $row['type'];
        $fixed_begin_term = $row['fixed_begin_term'];
        $fixed_term       = $row['fixed_term']-1;
        //如果是固定时间段
        if ($type ==  'DATE_TYPE_FIX_TIME_RANGE')
        {
            $start_time = $row['begin_timestamp'];
            $valid_time = $row['end_timestamp'];
        }else if ($type ==  'DATE_TYPE_FIX_TERM')//如果是指定时间段
        {
            $start_time = date("Y-m-d 00:00:00",strtotime("+".$fixed_begin_term." day",strtotime($datetime)));//领取后多少天生效
            $valid_time = date("Y-m-d 23:59:59",strtotime("+".$fixed_term." day",strtotime($start_time)));//领取后多少天有效
        }
        $list['start_time'] = $start_time;
        $list['valid_time'] = $valid_time;
        return $list;
    }




    /**
     * @desc 使用curl抓取远程图片至本地
     * @param $url
     * @param string $path
     */
    static public function downloadImage($url, $path='./public/img/')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $file = curl_exec($ch);
        if($file === false)
        {
            echo 'Curl error: ' . curl_error($ch);die();
        }

        curl_close($ch);
        return self::saveAsImage($url, $file, $path);
    }

    static private function saveAsImage($url, $file, $path)
    {
        $filename = pathinfo($url, PATHINFO_BASENAME);
        $resource = fopen($path . $filename, 'w+');
        if(!(bool)fwrite($resource, $file)){
            echo 'mission';
        }
        fclose($resource);
        return $path.$filename;
    }
    /**
     * @desc php获取中文字符拼音首字母
     * @param $str
     * @return null|string
     */
    static function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        @$s1 = iconv('UTF-8', 'gb2312', $str);
        @$s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return 'other';
    }
    /*
    * @param金额转换
    * */
    public function moneyHandle($money)
    {
        if($money){
            $money = sprintf("%.2f",substr(sprintf("%.4f", $money), 0, -2));
        }else{
            $money = '0.00';
        }
        return $money;
    }


    /**
     * 获取指定日期段内每一天的日期
     * @param Date $startdate 开始日期
     * @param Date $enddate  结束日期
     * @return Array
     */
    public function getDateFromRange($startdate, $enddate)
    {
        $stimestamp = strtotime($startdate);
        $etimestamp = strtotime($enddate);
        // 计算日期段内有多少天
        $days = ($etimestamp-$stimestamp)/86400+1;
        // 保存每天日期
        $date = array();
        for($i=0; $i<$days; $i++){
            $date[] = date('Y-m-d', $stimestamp+(86400*$i));
        }
        return $date;
    }
    /*
     * @param是否为图片
     * */
    public function isImage($filename)
    {
        $arr_file_type = ['image/gif','image/png','image/jpeg','image/jpg'];
        //如果上传的类型 不在配置的类型数组里面
        if(!in_array($_FILES[$filename]['type'],$arr_file_type))
        {
            return false;
        }else{
            return true;
        }
    }
    /*
     * @param随机算出中奖概率
    * */
    public function get_rand($proArr)
    {

        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
    /*
  * @param验证手机号手机号
  * */
    public function checkphone($phonenumber){
//        $regx = "#^13[\d]{9}$|^14[5,7]{1}[\d]{8}$|^15[^4]{1}[\d]{8}$|^17[0,6,7,8]{1}[\d]{8}$|^18[\d]{9}$#";
        $regx = "/^1[34578]{1}\d{9}$/";
        if(!preg_match($regx,$phonenumber)){
            return false;
        }else{
            return true;
        }
    }
    /**
     * 按照二维数组中某个指定的某个字段进行排序
     * @param $array 需要被排序的数组
     * @param $flag  排序的标志 1，SORT_DESC 降序 2，SORT_ASC 升序
     * @param int $range
     * @return array
     */
    public function assortArray2($array,$flag,$keyword)
    {
        $sort = array(
            'direction' => $flag, //排序顺序标志 1 ，SORT_DESC 降序；2 ，SORT_ASC 升序
            'field' => $keyword,       //排序字段
        );
        $arrSort = array();
        foreach ($array AS $uniqid => $row) {
            foreach ($row AS $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if ($sort['direction']) {
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $array);
        }
        return $array;
    }
    /**
     * 统一生成订单号
     */
    public function setOrderNumber()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        //订单号为：微秒时间戳+随机数字6位
        $order_num = (string)$msectime.str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        return $order_num;
    }

    public static function get_url()
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }
}
?>
