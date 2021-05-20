<?php

namespace reporter\lib;

use reporter\lib\Container;

// 依赖注入类
class Injection
{

    private static function getInstance($className)
    {
        // 实例化类
        $params = self::getMethodParams($className);
        $server = (new \ReflectionClass($className))->newInstanceArgs($params);

        // 返回
        return $server;
    }

    /**
     * 制作
     *
     * @param string $className 类名称
     * @param string $methodName 类函数名称 [default='__construct']
     * @param array $params 接收到的参数 [default=[]]
     * @return Object
     */
    public static function make(string $className, string $methodName = '__construct', array $params = [])
    {
        // 获取类的构造函数的参数
        $server = self::getInstance($className);
        if ($methodName == '__construct') {
            return $server;
        }

        // 获取函数参数
        $methodParams = self::getMethodParams($className, $methodName);
        $params = array_values($params);
        $methodParams = array_merge($methodParams, $params);

        $server = $server->{$methodName}(...array_merge($methodParams, $params));

        return $server;
    }

    /**
     * 获取类函数中需要注入参数
     *
     * @param string $className 类名称
     * @param string $methodName 类函数名称 [default='__construct']
     * @return array
     */
    private static function getMethodParams(string $className, string $methodName = '__construct')
    {
        // 反转类的信息
        $class = new \ReflectionClass($className);

        $injectionParams = [];
        // 判断该类中是否存在指定的函数：如果存在
        if ($class->hasMethod($methodName)) {
            // 获取指定函数的信息
            $method = $class->getMethod($methodName);
            // 获取改函数的参数
            $methodParams = $method->getParameters();
            if (count($methodParams) > 0) {
                // 遍历这些参数
                foreach ($methodParams as $param) {
                    // 如果该参数是类
                    $paramClass = $param->getClass();
                    if (!empty($paramClass)) {
                        // 获取这个参数名称
                        $paramClassName = $param->getType();
                        $paramClassName = $paramClassName->getName();

//                        $this->getInstance($paramClassName);

                        // 递归获取这个类的构造函数的参数
                        $paramClassParams = self::getMethodParams($paramClassName);

                        // 查询这个服务是否已经存在于服务容器中
//                        if (Container::has($paramClassName) == true) {
                        // 制作
//                        $injectionParams[] = Container::make($paramClassName);
//                        } else {
                        $paramClass = (new \ReflectionClass($paramClassName))->newInstanceArgs($paramClassParams);

//                            Container::bind($paramClassName, $paramClass);
                        $injectionParams[] = $paramClass;
//                        }
                    }
                }
            }
        }

        return $injectionParams;
    }

    /*
    ReflectionClass = 类报告了一个类的有关信息
        newInstanceArgs = 从给出的参数创建一个新的类实例
        hasMethod = 检查方法是否已定义
        getMethod = 获取一个类方法的 ReflectionMethod
    ReflectionMethod = 类报告了一个方法的有关信息
        getParameters = 获取参数
    ReflectionParameter = 取回了函数或方法参数的相关信息
        getClass = 获得类型提示类
        getName = 获取参数名称
        getType = 获取参数类型
        getDeclaringClass = 获取声明的类
    */
}