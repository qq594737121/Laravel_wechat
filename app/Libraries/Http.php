<?php

namespace App\Libraries;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Http.
 */
class Http
{

    /**
     * Used to identify handler defined by client code
     * Maybe useful in the future.
     */
    const USER_DEFINED_HANDLER = 'userDefined';

    /**
     * Http client.
     *
     * @var HttpClient
     */
    protected $client;

    /**
     * The middlewares.
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var array
     */
    protected static $globals = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    /**
     * Guzzle client default settings.
     *
     * @var array
     */
    protected static $defaults = [];



    /**
     * Set guzzle default settings.
     *
     * @param array $defaults
     */
    public static function setDefaultOptions($defaults = [])
    {
        self::$defaults = array_merge(self::$globals, $defaults);
    }

    /**
     * Return current guzzle default settings.
     *
     * @return array
     */
    public static function getDefaultOptions()
    {
        return self::$defaults;
    }



    /**
     * GET request.
     *
     * @param string $url
     * @param array $options
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     */
    public function get($url, array $options = [])
    {
        return $this->request($url, 'GET', ['query' => $options]);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array|string $options
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     */
    public function post($url, $options = [])
    {
        $key = is_array($options) ? 'form_params' : 'body';

        return $this->request($url, 'POST', [$key => $options]);
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param string|array $options
     * @param array $queries
     * @param int $encodeOption
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     */
    public function json($url, $options = '{}', $queries = [], $encodeOption = JSON_UNESCAPED_UNICODE)
    {
        is_array($options) && $options = json_encode($options, $encodeOption);

        return $this->request($url, 'POST', ['query' => $queries, 'body' => $options, 'headers' => ['content-type' => 'application/json']]);
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array $files
     * @param array $form
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     */
    public function upload($url, array $files = [], array $form = [], array $queries = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', ['query' => $queries, 'multipart' => $multipart]);
    }
    /*
    * @param guzzle上传文件
    * */
//    protected function upload($url, $path, array $queries = [], $isRaw = false)
//    {
//        if (!file_exists($path) || !is_readable($path)) {
//            return false;
//        }
//        $multipart = [];
//        $multipart[] = [
//            'name' => 'buffer',
//            'contents' => fopen($path, 'r'),
//        ];
//        $res = $this->httpRequest('POST', $url, [
//            'query' => $queries,
//            'multipart' => $multipart,
//        ], $isRaw);
//
//        return $res;
//    }

    /**
     * Set GuzzleHttp\Client.
     *
     * @param \GuzzleHttp\Client $client
     *
     * @return Http
     */
    public function setClient(HttpClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Return GuzzleHttp\Client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        if (!($this->client instanceof HttpClient)) {
            $this->client = new HttpClient();
        }

        return $this->client;
    }

    /**
     * Add a middleware.
     *
     * @param callable $middleware
     *
     * @return $this
     */
    public function addMiddleware(callable $middleware)
    {
        array_push($this->middlewares, $middleware);

        return $this;
    }

    /**
     * Return all middlewares.
     *
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Make a request.
     *
     * @param string $url
     * @param string $method
     * @param array $options
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     verify 设置成 true 启用SSL证书验证，默认使用操作系统提供的CA包。 
      设置成 false 禁用证书验证(这是不安全的！)
     */
    public function request($url, $method = 'GET', $options = [])
    {
        $options['verify']  = false;
        $method             = strtoupper($method);
        $options            = array_merge(self::$defaults, $options);
        $options['handler'] = $this->getHandler();
        $response = $this->getClient()->request($method, $url, $options);
        return $response;
    }

    /**
     * Parse JSON from response and check error.
     *
     * @param string $method
     * @param array $args
     *
     * @return array
  
     */
    public function parseJSON($method, array $args)
    {
        $body = call_user_func_array([$this, $method], $args);
        if ($body instanceof ResponseInterface) {
            $body = $body->getBody();
        }

        if (empty($body)) {
            return false;
        }

        $contents = $body->getContents();
        $res = json_decode($contents, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return false;
        }

        return $res;
    }


    /**
     * Filter the invalid JSON string.
     *
     * @param \Psr\Http\Message\StreamInterface|string $invalidJSON
     *
     * @return string
     */
    protected function fuckTheWeChatInvalidJSON($invalidJSON)
    {
        return preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', trim($invalidJSON));
    }

    /**
     * Build a handler.
     *
     * @return HandlerStack
     */
    protected function getHandler()
    {
        $stack = HandlerStack::create();

        foreach ($this->middlewares as $middleware) {
            $stack->push($middleware);
        }

        if (isset(static::$defaults['handler']) && is_callable(static::$defaults['handler'])) {
            $stack->push(static::$defaults['handler'], self::USER_DEFINED_HANDLER);
        }

        return $stack;
    }
}
