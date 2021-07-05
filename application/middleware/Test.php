<?php

namespace application\middleware;

use reporter\lib\interfaces\Middleware;
use reporter\lib\Request;
use Closure;


class Test implements Middleware
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

        $nextResult = $next();

        return $nextResult;
    }
}