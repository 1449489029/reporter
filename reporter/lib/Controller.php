<?php

namespace reporter\lib;

// 控制器基础类
class Controller
{
    /**
     * @var \reporter\lib\Application
     */
    public $app;


    public function __construct(\reporter\lib\Application $app)
    {
        $this->app = $app;
    }

    /**
     * @var array 分配的变量集合
     */
    public $assignVars;

    /**
     * 加载视图文件
     *
     * @param string $file 视图文件路劲
     * @return void
     */
    public function display($file)
    {
        $Route = $this->app->make(\reporter\lib\Route::class);

        // 加载视图文件
        $filePath = APP_PATH . '/' . $Route->model . '/view/' . $Route->controller . '/' . $file;
        if (is_file($filePath) == true) {
            // 设置视图目录
            $loader = new \Twig\Loader\FilesystemLoader(APP_PATH . '/' . $Route->model . '/view/');
            // 配置缓存存放目录
            $twig = new \Twig\Environment($loader, [
                'cache' => VIEW_CACHE_PATH,
                'debug' => IS_DEBUG
            ]);
            // 加载视图文件
            $template = $twig->load($Route->controller . '/' . $file);
            // 为视图加载变量，并输出视图。
            return $template->render($this->assignVars ? $this->assignVars : []);
        } else {
            throw new \Exception('视图文件不存在');
        }
    }

    /**
     * 分配变量
     *
     * @param string $name 变量名
     * @param mixed $value 变量值
     * @return void
     */
    public function assign($name, $value)
    {
        $this->assignVars[$name] = $value;
    }
}