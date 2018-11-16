<?php

namespace App\Libraries;

/**
 * 微信门店接口
 * Class POI.
 */
class WechatPoi extends Http
{
    const API_CREATE = 'https://api.weixin.qq.com/cgi-bin/poi/addpoi';
    const API_GET = 'https://api.weixin.qq.com/cgi-bin/poi/getpoi';
    const API_LIST = 'https://api.weixin.qq.com/cgi-bin/poi/getpoilist';
    const API_UPDATE = 'https://api.weixin.qq.com/cgi-bin/poi/updatepoi';
    const API_DELETE = 'https://api.weixin.qq.com/cgi-bin/poi/delpoi';
    const API_GET_CATEGORIES = 'https://api.weixin.qq.com/cgi-bin/poi/getwxcategory';

    /**
     * Get POI supported categories.
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function getCategories()
    {
        return $this->parseJSON('get', [self::API_GET_CATEGORIES]);
    }

    /**
     * Get POI by ID.
     *
     * @param int $poiId
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function get($poiId)
    {
        return $this->parseJSON('json', [self::API_GET, ['poi_id' => $poiId]]);
    }

    /**
     * List POI.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function lists($offset = 0, $limit = 10)
    {
        $params = [
            'begin' => $offset,
            'limit' => $limit,
        ];

        return $this->parseJSON('json', [self::API_LIST, $params]);
    }

    /**
     * Create a POI.
     *
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data)
    {
        $params = [
            'business' => ['base_info' => $data],
        ];

        return $this->parseJSON('json', [self::API_CREATE, $params]);
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function createAndGetId(array $data)
    {
        return $this->create($data)['poi_id'];
    }

    /**
     * Update a POI.
     *
     * @param int $poiId
     * @param array $data
     *
     * @return bool
     */
    public function update($poiId, array $data)
    {
        $data = array_merge($data, ['poi_id' => $poiId]);

        $params = [
            'business' => ['base_info' => $data],
        ];

        return $this->parseJSON('json', [self::API_UPDATE, $params]);
    }

    /**
     * Delete a POI.
     *
     * @param int $poiId
     *
     * @return bool
     */
    public function delete($poiId)
    {
        $params = ['poi_id' => $poiId];

        return $this->parseJSON('json', [self::API_DELETE, $params]);
    }

}
