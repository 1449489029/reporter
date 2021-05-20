<?php

namespace reporter;

use reporter\lib\Log;
use reporter\lib\Route;
use reporter\lib\Injection;
use reporter\lib\Request;
use reporter\lib\Env;
use reporter\lib\Container;
use reporter\lib\ReporterContainerProvider;
use reporter\lib\Config;


class Base
{
    /**
     * @var \reporter\lib\Request
     */
    public static $Request;

    /**
     * @var \reporter\lib\Route
     */
    public static $Route;

    /**
     * 开启框架
     */
    public static function run()
    {
        self::$Request = new Request();
        self::start();
        $Log = Log::init();
        try {
            // 加载环境变量
            $envFilePath = ROOT_PATH . '/.env';
            if (is_file($envFilePath)) {
                Env::loadFile($envFilePath);
            }

            // 实例化"服务容器"
            $Container = new Container();

            // 自动绑定框架注册的服务
            $ReporterContainerProvider = new ReporterContainerProvider($Container);
            $ReporterContainerProvider->register();
            // 获取应用定制的服务提供者
            $providers = Config::get('providers', 'app');
            foreach ((array)$providers as $provider) {
                $providerInstance = new $provider($Container);
                // 注册服务
                $providerInstance->register();
            }


            // 定义控制器名
            $controllerName = '\application\\' . self::$Route->model . '\controller\\' . self::$Route->controller;
            // 定义控制器的指定函数名
            $actionName = self::$Route->action;

            // 实例化类
            $Controller = new $controllerName(self::$Route);
            if (!empty(self::$Route->queryParams)) {
                // 调用函数并传递参数
                Injection::make($controllerName, $actionName, self::$Route->queryParams);
//                call_user_func_array([$Controller, $actionName], self::$Route->queryParams);
            } else {
                // 只调用函数
                Injection::make($controllerName, $actionName);
//                $Controller->$actionName();
            }
        } catch (\Exception $e) {
            $error_info = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ];
            $Log::error($error_info);

            if (IS_DEBUG == true) {
                $whoops = new \Whoops\Run;
                $whoops->allowQuit(false);
                $whoops->writeToOutput(false);
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
                $html = $whoops->handleException($e);
                echo $html;
            }
        } catch (\Error $e) {
            $error_info = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ];
            $Log::error($error_info);

            if (IS_DEBUG == true) {
                $whoops = new \Whoops\Run;
                $whoops->allowQuit(false);
                $whoops->writeToOutput(false);
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
                $html = $whoops->handleException($e);
                echo $html;
            }
        }

        self::end();

    }

    /**
     * 开始运行时触发
     */
    protected static function start()
    {
        $Log = Log::init();

        $Log::log('---------------------------------------------------------------');
        $headLog = '[ ' . date(DATE_ATOM) . ' ] ' . self::$Request->clientIP . ' ' . self::$Request->method . ' ' . self::$Request->domain . self::$Request->uri;
        $Log::log($headLog);

        $Route = self::$Route = new Route(self::$Request);

        $routeData = [
            $Route->model,
            $Route->controller,
            $Route->action
        ];
        $routeLog = '[ ROUTE ] ' . print_r($routeData, true);
        $Log::record($routeLog);
        $headerLog = '[ HEADER ] ' . print_r(self::$Request->getHeader(), true);
        $Log::record($headerLog);
        $paramsLog = '[ PARAM ] ' . print_r(self::$Request->getParams(), true);
        $Log::record($paramsLog);
    }

    /**
     * 结束运行时触发
     */
    protected static function end()
    {
        $Log = Log::init();

        $useTime = self::getUseTime();
        $throughputRate = self::getThroughputRate($useTime);
        $memoryUsage = self::getMemoryUsage();
        $fileIncludeCount = self::getFileIncludeCount();
        $headLog = '[运行时间：' . $useTime . 's] [吞吐率：' . $throughputRate . '] [内存消耗：' . $memoryUsage . '] [文件加载：' . $fileIncludeCount . ']';
        $Log::log($headLog);

        // 写入日志
        $Log::writeAll();
    }

    /**
     * 计算运行耗时
     *
     * @return float
     */
    protected static function getUseTime()
    {
        return number_format((microtime(true) - START_TIME), 6);
    }

    /**
     * 计算当前访问的吞吐率情况
     *
     * @param float $useTime 运行耗时
     * @return string
     */
    protected static function getThroughputRate($useTime)
    {
        return number_format(1 / $useTime, 2) . 'req/s';
    }

    /**
     * 获取内存消耗
     *
     * @return string
     */
    protected static function getMemoryUsage()
    {
        $result = ((memory_get_usage() - START_MEMORY) / 1024) . 'Kb';

        return $result;
    }

    /**
     * 获取文件加载数量
     *
     * @return int
     */
    protected static function getFileIncludeCount()
    {
        return count(get_included_files());
    }

}