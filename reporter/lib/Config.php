<?php

namespace reporter\lib;

// 用于处理配置数据
class Config
{

    public static $configs = [];

    /**
     * 读取配置数据
     *
     * @param string $name 配置名
     * @param string $configFileName 配置文件名称 [default = 'web']
     * @return mixed
     */
    public static function get($name, $configFileName = 'web')
    {
        // 如果需要读取的配置文件已经加载
        if (isset(self::$configs[$configFileName])) {
            return self::$configs[$configFileName][$name];
        } else {
            // 获取配置文件的路径
            $configFilePath = self::getConfigFilePath($configFileName);

            // 加载配置
            $configData = require $configFilePath;

            // 保存配置
            self::$configs[$configFileName] = $configData;

            // 返回指定配置项
            return $configData[$name];
        }
    }

    /**
     * 读取指定配置文件的所有配置项
     *
     * @param string $configFileName 配置文件名称 [default = 'web']
     * @return array
     */
    public static function all($configFileName = 'web')
    {
        // 如果需要读取的配置文件已经加载
        if (isset(self::$configs[$configFileName])) {
            return self::$configs[$configFileName];
        } else {
            // 获取配置文件的路径
            $configFilePath = self::getConfigFilePath($configFileName);

            // 加载配置
            $configData = require $configFilePath;

            // 保存配置
            self::$configs[$configFileName] = $configData;

            // 返回指定配置项
            return $configData;
        }
    }

    /**
     * 获取配置文件路径
     *
     * @param string $configFileName 配置文件名称
     * @return string
     */
    public static function getConfigFilePath($configFileName)
    {
        $configFilePath = CONFIG_PATH . '/' . $configFileName . '.php';

        return $configFilePath;
    }

}