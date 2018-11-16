<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Config,DB;

class ApiController extends Controller
{
    /**
     * json返回
     * @param int $error_code
     * @param string $error_msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($code = 200,$data = [])
    {
        return response()->json(compact('code','data'));
    }






}
