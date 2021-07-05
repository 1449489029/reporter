<?php

namespace reporter\lib\middleware;

use Closure;
use reporter\lib\Log;
use reporter\lib\Request;
use reporter\lib\Route;

class BeforeRun
{
    /**
     * 处理传入的请求
     *
     * @param Request $request 请求类
     * @param Closure $next 下一步
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::log('---------------------------------------------------------------');
        $headLog = '[ ' . date(DATE_ATOM) . ' ] ' . $request->clientIP . ' ' . $request->method . ' ' . $request->domain . $request->uri;
        Log::log($headLog);

        $Route = new Route($request);

        $routeData = [
            $Route->model,
            $Route->controller,
            $Route->action
        ];
        $routeLog = '[ ROUTE ] ' . print_r($routeData, true);
        Log::record($routeLog);
        $headerLog = '[ HEADER ] ' . print_r($request->getHeader(), true);
        Log::record($headerLog);
        $paramsLog = '[ PARAM ] ' . print_r($request->getParams(), true);
        Log::record($paramsLog);


        return $next();
    }
}