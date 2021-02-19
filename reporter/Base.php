<?php

namespace reporter;

class Base
{
    public static function run()
    {
        $Route = new \reporter\lib\Route();

        $controllerName = '\application\\controller\\' . $Route->controller;
        $actionName = $Route->action;

        // 实例化类
        $Controller = new $controllerName();
        if(!empty($Route->queryParams)){
            // 调用函数并传递参数
            call_user_func_array([$Controller, $actionName], $Route->queryParams);
        }else{
            // 只调用函数
            $Controller->$actionName();
        }
    }

}