<?php

namespace App\Libraries;

/**
 * 微信素材接口
 * Class Material.
 */
class WechatMaterial extends Http
{
    /**
     * Allow media type.
     *
     * @var array
     */
    protected $allowTypes = ['image', 'voice', 'video', 'thumb', 'news_image'];

    const API_GET = 'https://api.weixin.qq.com/cgi-bin/material/get_material';
    const API_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/material/add_material';
    const API_DELETE = 'https://api.weixin.qq.com/cgi-bin/material/del_material';
    const API_STATS = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount';
    const API_LISTS = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material';
    const API_NEWS_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/material/add_news';
    const API_NEWS_UPDATE = 'https://api.weixin.qq.com/cgi-bin/material/update_news';
    const API_NEWS_IMAGE_UPLOAD = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg';

    /**
     * Upload image.
     *
     * @param string $path
     *
     * @return array
     */
    public function uploadImage($path)
    {
        return $this->uploadMedia('image', $path);
    }

    /**
     * Upload voice.
     *
     * @param string $path
     *
     * @return array
     */
    public function uploadVoice($path)
    {
        return $this->uploadMedia('voice', $path);
    }

    /**
     * Upload thumb.
     *
     * @param string $path
     *
     * @return array
     */
    public function uploadThumb($path)
    {
        return $this->uploadMedia('thumb', $path);
    }

    /**
     * Upload video.
     *
     * @param string $path
     * @param string $title
     * @param string $description
     *
     * @return array
     */
    public function uploadVideo($path, $title, $description)
    {
        $params = [
            'description' => json_encode(
                [
                    'title' => $title,
                    'introduction' => $description,
                ], JSON_UNESCAPED_UNICODE),
        ];

        return $this->uploadMedia('video', $path, $params);
    }

    /**
     * Upload articles.
     *
     * @param array|Article $articles
     *
     * @return array
     */
    public function uploadArticle($articles)
    {
        if (!empty($articles['title']) || $articles instanceof Article) {
            $articles = [$articles];
        }

        $params = ['articles' => array_map(function ($article) {
            if ($article instanceof Article) {
                return $article->only([
                    'title', 'thumb_media_id', 'author', 'digest',
                    'show_cover_pic', 'content', 'content_source_url',
                ]);
            }

            return $article;
        }, $articles)];

        return $this->parseJSON('json', [self::API_NEWS_UPLOAD, $params]);
    }

    /**
     * Update article.
     *
     * @param string $mediaId
     * @param array $article
     * @param int $index
     *
     * @return array
     */
    public function updateArticle($mediaId, $article, $index = 0)
    {
        $params = [
            'media_id' => $mediaId,
            'index' => $index,
            'articles' => isset($article['title']) ? $article : (isset($article[$index]) ? $article[$index] : []),
        ];

        return $this->parseJSON('json', [self::API_NEWS_UPDATE, $params]);
    }

    /**
     * Upload image for article.
     *
     * @param string $path
     *
     * @return array
     */
    public function uploadArticleImage($path)
    {
        return $this->uploadMedia('news_image', $path);
    }

    /**
     * Fetch material.
     *
     * @param string $mediaId
     *
     * @return mixed
     */
    public function get($mediaId)
    {
        $response = $this->getHttp()->json(self::API_GET, ['media_id' => $mediaId]);

        foreach ($response->getHeader('Content-Type') as $mime) {
            if (preg_match('/(image|video|audio)/i', $mime)) {
                return $response->getBody();
            }
        }

        $json = $this->parseJSON($response);

        if (!$json) {
            return $response->getBody();
        }

        return $json;
    }

    /**
     * Delete material by media ID.
     *
     * @param string $mediaId
     *
     * @return array
     */
    public function delete($mediaId)
    {
        return $this->parseJSON('json', [self::API_DELETE, ['media_id' => $mediaId]]);
    }

    /**
     * List materials.
     *
     * example:
     *
     * {
     *   "total_count": TOTAL_COUNT,
     *   "item_count": ITEM_COUNT,
     *   "item": [{
     *             "media_id": MEDIA_ID,
     *             "name": NAME,
     *             "update_time": UPDATE_TIME
     *         },
     *         // more...
     *   ]
     * }
     *
     * @param string $type
     * @param int $offset
     * @param int $count
     *
     * @return array
     */
    public function lists($type, $offset = 0, $count = 20)
    {
        $params = [
            'type' => $type,
            'offset' => intval($offset),
            'count' => min(20, $count),
        ];

        return $this->parseJSON('json', [self::API_LISTS, $params]);
    }

    /**
     * Get stats of materials.
     *
     * @return array
     */
    public function stats()
    {
        return $this->parseJSON('get', [self::API_STATS]);
    }

    /**
     * Upload material.
     *
     * @param string $type
     * @param string $path
     * @param array $form
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    protected function uploadMedia($type, $path, array $form = [])
    {
        if (!file_exists($path) || !is_readable($path)) {
            return false;
        }

        $form['type'] = $type;

        return $this->parseJSON('upload', [$this->getAPIByType($type), ['media' => $path], $form]);
    }

    /**
     * Get API by type.
     *
     * @param string $type
     *
     * @return string
     */
    public function getAPIByType($type)
    {
        switch ($type) {
            case 'news_image':
                $api = self::API_NEWS_IMAGE_UPLOAD;

                break;
            default:
                $api = self::API_UPLOAD;
        }

        return $api;
    }
}
