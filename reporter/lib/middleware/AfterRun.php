<?php

namespace reporter\lib\middleware;

use Closure;
use reporter\lib\Log;
use reporter\lib\Request;

class AfterRun
{
    /**
     * 处理传入的请求
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $nextRunResult = $next();

        $useTime = self::getUseTime();
        $throughputRate = self::getThroughputRate($useTime);
        $memoryUsage = self::getMemoryUsage();
        $fileIncludeCount = self::getFileIncludeCount();
        $headLog = '[运行时间：' . $useTime . 's] [吞吐率：' . $throughputRate . '] [内存消耗：' . $memoryUsage . '] [文件加载：' . $fileIncludeCount . ']';
        Log::log($headLog);

        // 写入日志
        Log::writeAll();

        return $nextRunResult;
    }


    /**
     * 计算运行耗时
     *
     * @return float
     */
    protected static function getUseTime()
    {
        return number_format((microtime(true) - START_TIME), 6);
    }

    /**
     * 计算当前访问的吞吐率情况
     *
     * @param float $useTime 运行耗时
     * @return string
     */
    protected static function getThroughputRate($useTime)
    {
        return number_format(1 / $useTime, 2) . 'req/s';
    }

    /**
     * 获取内存消耗
     *
     * @return string
     */
    protected static function getMemoryUsage()
    {
        $result = ((memory_get_usage() - START_MEMORY) / 1024) . 'Kb';

        return $result;
    }

    /**
     * 获取文件加载数量
     *
     * @return int
     */
    protected static function getFileIncludeCount()
    {
        return count(get_included_files());
    }
}