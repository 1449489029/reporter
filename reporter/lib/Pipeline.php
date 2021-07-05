<?php

namespace reporter\lib;

use reporter\lib\Config;
use reporter\lib\Route;

// 管道
class Pipeline
{
    /**
     * @var Request
     */
    protected $Request;

    /**
     * @var string 控制器命名空间
     */
    protected $controller;

    /**
     * @var string 方法名称
     */
    protected $action;

    /**
     * @var array 中间件
     */
    protected $middleware = [];

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
     * @return Pipeline
     */
    public function setMiddleware()
    {
        // 查找当前请求的路由
        $RouteData = Route::query($this->Request->method, $this->Request->uri);
        $this->controller = $RouteData['controller'];
        $this->action = $RouteData['action'];

        // 查询所有已注册的中间件
        $routeMiddlewares = Config::get('routeMiddlewares', 'app');

        foreach ($RouteData['middleware'] as $middleware) {
            if (isset($routeMiddlewares[$middleware]) == false) {
                throw new \Exception('中间件不存在');
            }

            $this->middleware[] = $routeMiddlewares[$middleware];
        }

        return $this;
    }

    /**
     * 运行指定路由
     *
     * @param Application $app
     * @return mixed
     */
    public function run(Application $app)
    {
        $request = $this->Request;

        $pipeline = array_reduce($this->middleware, function ($stack, $pipeline) use ($request) {

            return function () use ($stack, $pipeline, $request) {

                return (new $pipeline())->handle($request, $stack);

            };

        }, function () use ($app) {
            return $this->toRoute($app);
        });

        return $pipeline();
    }

    /**
     * 运行路由
     *
     * @param Application $app
     * @return mixed
     */
    protected function toRoute(Application $app)
    {
        $request = $app->make(Request::class);

        // 实例化类
        $Controller = new $this->controller($app);
        $ResponseContent = $Controller->{$this->action}();

        return $ResponseContent;
    }
}