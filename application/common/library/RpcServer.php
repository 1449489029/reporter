<?php

namespace app\common\library;

class RpcServer
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

    public function start()
    {
        while (true) {

            // 接收数据包
            $request = $this->redis->brpop($this->redisRequestListKey, 30);

            if (!empty($request)) {
                print_r($request);
                echo "\r\n";
                // 解包
//                $request = unpack('a*', $request);
                $request = json_decode($request[1], true);

                // 封装响应包
                $response = [
                    'code' => 200,
                    'msg' => '成功返回',
                    'data' => '',
                ];

                switch ($request['action']) {
                    case 'get':
                        $response['msg'] = '成功调用get方法';
                        break;
                    default:
                        $response['msg'] = '调用的方法不存在';
                        break;
                }

                // 包装
                $response = json_encode($response);
//                $response = pack('a*', $response);

                // 发送结果包
                $this->redis->lPush($request['id'], $response);
            }


        }
    }
}