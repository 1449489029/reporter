<?php

namespace reporter\lib;

use reporter\lib\Config;

class Log
{
    // 类型
    const TYPE_FILE = 'file'; // 写入到文件中

    /**
     * @var mixed 实例
     */
    public static $example = null;

    public static function init()
    {
        if (empty(self::$example)) {
            $type = Config::get('type', 'log');
            if ($type == self::TYPE_FILE) {
                $typeNameSpace = '\reporter\lib\log\drive\File';
            } else {
                throw new \Exception('无效类型');
            }
            self::$example = new $typeNameSpace();
        }

        return self::$example;
    }
}