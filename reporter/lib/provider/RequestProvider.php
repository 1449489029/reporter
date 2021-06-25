<?php

namespace reporter\lib\provider;

use reporter\lib\ContainerProvider;
use reporter\lib\Request;

class RequestProvider extends ContainerProvider
{

    /**
     * 注册服务
     *
     * @return void;
     */
    public function register()
    {
        $this->container->bind(Request::class, function () {
            return new Request();
        }, true);
    }

}