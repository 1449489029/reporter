<?php

namespace reporter\lib\interfaces;

// 缓存接口
interface Cache
{
    /**
     * 获取缓存链接
     *
     * @param string $type 缓存类型 [default = '']
     * @return Object
     */
    public static function getConnect(string $type = '');
}