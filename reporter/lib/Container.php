<?php

namespace reporter\lib;

// 服务容器类
class Container
{
    /**
     * @var array 服务列表
     */
    protected static $servers = [];

    /**
     * 制造
     *
     * @param string $serverAlias 服务别名
     * @return object
     */
    public static function make(string $serverAlias)
    {
        if (isset(self::$servers[$serverAlias]) == true) {
            return self::$servers[$serverAlias];
        }

        throw new \Exception($serverAlias . '服务不存在');
    }

    /**
     * 是否存在该服务
     *
     * @param string $serverAlias 服务别名
     * @return bool
     */
    public function has(string $serverAlias): bool
    {
        return isset(self::$servers[$serverAlias]);
    }

    /**
     * 绑定
     *
     * @param string $serverAlias 服务别名
     * @param object $instance 实例化后的对象
     * @return void
     */
    public static function bind(string $serverAlias, $instance): void
    {
        self::$servers[$serverAlias] = $instance;

        return;
    }

}