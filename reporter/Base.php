<?php

namespace reporter;

use reporter\lib\Application;
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
     *
     * @param Application $app
     */
    public function run(Application $app)
    {
        self::$Request = new Request();
        $Route = new Route(self::$Request);

        $Log = Log::init();
        try {
            // 加载环境变量
            $envFilePath = ROOT_PATH . '/.env';
            if (is_file($envFilePath)) {
                Env::loadFile($envFilePath);
            }

            // 实例化"服务容器"
//            $Container = Container::instance();

            // 自动绑定框架注册的服务
//            $ReporterContainerProvider = new ReporterContainerProvider($Container);
//            $ReporterContainerProvider->register();
            // 获取应用定制的服务提供者
//            $providers = Config::get('providers', 'app');
//            foreach ((array)$providers as $provider) {
//                $providerInstance = new $provider($Container);
//                // 注册服务
//                $providerInstance->register();
//            }


            // 定义控制器名
            $controllerName = '\application\\' . $Route->model . '\controller\\' . $Route->controller;
            // 定义控制器的指定函数名
            $actionName = $Route->action;

            // 实例化类
            $Controller = new $controllerName($Route);
            if (!empty($Route->queryParams)) {
                // 调用函数并传递参数
//                Injection::make($controllerName, $actionName, $Route->queryParams);
                $app->make($controllerName);
//                call_user_func_array([$Controller, $actionName], $Route->queryParams);
            } else {
                // 只调用函数
//                Injection::make($controllerName, $actionName);
                $controller = $app->make($controllerName);

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
    }


}