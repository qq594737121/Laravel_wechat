<?php

namespace App\Libraries;

/**
 */
class RequestApi extends Http
{
    const API_PRODUCT_DETAIL = 'https://ikea-vsb.woaap.com/api/cn/zh/products/';
    const API_IS_MEMBER         = 'http://ikea-wechat.woaap.com/mrb/ismember';

    public function getProduct($productNo)
    {
        $resp = $this->parseJSON('get', [self::API_PRODUCT_DETAIL . $productNo]);
        if (isset($resp['errcode']) && $resp['errcode'] == 'ER2000') {
            return $resp['data'] ?? null;
        }

        return false;
    }

    public function isMember($unionid)
    {
        $resp = $this->parseJSON('post', [self::API_IS_MEMBER, compact('unionid')]);
        if (isset($resp['errcode']) && $resp['errcode'] == '0') {
            return $resp['data']['is_member'] ?? null;
        }

        return false;
    }

}
