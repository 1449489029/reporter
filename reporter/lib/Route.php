<?php

namespace reporter\lib;

use reporter\lib\Config;
use Closure;

/**
 * 用于解析访问的路由路径找到对应的控制器
 *
 * 示例：/api/demo/test1 => /app/api/controller/Demo.php -> function test()
 */
class Route
{
    // 请求方式
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';


    /**
     * @var array 配置的POST请求的路由集合
     */
    protected static $routes = [];


    private function __construct()
    {
    }

    /**
     * 增加POST路由
     *
     * @param string $uri 路由
     * @param string $controller 控制器命名空间
     * @param string $action 方法名称
     * @param string|array $middleware 中间件 [default=NULL]
     * @return void
     */
    public static function post(string $uri, string $controller, string $action, $middleware = NULL): void
    {
        self::addRoute(self::METHOD_POST, $uri, $controller, $action, $middleware);
    }

    /**
     * 增加GET路由
     *
     * @param string $uri 路由
     * @param string $controller 控制器命名空间
     * @param string $action 方法名称
     * @param string|array $middleware 中间件 [default=NULL]
     * @return void
     */
    public static function get(string $uri, string $controller, string $action, $middleware = NULL): void
    {
        self::addRoute(self::METHOD_GET, $uri, $controller, $action, $middleware);
    }


    /**
     * 配置POST路由
     *
     * @param string $method 请求方式
     * @param string $uri 路由
     * @param string $controller 控制器命名空间
     * @param string $action 方法名称
     * @param string|array $middleware 中间件 [default=NULL]
     * @return void
     */
    public static function addRoute(string $method, string $uri, string $controller, string $action, $middleware = NULL): void
    {
        if (is_null($middleware)) {
            $middleware = [];
        } else
            if (is_string($middleware)) {
                $middleware = [$middleware];
            } else if (is_array($middleware)) {
                $middleware = $middleware;
            }

        self::$routes[$method][$uri] = [
            'controller' => $controller,
            'action' => $action,
            'middleware' => $middleware
        ];
    }


    /**
     * 查询指定的路由
     *
     * @param string $method 请求的方式
     * @param string $uri 路由
     * @return array
     */
    public
    static function query(string $method, string $uri)
    {
        if (isset(self::$routes[$method][$uri]) == false) {
            throw new \Exception('路由不存在');
        }

        return self::$routes[$method][$uri];
    }


}