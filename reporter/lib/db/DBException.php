<?php

namespace reporter\lib\db;

use reporter\lib\Log;

// 数据库处理的异常处理类
class DBException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        Log::error($this->getErrorData($message, $code));
        throw new \Exception($message, $code);
    }

    /**
     * 获取错误信息
     *
     * @param string $message 错误消息
     * @param int $code 错误码
     * @return array
     */
    public function getErrorData(string $message, int $code)
    {
        $errorData = [
            'code' => $code,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'message' => $message
        ];

        return $errorData;
    }
}