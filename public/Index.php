<?php

//use /reporter/Base;

/**
 * 框架入口文件
 *
 * 运行流程：
 * 1.定义常量
 * 2.引入函数库
 * 3.开启自动加载
 * 4.开启框架
 */

// 定义常量
define('ROOT_PATH', __DIR__ . '/..');
define('CORE_PATH', ROOT_PATH . '/reporter');
define('APP_PATH', ROOT_PATH . '/application');

define('IS_DEBUG', true);

if (IS_DEBUG) {
    // 打开错误提示
    ini_set("display_errors", "On");
    //显示所有错误
    ini_set("error_reporting",E_ALL);
} else {
    // 关闭错误提示
    ini_set("display_errors", "Off");
}


// 引入函数库
require CORE_PATH . '/common/function.php';


// 开启自动加载
require CORE_PATH . '/Load.php';
spl_autoload_register('\reporter\Load::bind');


// 开启框架
\reporter\Base::run();










