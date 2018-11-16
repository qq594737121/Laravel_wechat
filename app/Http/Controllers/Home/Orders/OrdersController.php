<?php
namespace App\Http\Controllers\Home\Orders;

use App\Http\Controllers\ApiController;
use DB;
use Illuminate\Http\Request;
class OrdersController extends ApiController
{
    /**
     * 订单状态
     */
    public function status(Request $request)
    {

        return $this->response(200,array("data"=>array("orderno"=>'b11')));
        $outTradeNo = $request->input('out_trade_no');
        $status = WechatOrder::where('out_trade_no', $outTradeNo)->value('status');
        $map = [
            '订单不存在',
            '待支付',
            '支付成功',
            '支付失败',
        ];
        $res['type'] = $status ?? 0;
        $res['msg'] = $map[$res['type']] ?? '未知';

        return $this->success($res);
    }

    /**
     * 统一下单
     */
    public function unified(Request $request)
    {
        $openid    = $request->input("openid");
        $app        = app('wechat.payment');
        $outTradeNo = date('YmdHis').mt_rand(100, 999).uniqid();
        dd($outTradeNo);
        $result = $app->order->unify(
            [
            'body' => '黒鲸-1元拼团',
            'out_trade_no' => $outTradeNo,//商户系统内部的订单号
            'openid' => $openid,
            'total_fee' => 100,
            // 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url' => secure_url('home/pay/notify'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI',
        ]);
        dd($result);
        WechatOrder::create([
            'openid' => $openid,
            'out_trade_no' => $outTradeNo,
            'total_fee' => 100,
            'prepay_id' => $result['prepay_id'] ?? '',
            'result' => $result,
        ]);

        return $this->success($result);
    }

    /*
     * @param发红包
     * */
    public function redpack()
    {
        $redpackData = [
            'mch_billno'   => 'xy123456',
            'send_name'    => '测试红包',
            're_openid'    => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
            'total_num'    => 1,  //固定为1，可不传
            'total_amount' => 100,  //单位为分，不小于100
            'wishing'      => '祝福语',
            'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
            'act_name'     => '测试活动',
            'remark'       => '测试备注',
            // ...
        ];
        $app        = app('wechat.payment');
        $result     = $app->redpack->sendNormal($redpackData);

        dd($result);
    }
    /**
     * 支付签名
     * @param Request $request
     * @return array
     */
    private function getPayConfig(Request $request)
    {
        $totalFee = 100;
        $userId = $request['miniUser']['id'];
        $openid = $request['miniUser']['openid'];
        $shopId = $request->input('shop_id');
        $app = app('wechat.payment');
        $outTradeNo = date('YmdHis').mt_rand(100, 999).uniqid();
        $result = $app->order->unify([
            'body' => '黑鲸-1元拼团',
            'out_trade_no' => $outTradeNo,
            'openid' => $openid,
            'total_fee' => $totalFee,
            'notify_url' => secure_url('api/pay/notify'),
            'trade_type' => 'JSAPI',
            'time_expire' => date('YmdHis', time() + 3 * 60),
        ]);
        WechatOrder::create([
            'user_id' => $userId,
            'shop_id' => $shopId,
            'out_trade_no' => $outTradeNo,
            'total_fee' => $totalFee,
            'prepay_id' => $result['prepay_id'] ?? '',
        ]);
        ApiRequestLog::create([
            'path' => 'order/unified',
            'params' => $result,
        ]);
        $payConfig = [];
        if (isset($result['prepay_id'])) {
            $payConfig = $app->jssdk->bridgeConfig($result['prepay_id'], false);
        }

        return $payConfig;
    }
    /*
     * @param退款接口
     * */
    public function refund()
    {
        $resp = app('wechat.payment')->refund->byTransactionId(
            $order['transaction_id'], $order['out_refund_no'],
            $order['total_fee'], $order['total_fee'], [
            'refund_desc' => '组团失败',
            'notify_url' => secure_url('api/pay/refund'),
        ]);
    }

    /**
     * @param订单详情接口
     */
    public function detail(Request $request)
    {
        $outTradeNo = $request->input('out_trade_no');
        $app = app('wechat.payment');
        $result = $app->order->queryByOutTradeNumber($outTradeNo);

        return $this->success($result);
    }

    /**
     * @param退款详情接口
     */
    public function refundDetail(Request $request)
    {
        $outRefundNo = $request->input('out_refund_no');
        $app = app('wechat.payment');
        $result = $app->refund->queryByOutRefundNumber($outRefundNo);

        return $this->success($result);
    }
}
