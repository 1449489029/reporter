<?php

namespace app\common\library;

use app\common\library\Random;
use function MongoDB\Driver\Monitoring\removeSubscriber;

class RpcClient
{
    protected $redisHost = '127.0.0.1';
    protected $redisPort = 6379;
    protected $redisRequestListKey = 'request_list';
    protected $redis = null;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect($this->redisHost, $this->redisPort);
    }

    public function run($action, $params = [])
    {
        $uuid = Random::uuid();

        // 封装传输数据
        $request = [
            'action' => $action,
            'params' => $params,
            'id' => $uuid
        ];

        // 包装成二进制数据包
        $request = json_encode($request);
//        $request = pack('a*', $request);

        // 发送数据包
        $this->redis->lPush($this->redisRequestListKey, $request);

        // 读取返回值
        $result = $this->redis->blPop($uuid, 30);

        // 解包
//        $result = unpack('a*', $result);
//        \think\Log::record($result);
        $result = json_decode($result[1], true);

        return $result;
    }
}