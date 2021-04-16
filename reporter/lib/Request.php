<?php

namespace reporter\lib;

// 请求类
class Request
{
    // 请求方式
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var array POST参数
     */
    protected $post = [];

    /**
     * @var array GET参数
     */
    protected $get = [];

    /**
     * @var array FILES参数
     */
    protected $files = [];

    /**
     * @var array 服务数据
     */
    protected $server = [];

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
        $this->post = $_POST;
        $this->get = $_GET;
        $this->files = $_FILES;
        $this->server = $_SERVER;

        $this->clientIP = isset($this->server['REMOTE_ADDR']) ? $this->server['REMOTE_ADDR'] : '';
        $this->uri = isset($this->server['REQUEST_URI']) ? $this->server['REQUEST_URI'] : '';
        $this->domain = isset($this->server['HTTP_HOST']) ? $this->server['HTTP_HOST'] : '';
        $this->method = isset($this->server['REQUEST_METHOD']) ? $this->server['REQUEST_METHOD'] : '';
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
        if ($this->method == self::METHOD_POST || $this->method == self::METHOD_PUT) {
            $result = $this->post;
        } else {
            $result = $this->get;
        }

        unset($result['s']);

        return $result;
    }

    /**
     * 本次是否是POST请求
     *
     * @return bool
     */
    public function isPost()
    {
        if ($this->server['REQUEST_METHOD'] == self::METHOD_POST) {
            return true;
        }

        return false;
    }

    /**
     * 本次是否是GET请求
     *
     * @return bool
     */
    public function isGet()
    {
        if ($this->server['REQUEST_METHOD'] == self::METHOD_GET) {
            return true;
        }

        return false;
    }

    /**
     * 获取Post传入值
     *
     * @param string $name 字段名
     * @param string $default 默认值 [default='']
     * @return
     */
    public function post(string $name, string $default = '')
    {
        if (isset($this->post[$name]) == true) {
            $value = $this->post[$name];
        } else {
            $value = $default;
        }

        return $value;
    }

    /**
     * 获取GET传入值
     *
     * @param string $name 字段名
     * @param string $default 默认值 [default='']
     * @return
     */
    public function get(string $name, string $default = '')
    {
        if (isset($this->get[$name]) == true) {
            $value = $this->get[$name];
        } else {
            $value = $default;
        }

        return $value;
    }

    /**
     * 接收上传的文件
     *
     * @param string $name 字段名
     * @return Object|bool
     */
    public function file(string $name)
    {
        if (empty($name)) {
            return false;
        }

        $name = explode('.', $name);
        $count = count($name);

        $file = $this->files;
        for ($i = 0; $i < $count; $i++) {
            if (isset($file[$name[$i]]) == false) {
                return false;
            }

            $file = $file[$name[$i]];
        }


        return $file;
    }

    /**
     * 判断是否有指定的传入值
     *
     * @param string $name 字段名
     * @return
     */
    public function has(string $name)
    {
        if (isset($this->post[$name]) == true) {
            return true;
        } else if (isset($this->get[$name]) == true) {
            return true;
        }

        return false;
    }

}