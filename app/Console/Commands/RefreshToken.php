<?php

namespace App\Console\Commands;

use App\Libraries\WoaapApi;
use App\Model\WechatApp;
use Illuminate\Console\Command;

class RefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wechat:refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $wechat_list = WechatApp::get();
        foreach ($wechat_list as $row) {
            $update = [];
            $resp = WoaapApi::getInstance()->getAckey($row->appid, $row->appkey);
            $ackey = $resp['ackey'] ?? '';
            if ($ackey)
            {
                $update['ackey'] = $ackey;

                $resp = WoaapApi::getInstance()->getAccessToken($ackey);
                $update['access_token'] = $resp['access_token'] ?? '';

                $resp = WoaapApi::getInstance()->getJsTicket($ackey);
                $update['js_ticket'] = $resp['js_ticket'] ?? '';

                $resp = WoaapApi::getInstance()->getApiTicket($ackey);
                $update['api_ticket'] = $resp['api_ticket'] ?? '';
                $update['updated_at'] = date("Y-m-d H:i:s",time());
            }
           $row->fill($update)->save();
        }
    }
    /**
     * 异常处理
     * @param string $event
     * @param array $data
     */
    public static function handleException($event = '', $data = [])
    {
        $filePath = storage_path('api/exception/' . $event . '/' . date('Ym'));
        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);
        }
        $file = $filePath . '/' . $event . '_' . date('Ymd') . '.log';
        file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL . PHP_EOL, FILE_APPEND);
    }

}
