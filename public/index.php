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
 * 5.引入Composer类库
 */

// 定义常量

// 项目更目录
use reporter\lib\Response;

define('ROOT_PATH', __DIR__ . '/..');
// 核心文件目录
define('CORE_PATH', ROOT_PATH . '/reporter');
// 应用文件目录
define('APP_PATH', ROOT_PATH . '/application');
// 配置文件目录
define('CONFIG_PATH', ROOT_PATH . '/config');
// 日志目录
define('LOG_PATH', ROOT_PATH . '/runtime/log');
// 缓存目录
define('CACHE_PATH', ROOT_PATH . '/runtime/cache/data');
// 视图缓存目录
define('VIEW_CACHE_PATH', ROOT_PATH . '/runtime/cache/view');
// 文件上传目录
define('UPLOAD_PATH', ROOT_PATH . '/public/upload');
// 当前时间
define('TIME_NOW', time());
// 今天的时间戳
define('TIME_TODAY', strtotime(date('Y-m-d')));
// 开始运行时间
define('START_TIME', microtime(true));
// 开始运行时的内存使用量
define('START_MEMORY', memory_get_usage());

define('IS_DEBUG', true);

// 引入Composer类库
require ROOT_PATH . '/vendor/autoload.php';

if (IS_DEBUG) {
    // 打开错误提示
    ini_set("display_errors", "On");
    //显示所有错误
    ini_set("error_reporting", E_ALL);
} else {
    // 关闭错误提示
    ini_set("display_errors", "Off");
}
// 引入函数库
require CORE_PATH . '/common/function.php';


// 开启自动加载
require CORE_PATH . '/Load.php';
spl_autoload_register('\reporter\Load::bind');

// 加载框架启动文件
$app = require_once ROOT_PATH . '/bootstrap/app.php';

$Response = (new \reporter\Kernel())->start($app)->send();




