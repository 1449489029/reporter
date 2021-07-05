<?php

namespace reporter\lib;

// 日志类
class Log
{
    // 类型
    const TYPE_FILE = 'File'; // 写入到文件中

    /**
     * @var mixed 实例
     */
    public static $instance = null;

    public static function instance()
    {
        $logConfig = Config::all('log');
        $typeNameSpace = '\reporter\lib\log\drive\\' . $logConfig['type'];
        if (!self::$instance instanceof $typeNameSpace) {
            self::$instance = new $typeNameSpace($logConfig);
        }

        return self::$instance;
    }

    /**
     * 写入日志
     *
     * @param string $message 写入的内容
     * @return bool
     */
    public static function write($message)
    {
        self::instance();

        self::$instance::write($message);

        return true;
    }

    /**
     * 写入所有日志
     *
     * @return bool
     */
    public static function writeAll()
    {
        self::instance();

        self::$instance::writeAll();

        return true;
    }

    /**
     * 记录日志
     *
     * @param mixed $message 记录的内容
     * @return bool
     */
    public static function record($message)
    {
        self::instance();

        self::$instance::record($message);

        return true;
    }

    /**
     * 记录错误日志
     *
     * @param mixed $message 错误信息
     * @return bool
     */
    public static function error($message)
    {
        self::instance();

        self::$instance::error($message);

        return true;
    }

    /**
     * 记录普通日志
     *
     * @param mixed $message 记录的内容
     * @return bool
     */
    public static function log($message)
    {
        self::instance();

        self::$instance::log($message);

        return true;
    }


}