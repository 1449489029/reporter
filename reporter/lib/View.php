<?php

namespace reporter\lib;

class View
{

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
        // 分配变量
        extract($this->assignVars);

        // 加载视图文件
        $filePath = APP_PATH . '/view' . $file;
        if (is_file($filePath)) {
            require $filePath;
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