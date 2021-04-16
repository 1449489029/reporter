<?php

namespace reporter\lib\cache\drive;

use reporter\lib\cache\Drive;
use reporter\lib\Config;

class File implements Drive
{
    protected $options = [
        // 哈希加密方式
        'hash_type' => 'md5',
        // 子目录
        'prefix' => 'yaf',
        // 缓存有效期 单位:秒
        'expire' => 3600
    ];

    public function __construct()
    {
        $cacheConfig = Config::all('cache');

        $this->options = [
            'hash_type' => $cacheConfig['hash_type'],
            'expire' => $cacheConfig['expire'],
        ];

    }

    /**
     * 查询
     *
     * @param string $key 键
     * @return mixed
     */
    public function get(string $key)
    {
        // 获取存在的文件路径
        $cacheFilePath = $this->getCacheFilePath($key);

        if (!is_file($cacheFilePath)) {
            return '';
        }

        $value = file_get_contents($cacheFilePath);
        $value = unserialize($value);

        // 已经过期
        if ($value['expire'] < TIME_NOW) {
            // 删除这个文件
            $this->deleteFilePath($cacheFilePath);
            return '';
        }

        return $value['value'];
    }

    /**
     * 设置
     *
     * @param string $key 键
     * @param mixed $value 值
     * @return bool
     */
    public function set(string $key, $value)
    {
        // 获取存在的文件路径
        $cacheFilePath = $this->getCacheFilePath($key);

        // 设置存储的格式
        $content = [
            'value' => $value,
            'expire' => TIME_NOW + $this->options['expire']
        ];

        // 保存
        file_put_contents($cacheFilePath, serialize($content));

        return true;
    }


    /**
     * 验证是否存在
     *
     * @param string $key 键
     * @return bool
     */
    public function has(string $key)
    {
        // 获取存在的文件路径
        $cacheFilePath = $this->getCacheFilePath($key);

        if (!is_file($cacheFilePath)) {
            return '';
        }

        // 读取文件数据
        $value = file_get_contents($cacheFilePath);
        // 反序列化
        $value = unserialize($value);

        // 已经过期
        if ($value['expire'] < TIME_NOW) {
            // 删除这个文件
            $this->deleteFilePath($cacheFilePath);
            return false;
        }

        return true;
    }


    /**
     * 删除
     *
     * @param string $key 键
     * @return bool
     */
    public function delete(string $key)
    {
        // 获取存在的文件路径
        $cacheFilePath = $this->getCacheFilePath($key);

        // 删除文件
        $this->deleteFilePath($cacheFilePath);

        return true;
    }

    /**
     * 删除文件
     *
     * @param string $filePath 文件路径
     * @return bool
     */
    private function deleteFilePath(string $filePath)
    {
        if (empty($filePath)) {
            return false;
        } else if (!is_file($filePath)) {
            return false;
        }

        // 删除文件
        unlink($filePath);

        return true;
    }

    /**
     * 获取文件地址
     *
     * @param string $key 键
     * @return string
     */
    private function getCacheFilePath(string $key)
    {
        $fileName = hash($this->options['hash_type'], $key);

        // 使用子目录
        $fileName = substr($fileName, 0, 2) . DIRECTORY_SEPARATOR . $fileName;

        $fileName = CACHE_PATH . DIRECTORY_SEPARATOR . $fileName . '.php';
        $dir = dirname($fileName);

        if (!is_dir($dir)) {
            try {
                mkdir($dir, 0755, true);
            } catch (\Exception $e) {

            }
        }

        return $fileName;
    }
}