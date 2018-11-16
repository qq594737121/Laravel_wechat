<?php

namespace App\Libraries;

/**
 * 工具类
 * Class Tools
 * @package App\Libraries
 */
class Tools
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

    /**
     * 参团成功消息
     * @param $openid
     * @param $formId
     * @param $shopName
     * @return bool|mixed
     */
    public static function getSuccessTemplate($openid, $formId, $shopName)
    {
        return [
            'touser' => $openid,
            'template_id' => 'ssCn8CEzWXo8cBGmm7r17QrzPmTBYGse6Ok4AT5Cfy4',
            'page' => 'pages/coupontuan/main',
            'form_id' => $formId,
            'data' => [
                'keyword1' => [
                    'value' => 'xxx',
                ],
                'keyword2' => [
                    'value' => $shopName,
                ],
            ],
            // 'emphasis_keyword' => 'keyword1.DATA',
        ];
    }

    /**
     * 参团失败消息
     * @param $openid
     * @param $formId
     * @param $shopName
     * @return bool|mixed
     */
    public static function getFailureTemplate($openid, $formId, $shopName)
    {
        return [
            'touser' => $openid,
            'template_id' => 'uuQKVBZjWemFbDRC9w4Or89fw4lMRzfq9Sb5udNmHmk',
            'page' => 'pages/coupontuan/main',
            'form_id' => $formId,
            'data' => [
                'keyword1' => [
                    'value' => 'xxx',
                ],
                'keyword2' => [
                    'value' => $shopName,
                ],
            ],
            // 'emphasis_keyword' => 'keyword1.DATA',
        ];
    }

    /**
     * 参团提醒消息
     * @param $openid
     * @param $formId
     * @param $shopName
     * @return bool|mixed
     */
    public static function getNotifyTemplate($openid, $formId, $shopName)
    {
        return [
            'touser' => $openid,
            'template_id' => 'w9y2IM3zBEy9JnPsXogTUQ4_r009PMM2A2ud89O0ePY',
            'page' => 'pages/coupontuan/main',
            'form_id' => $formId,
            'data' => [
                'keyword1' => [
                    'value' => 'xx',
                ],
                'keyword2' => [
                    'value' => $shopName,
                ],
                'keyword3' => [
                    'value' => '2018-11-03到2018-11-11',
                ],
                'keyword4' => [
                    'value' => '在有效期内到店后，还可领取1张20元无门槛优惠券哦。',
                ],
            ],
        ];
    }

    /**
     * 领取20元优惠券成功
     * @param $openid
     * @param $formId
     * @return array
     */
    public static function getSuccess20Template($openid, $formId)
    {
        return [
            'touser' => $openid,
            'template_id' => 'w9y2IM3zBEy9JnPsXogTUav419HNe4xm6UM5MmTlGVA',
            'page' => 'pages/coupon/main',
            'form_id' => $formId,
            'data' => [
                'keyword1' => [
                    'value' => 'xxx',
                ],
                'keyword2' => [
                    'value' => '2018-11-03到2018-11-11',
                ],
            ],
        ];
    }

}
