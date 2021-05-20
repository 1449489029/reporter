<?php

namespace reporter;

// 自动加载调用的类文件
class Load
{
    /**
     * @var array 存放引入的类
     */
    protected static $classMap = [];

    /**
     * 绑定自动加载类的函数
     *
     * @param string $class 调用的类名称 示例:/application/api/Demo
     * @return bool
     */
    public static function bind($class)
    {
        // 将类名称的\转换为/
        $class = str_replace('\\', '/', $class);

        // 拼接类文件的路径
        $filePath = ROOT_PATH . '/' . $class . '.php';

        // 判断文件是否存在
        if (isset(self::$classMap[$class])) {
            return true;
        }else{
            if (is_file($filePath)) {
                // 加载类文件
                require $filePath;
                // 标记该类文件为已引入
                self::$classMap[$class] = $class;

                return true;
            }else{
                return false;
            }
        }

    }


}