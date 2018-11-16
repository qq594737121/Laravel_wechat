<?php
namespace App\Services\Gift;
use DB;
use App\Services\BaseService;
use Illuminate\Support\Facades\Cache;
/*
 * @param lei.wang
 * */
class GiftmemberService extends BaseService
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




}
