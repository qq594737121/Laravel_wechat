<?php
namespace App\Http\Controllers\Admin\Index;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Validator;

class IndexController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /*
      * @param后台首页
      * */
    public function index(Request $request)
    {
        return view('admin.index.index');
    }
}
