<?php

namespace reporter\lib;

use reporter\lib\Config;
use reporter\lib\interfaces\Cache as CacheInterface;

// 缓存调用层
class Cache implements CacheInterface
{

    /**
     * @var array 对象集合
     */
    protected static $thats = [];

    private function __construct()
    {
    }

    /**
     * 获取缓存链接
     *
     * @param string $type 缓存类型 [default = '']
     * @return Object
     */
    public static function getConnect(string $type = '')
    {
        if (empty($type)) {
            $type = Config::get('type', 'cache');
        }
        $class = '\\reporter\\lib\\cache\\drive\\' . $type;
        $name = md5($class);
        if (isset(self::$thats[$name]) == false || !self::$thats[$name] instanceof $class) {
            self::$thats[$name] = new $class;
        }

        return self::$thats[$name];
    }

}