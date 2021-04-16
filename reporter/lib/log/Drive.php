<?php

namespace reporter\lib\log;

// 日志的基础类
interface Drive
{
    /**
     * 写入日志
     *
     * @param string $message 写入的内容
     * @return bool
     */
    public static function write($message);

    /**
     * 写入所有日志
     *
     * @return bool
     */
    public static function writeAll();

    /**
     * 写入普通日志
     *
     * @param string $message 记录的内容
     * @return bool
     */
    public static function log($message);

    /**
     * 记录日志
     *
     * @param mixed $message 记录的内容
     * @return bool
     */
    public static function record($message);

    /**
     * 记录错误日志
     *
     * @param mixed $message 错误信息
     * @return bool
     */
    public static function error($message);
}