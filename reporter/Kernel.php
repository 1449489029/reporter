<?php

namespace reporter;

use reporter\lib\Application;
use reporter\lib\Env;
use reporter\lib\Log;
use reporter\lib\Request;
use reporter\lib\Response;
use reporter\lib\Route;
use reporter\lib\Pipeline;

class Kernel
{

    /**
     *
     * @param Application $app
     * @return Response
     */
    public function start(Application $app)
    {
        try {
            // 获取请求服务
            $request = $app->make(Request::class);


            $this->beforeRun($app, $request);
            $Response = $this->run($app, $request);
            $this->afterRun($app, $request);

            return $Response;
        } catch (\Exception $e) {
            $error_info = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ];
            Log::error($error_info);

            if (IS_DEBUG == true) {
                $whoops = new \Whoops\Run;
                $whoops->allowQuit(false);
                $whoops->writeToOutput(false);
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
                $html = $whoops->handleException($e);
                echo $html;
            }
        } catch (\Throwable $e) {
            $error_info = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ];
            Log::error($error_info);

            if (IS_DEBUG == true) {
                $whoops = new \Whoops\Run;
                $whoops->allowQuit(false);
                $whoops->writeToOutput(false);
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
                $html = $whoops->handleException($e);
                echo $html;
            }
        }
    }


    /**
     * 开启框架
     *
     * @param Application $app
     * @param Request $request
     * @return Response
     */
    private function run(Application $app, Request $request)
    {
        // 加载环境变量
        $envFilePath = ROOT_PATH . '/.env';
        if (is_file($envFilePath)) {
            Env::loadFile($envFilePath);
        }

        // 加载路由
        require ROOT_PATH . '/routes/web.php';

        // 查找请求的路由


        // 进入管道
        $ResponseContent = (new Pipeline())
            ->setRequest($request)
            ->setMiddleware()
            ->run($app);

        // header('Content-type: application/json');
        if (is_array($ResponseContent)) {
            $Response = (new Response())->setContent(json_encode($ResponseContent))
                ->setCode(200)
                ->setHeader('Content-type', 'application/json');
        } else {
            $Response = (new Response())->setContent($ResponseContent)
                ->setCode(200)
                ->setHeader('Content-type', 'text/html');
        }


        return $Response;
    }

    /**
     * 开启框架前
     *
     * @param Application $app
     * @param Request $request
     * @return void
     */
    private function beforeRun(Application $app, Request $request): void
    {
        Log::log('---------------------------------------------------------------');
        $headLog = '[ ' . date(DATE_ATOM) . ' ] ' . $request->clientIP . ' ' . $request->method . ' ' . $request->domain . $request->uri;
        Log::log($headLog);


        $headerLog = '[ HEADER ] ' . print_r($request->getHeader(), true);
        Log::record($headerLog);
        $paramsLog = '[ PARAM ] ' . print_r($request->getParams(), true);
        Log::record($paramsLog);
    }


    /***
     * 运行框架后
     *
     * @param Application $app
     * @param Request $request
     * @return void
     */
    private function afterRun(Application $app, Request $request): void
    {
        $useTime = self::getUseTime();
        $throughputRate = self::getThroughputRate($useTime);
        $memoryUsage = self::getMemoryUsage();
        $fileIncludeCount = self::getFileIncludeCount();
        $headLog = '[运行时间：' . $useTime . 's] [吞吐率：' . $throughputRate . '] [内存消耗：' . $memoryUsage . '] [文件加载：' . $fileIncludeCount . ']';
        Log::log($headLog);

        // 写入日志
        Log::writeAll();
    }

    /**
     * 计算运行耗时
     *
     * @return float
     */
    private static function getUseTime()
    {
        return number_format((microtime(true) - START_TIME), 6);
    }

    /**
     * 计算当前访问的吞吐率情况
     *
     * @param float $useTime 运行耗时
     * @return string
     */
    private static function getThroughputRate($useTime)
    {
        return number_format(1 / $useTime, 2) . 'req/s';
    }

    /**
     * 获取内存消耗
     *
     * @return string
     */
    private static function getMemoryUsage()
    {
        $result = ((memory_get_usage() - START_MEMORY) / 1024) . 'Kb';

        return $result;
    }

    /**
     * 获取文件加载数量
     *
     * @return int
     */
    private static function getFileIncludeCount()
    {
        return count(get_included_files());
    }
}