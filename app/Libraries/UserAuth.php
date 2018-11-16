<?php
namespace App\Libraries;
use App\Model\AdminUser;
class UserAuth
{
    protected $user;
    protected static $loginUser;


    public static function login($request)
    {
        if (empty(self::$loginUser)) {
            if (empty($request->session()->get('uid')))
                return false;

            $user = AdminUser::findOrFail($request->session()->get('uid'));
            self::$loginUser = $user;
        }
        return self::$loginUser;
    }

}
