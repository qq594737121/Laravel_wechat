<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class WechatOrder extends Model
{
    protected $table = 'wechat_order';

    protected $fillable = [
        'user_id',
        'shop_id',
        'appid',
        'out_trade_no',
        'total_fee',
        'prepay_id',
        'transaction_id',
        'paid_at',
        'out_refund_no',
        'status',
    ];

    protected $casts = [
        'result' => 'array',
    ];

    public $timestamps = false;

}
