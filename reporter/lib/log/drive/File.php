<?php

namespace reporter\lib\log\drive;

use reporter\lib\log\Drive;

class File implements Drive
{
    /**
     * @var array 存放请求日志的临时容器
     */
    protected static $headerMessages = [];

    /**
     * @var array 存放日志的临时容器
     */
    protected static $messages = [];

    // 级别
    const LEVEL_RECORD = 'record'; // 记录
    const LEVEL_WARNING = 'warning'; // 警告
    const LEVEL_ERROR = 'error'; // 错误

    /**
     * @var array 配置项
     */
    protected static $options = [
        // 每隔多久产生一个新日志文件 单位:秒
        'duration' => 300
    ];

    public function __construct($options)
    {
        self::$options = array_merge(self::$options, $options);
    }


    /**
     * 写入日志
     *
     * @param string $message 写入的内容
     * @return bool
     */
    public static function write($message)
    {
        // 获取存放的目录
        $dirPath = self::getLogDir();
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        // 获取文件名
        $fileName = self::getLogFileName();

        // 拼接日志文件的路径
        $filePath = $dirPath . '/' . $fileName;

        file_put_contents($filePath, $message, FILE_APPEND);

        return true;
    }

    /**
     * 写入所有日志
     *
     * @return bool
     */
    public static function writeAll()
    {
        // 获取存放的目录
        $dirPath = self::getLogDir();
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        // 获取文件名
        $fileName = self::getLogFileName();

        // 拼接日志文件的路径
        $filePath = $dirPath . '/' . $fileName;


        foreach (self::$headerMessages as $headerMessage) {
            file_put_contents($filePath, $headerMessage, FILE_APPEND);
        }
        self::$headerMessages = [];

        foreach (self::$messages as $message) {
            file_put_contents($filePath, $message, FILE_APPEND);
        }
        self::$messages = [];

        return true;
    }

    /**
     * 记录普通日志
     *
     * @param string $message 记录的内容
     * @return bool
     */
    public static function log($message)
    {

        self::$headerMessages[] = $message . PHP_EOL;

        return true;
    }

    /**
     * 记录日志
     *
     * @param mixed $message 记录的内容
     * @return bool
     */
    public static function record($message)
    {
        if (is_array($message)) {
            $message = '[ info ] ' . print_r($message, true);
        } else {
            $message = '[ info ] ' . $message . PHP_EOL;
        }

        self::$messages[] = $message;

        return true;
    }

    /**
     * 记录错误日志
     *
     * @param mixed $message 错误信息
     * @return bool
     */
    public static function error($message)
    {
        if (is_array($message)) {
            $message = '[ error ] ' . print_r($message, true);
        } else {
            $message = '[ error ] ' . $message . PHP_EOL;
        }

        self::$messages[] = $message;

        return true;
    }


    /**
     * 获取当前要写入的日志名
     *
     * @return string
     */
    protected static function getLogFileName()
    {
        $number = floor((TIME_NOW - TIME_TODAY) / self::$options['duration']);

        $fileName = date('d') . '_' . $number . '.log';

        return $fileName;
    }


    /**
     * 获取日志存放的目录
     *
     * @return string
     */
    protected static function getLogDir()
    {
        $dirPath = LOG_PATH . '/' . date('Ym');

        return $dirPath;
    }
}