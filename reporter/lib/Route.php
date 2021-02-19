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
     * @var string $model 模块
     */
    public $model;

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
    public function __construct(\reporter\lib\Request $Request)
    {
        $this->model = Config::get('model', 'web');
        $this->controller = Config::get('controller', 'web');
        $this->action = Config::get('action', 'web');
        $this->formatUrlParams($Request->uri);
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
            $urlArr = explode('?', $url);
            $urlArr = explode('/', $urlArr[0]);
        } else {
            $urlArr = [];
        }
        $urlArrLen = count($urlArr);
        if ($urlArrLen == 1) {
            $this->model = $urlArr[0];
        } else if ($urlArrLen == 2) {
            $this->model = $urlArr[0];
            $this->controller = $urlArr[1];
        } else if ($urlArrLen == 3) {
            $this->model = $urlArr[0];
            $this->controller = $urlArr[1];
            $this->action = $urlArr[2];
        } else if ($urlArrLen >= 4) {
            $this->model = $urlArr[0];
            $this->controller = $urlArr[1];
            $this->action = $urlArr[2];
            $i = 3;
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