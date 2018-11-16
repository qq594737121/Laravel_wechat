<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WechatApp extends Model
{
   protected $primaryKey = 'id';

    protected $table = 'wechat_app';

    protected $fillable = [
        'appid',
        'appkey',
        'ackey',
        'access_token',
        'js_ticket',
        'api_ticket',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;
}
