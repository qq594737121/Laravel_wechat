<?php
namespace App\Http\Controllers\Home\Orders;
use App\Http\Controllers\ApiController;
use DB;
use Illuminate\Http\Request;
class PayControllers extends ApiController
{
    /**
     * 支付回调
     * @return mixed
     */
    public function notify()
    {
        $app = app('wechat.payment');
        $response = $app->handlePaidNotify(function ($message, $fail) {
            ApiRequestLog::create([
                'path' => 'pay/notify',
                'params' => $message,
            ]);
            if (isset($message['out_trade_no'])) {
                $order = WechatOrder::where('out_trade_no', $message['out_trade_no'])->first();
                if (!$order || $order->paid_at) {
                    return true;
                }
                if (array_get($message, 'return_code') == 'SUCCESS') {
                    if (array_get($message, 'result_code') === 'SUCCESS') {
                        $order->transaction_id = $message['transaction_id'];
                        $order->paid_at = date('Y-m-d H:i:s');
                        $order->status = 2;
                        DB::transaction(function () use ($order) {
                            $group = Group::where([
                                'shop_id' => $order['shop_id'],
                                'status' => 1,
                            ])->where('num', '<', 20)->first();
                            if (empty($group)) {
                                $group = Group::create([
                                    'shop_id' => $order['shop_id'],
                                    'num' => 0,
                                    'status' => 1,
                                ]);
                            }
                            $group->increment('num');
                            UserGroup::create([
                                'user_id' => $order['user_id'],
                                'shop_id' => $order['shop_id'],
                                'group_id' => $group['id'],
                                'status' => 1,
                            ]);
                            $locked = UserShop::where([
                                'user_id' => $order['user_id'],
                                'shop_id' => $order['shop_id'],
                            ])->first();
                            if ($locked) {
                                $locked->delete();
                            } else {
                                ShopCard::where([
                                    'shop_id' => $order['shop_id'],
                                    'type' => 2,
                                ])->decrement('quantity');
                            }
                        });
                    } elseif (array_get($message, 'result_code') === 'FAIL') {
                        $order->status = 3;
                    }
                    $order->save();
                } else {
                    return $fail('code error');
                }
            }

            return true;
        });

        return $response;
    }

    /**
     * 退款回调
     * @return mixed
     */
    public function refund()
    {
        $app = app('wechat.payment');
        $response = $app->handleRefundedNotify(function ($message, $reqInfo, $fail) {
            ApiRequestLog::create([
                'path' => 'refund/notify',
                'params' => $reqInfo,
            ]);
            if (array_get($message, 'return_code') == 'SUCCESS') {
                if (isset($reqInfo['out_refund_no'])) {
                    WechatRefund::updateOrCreate([
                        'out_refund_no' => $reqInfo['out_refund_no'],
                    ], $reqInfo);
                }
            } else {
                return $fail('code error');
            }

            return true;
        });

        return $response;
    }

}
