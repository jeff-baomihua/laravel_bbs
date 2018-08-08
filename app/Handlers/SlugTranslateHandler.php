<?php

namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class SlugTranslateHandler
{
    private $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
    private $appid;
    private $key;
    private $salt;
    private $from;
    private $to;

    public function __construct($from='zh', $to='en')
    {
        $this->appid = config('services.baidu_translate.appid');
        $this->key   = config('services.baidu_translate.key');
        $this->salt  = time();
        $this->from  = $from;
        $this->to    = $to;
    }

    public function translate($text)
    {
        // 如果没有配置百度翻译，自动使用兼容的拼音方案
        if (empty($this->appid) || empty($this->key)) {
            return $this->pinyin($text);
        }

        // 实例化 HTTP 客户端
        $http = new Client;

        // 根据文档，生成 sign
        // http://api.fanyi.baidu.com/api/trans/product/apidoc
        // appid+q+salt+密钥 的MD5值
        $sign = md5($this->appid. $text . $this->salt . $this->key);

        // 构建请求参数
        $query = http_build_query([
            "q"     =>  $text,
            "from"  => "zh",
            "to"    => "en",
            "appid" => $this->appid,
            "salt"  => $this->salt,
            "sign"  => $sign,
        ]);

        // 发送 HTTP Get 请求
        $response = $http->get($this->api.$query);

        $result = json_decode($response->getBody(), true);

        /**
        获取结果，如果请求成功，dd($result) 结果如下：

        array:3 [▼
        "from" => "zh"
        "to" => "en"
        "trans_result" => array:1 [▼
        0 => array:2 [▼
        "src" => "XSS 安全漏洞"
        "dst" => "XSS security vulnerability"
        ]
        ]
        ]

         **/

        // 尝试获取获取翻译结果
        if (isset($result['trans_result'][0]['dst'])) {
            return str_slug($result['trans_result'][0]['dst']);
        } else {
            // 如果百度翻译没有结果，使用拼音作为后备计划。
            return $this->pinyin($text);
        }
    }

    public function pinyin($text)
    {
        return str_slug(app(Pinyin::class)->permalink($text));
    }
}