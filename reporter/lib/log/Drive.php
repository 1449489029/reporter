<?php

namespace reporter\lib\log;

// 日志的基础类
abstract class Drive
{
    /**
     * @var array 存放请求日志的临时容器
    */
    public static $headerMessages = [];

    /**
     * @var array 存放日志的临时容器
    */
    public static $messages = [];

    // 级别
    const LEVEL_RECORD = 'record'; // 记录
    const LEVEL_WARNING = 'warning'; // 警告
    const LEVEL_ERROR = 'error'; // 错误

    /**
     * 写入日志
     *
     * @param string  $message   写入的内容
     * @return bool
     */
    abstract public static function write($message);

    /**
     * 写入所有日志
     *
     * @return bool
     */
    abstract public static function writeAll();

    /**
     * 写入普通日志
     *
     * @param string $message 记录的内容
     * @return bool
    */
    abstract public static function log($message);

    /**
     * 记录日志
     *
     * @param mixed $message 记录的内容
     * @return bool
    */
    abstract public static function record($message);

    /**
     * 记录错误日志
     *
     * @param mixed $message 错误信息
     * @return bool
     */
    abstract public static function error($message);
}