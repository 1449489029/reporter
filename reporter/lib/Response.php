<?php

namespace reporter\lib;

/**
 * Response represents an HTTP response.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Response
{
    /**
     * 返回json以及请求头
     * @param array $data 数据
     * @param string $message 描述 [default='']
     * @param int $code 状态码 [default=200]
     */
    public static function jsonOutput($data, string $message = '', int $code = 200)
    {
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        header('Access-Control-Allow-Origin: *');//解决跨域
        header('Content-type: application/json');
        exit(json_encode([
            'data' => $data,
            'code' => $code,
            'message' => $message
        ]));
    }
}
