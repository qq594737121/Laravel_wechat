<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class WechatMiniUser extends Model
{
    protected $table = 'wechat_mini_user';

    protected $fillable = [
        'openid',
        'unionid',
        'nickname',
        'language',
        'gender',
        'city',
        'province',
        'country',
        'avatarurl',
        'session_key',
        'session_id',
    ];

    protected $casts = [
        'nickname' => 'array',
    ];

    public $timestamps = false;

}
