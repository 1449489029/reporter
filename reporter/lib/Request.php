<?php

namespace reporter\lib;

/**
 * 请求类
 */
class Request
{
    /**
     * @var string 客户端IP
     */
    public $clientIP;

    /**
     * @var string 请求的域名
     */
    public $domain;

    /**
     * @var string 请求的URI
     */
    public $uri;

    /**
     * @var string 请求方式
     */
    public $method;


    public function __construct()
    {
        $this->clientIP = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $this->uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $this->domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
    }

    /**
     * 获取头部信息
     *
     * @return array
     */
    public function getHeader()
    {
        if (function_exists('apache_request_headers') && $result = apache_request_headers()) {
            $header = $result;
        } else {
            $header = [];
        }

        return $header;
    }

    /**
     * 获取请求参数
     *
     * @return array
     */
    public function getParams()
    {
        if ($this->method == 'POST' || $this->method == 'PUT') {
            $result = $_POST;
        } else {
            $result = $_GET;
        }

        unset($result['s']);

        return $result;
    }

}