<?php

namespace reporter\lib;

class ReporterContainerProvider extends ContainerProvider
{
    /**
     * 注册服务
     *
     * @return void;
     */
    public function register()
    {
        $this->container->bind(\reporter\lib\DB\Connect::class, \reporter\lib\DB\Connect::class, true);
        $this->container->bind(\reporter\lib\Request::class, \reporter\lib\Request::class);
    }
}