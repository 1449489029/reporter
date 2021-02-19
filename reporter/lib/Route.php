<?php

namespace reporter\lib;

use reporter\lib\Config;


/**
 * 用于解析访问的路由路径找到对应的控制器
 *
 * 示例：/api/demo/test1 => /app/api/controller/Demo.php -> function test()
 */
class Route
{
    /**
     * @var string 控制器名称
     */
    public $controller;

    /**
     * @var string 方法名称
     */
    public $action;

    /**
     * @param array 查询参数
     */
    public $queryParams = [];


    /**
     * 返回对应的控制器方法
     * 需要在nginx中配置以下伪静态规则：
     * if (!-e $request_filename) {
     *     rewrite ^(.*)$ /index.php?s=$1 last;
     *     break;
     *  }
     */
    public function __construct()
    {
        $this->controller = Config::get('controller', 'web');
        $this->action = Config::get('action', 'web');

        if (isset($_SERVER['REQUEST_URI'])) {
            $this->formatUrlParams($_SERVER['REQUEST_URI']);
        }
    }


    /**
     * 格式化URL参数
     *
     * @param string $url URL
     * @return void
     */
    public function formatUrlParams($url)
    {
        $url = trim($url, '/');
        if (!empty($url)) {
            $urlArr = explode('/', $url);
        } else {
            $urlArr = [];
        }
        $urlArrLen = count($urlArr);
        if ($urlArrLen == 1) {
            $this->controller = $urlArr[0];
        } else if ($urlArrLen == 2) {
            $this->controller = $urlArr[0];
            $this->action = $urlArr[1];
        } else if ($urlArrLen >= 3) {
            $this->controller = $urlArr[0];
            $this->action = $urlArr[1];
            $i = 2;
            while ($urlArrLen > $i) {
                // 收集参数
                if (isset($urlArr[$i + 1])) {
                    $this->queryParams[$urlArr[$i]] = $urlArr[$i + 1];
                }
                $i = $i + 2;
            }
        }

        // 将控制器名称的首字母转换为大写的
        $this->controller = ucfirst($this->controller);


    }


}