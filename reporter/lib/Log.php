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
    public static $example = null;

    public static function init()
    {
        $logConfig = Config::all('log');
        $typeNameSpace = '\reporter\lib\log\drive\\' . $logConfig['type'];
        if (!self::$example instanceof $typeNameSpace) {
            self::$example = new $typeNameSpace($logConfig);
        }

        return self::$example;
    }

    /**
     * 写入日志
     *
     * @param string $message 写入的内容
     * @return bool
     */
    public static function write($message)
    {
        self::init();

        self::$example::write($message);

        return true;
    }

    /**
     * 写入所有日志
     *
     * @return bool
     */
    public static function writeAll()
    {
        self::init();

        self::$example::writeAll();

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
        self::init();

        self::$example::record($message);

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
        self::init();

        self::$example::error($message);

        return true;
    }


}