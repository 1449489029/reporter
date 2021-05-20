<?php

namespace reporter\lib;

abstract class ContainerProvider
{

    /**
     * @var \reporter\lib\Container 服务容器
     */
    protected $container;

    public function __construct(\reporter\lib\Container $container)
    {
        $this->container = $container;
    }

    /**
     * 注册服务
     *
     * @return void;
     */
    public function register()
    {

    }
}