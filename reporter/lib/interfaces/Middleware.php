<?php

namespace reporter\lib\interfaces;

use reporter\lib\Request;
use Closure;

// 中间件接口类
interface Middleware
{
    /**
     * 处理传入的请求
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next);

}