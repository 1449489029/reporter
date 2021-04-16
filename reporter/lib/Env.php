<?php

namespace reporter\lib;

// 环境变量基础类
class Env
{
    const ENV_PREFIX = 'REPORTER_';

    /**
     * 加载配置文件
     *
     * @access public
     * @param string $filePath 配置文件路径
     * @return void
     */
    public static function loadFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception('配置文件' . $filePath . '不存在');
        }

        // 读取环境变量 ["配置项" => ["配置字段1" => "配置值1", "配置字段2" => "配置值2", ...]]
        $env = parse_ini_file($filePath, true);
        foreach ($env as $key => $val) {
            // strtoupper() = 将小写字母转换为大写的字母
            $prefix = static::ENV_PREFIX . strtoupper($key);
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $item = $prefix . '_' . strtoupper($k);
                    // 设置环境变量
                    putenv($item . "=" . $v);
                }
            } else {
                // 设置环境变量
                putenv($prefix . "=" . $val);
            }
        }

        return;
    }

    /**
     * 获取环境变量值
     *
     * @access public
     * @param string $name 环境变量名（支持二级 . 号分割）
     * @param string $default 默认值
     * @return mixed
     */
    public static function get(string $name, $default = null)
    {
        $result = getenv(static::ENV_PREFIX . strtoupper(str_replace('.', '_', $name)));

        if (false !== $result) {
            if ('false' === $result) {
                $result = false;
            } elseif ('true' === $result) {
                $result = true;
            }
            return $result;
        }
        return $default;
    }

}