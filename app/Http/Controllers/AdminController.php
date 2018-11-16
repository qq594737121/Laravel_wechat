<?php
namespace App\Http\Controllers;
use Validator;
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['CheckAdminAuth']);
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

}
