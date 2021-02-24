<?php

namespace reporter\lib;

class Controller
{
    /**
     * @var \reporter\lib\Route
     */
    public static $Route;


    public function __construct(\reporter\lib\Route $Route)
    {
        self::$Route = $Route;
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
        // 加载视图文件
        $filePath = APP_PATH . '/' . self::$Route->model . '/view/' . self::$Route->controller . '/' . $file;
        if (is_file($filePath)) {
            // 设置视图目录
            $loader = new \Twig\Loader\FilesystemLoader(APP_PATH . '/' . self::$Route->model . '/view/');
            // 配置缓存存放目录
            $twig = new \Twig\Environment($loader, [
                'cache' => VIEW_CACHE_PATH,
                'debug' => IS_DEBUG
            ]);
            // 加载视图文件
            $template = $twig->load(self::$Route->controller . '/' . $file);
            // 为视图加载变量，并输出视图。
            echo $template->render($this->assignVars ? $this->assignVars : '');
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