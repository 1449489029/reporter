<?php

namespace reporter\lib;

use reporter\Base;

// 管道
class Pipeline
{
    /**
     * @var Request
     */
    protected $Request;

    /**
     * @var array 中间件
     */
    protected $middleware = [
    ];

    /**
     * 设置请求
     *
     * @param Request $Request 请求
     * @retrun Pipeline
     */
    public function setRequest($Request)
    {
        $this->Request = $Request;

        return $this;
    }

    /**
     * 设置中间件
     *
     * @param array $middleware 中间件
     * @return Pipeline
     */
    public function setMiddleware(array $middleware)
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * 运行指定路由
     *
     * @param Application $app
     * @retrun void
     */
    public function run(Application $app)
    {
        $request = $this->Request;

        $pipeline = array_reduce($this->middleware, function ($stack, $pipeline) use ($request) {

            return function () use ($stack, $pipeline, $request) {

                return (new $pipeline())->handle($request, $stack);

            };

        }, function () use ($app) {
            return (new Base())->run($app);
        });

        $pipeline();

    }
}