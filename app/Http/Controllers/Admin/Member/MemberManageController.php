<?php
namespace App\Http\Controllers\Admin\Member;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Validator;

class MemberManageController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /*
      * @param会员首页
      * */
    public function index(Request $request)
    {
        $params = $request->all();
        return view('admin.membermanage.index');
    }
}
