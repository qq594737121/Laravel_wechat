<?php
namespace App\Http\Controllers\Admin\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Libraries\Captcha_code;
use App\Model\AdminLog;
use App\Model\AdminUser;
use App\Libraries\Common;
use App\Libraries\PermissionLogic;

class LoginController extends Controller
{
    protected $permis;
    protected $common;

    public function __construct()
    {
        $this->common = Common::getInstance(); 

        $this->permis = PermissionLogic::getInstance();
       
    }
  
    public function index(Request $request)
    {
        $input  = $request->all();
        $filter = array('user_name'=>'','error_code'=>'');
        return view('admin.index.login',compact("filter"));
    }

    public function signin(Request $request)
    {
        try {
            $input = $request->all();
            $input['name']      = trim($input['name']);
            $input['password']  = trim($input['password']);
            $input['verify_text'] = trim($input['verify_text']);
            if(empty($input)|| empty($input['name'])
                || empty($input['password'])){
                return $this->common->ajaxReturn("参数不完整",1);
            }
            $input['password'] = base64_decode($input['password']);

            //是不是错误次数超过了3次
            $admin_log_count = AdminLog::where("name",$input['name'])
                    ->where("logintime",">=",date('Y-m-d').' 00:00:00')
                    ->where("logintime","<=",date('Y-m-d').' 23:59:59')
                    ->count();

            if($admin_log_count > 3)
            {
                $this->common->ajaxReturn("今天登陆错误次数过多，请明天再来吧。",1);
            }
            if($request->session()->get('verify_code') != $input['verify_text'])
            {
               return $this->common->ajaxReturn("验证码错误",1);
            }
            $user = AdminUser::where("name",$input['name'])->first();

            if(empty($user)){
               return  $this->common->ajaxReturn("账号不存在",1);
            }
            $pwd = $this->common->pwd($input['password']);
            if($pwd != $user->password){
                $arr['name']      = $input['name'];
                $arr['logintime'] = date('Y-m-d H:i:s');
                AdminLog::insert($arr);
                return $this->common->ajaxReturn("用户名或密码错误。",1);
            }
            if(0 == $user->status){
                 return $this->common->ajaxReturn("用户被禁用，不能登录。",1);
            }
            $request->session()->put('uid',$user->id);
            $this->permis->setLoginUserInfo($request,$user);
            return $this->common->ajaxReturn("success",200);

        } catch (Exception $e) {
            $filter = array('user_name'=>$input['name']);
            $filter['error_code'] = $e->getMessage();
            return view('admin.index.login',compact("filter"));
        }
    }

    public function verify_image(Request $request)
    {
        $conf['name'] = 'verify_code'; //作为配置参数
        (new captcha_code($conf))->show($request);
      
    }
    /**
     * 注销
     * @author lei.wang
     */
    public function logout(Request $request)
    {
       
        $this->permis->destoryLoginUserInfo($request);//注销
        return redirect('admin/login/index');
    }   
}
