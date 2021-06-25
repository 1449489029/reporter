<?php

namespace reporter\lib;

use reporter\lib\Container;

class Application extends Container
{

    /**
     * @var string 框架版本
     */
    const VERSION = '1.0';

    private function __construct()
    {
        $this->registerBaseBindings();
    }

    /**
     * 获取实例
     *
     * @return Container
     */
    public static function instance()
    {
        if (!self::$that instanceof Application) {
            self::$that = new self();
        }

        return self::$that;
    }


    /**
     * 注册基础的绑定
     *
     * @return void
     */
    public function registerBaseBindings()
    {

    }

    /**
     * 执行函数
     *
     * @param mixed $instance 实例
     * @param string $action 函数名
     * @return void
     */
    public function makeAction($instance, string $action)
    {

    }


}