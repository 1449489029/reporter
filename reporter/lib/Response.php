<?php

namespace reporter\lib;


class Response
{
    /**
     * @var mixed 响应的主体
     */
    protected $content;

    /**
     * @var int 响应状态码
     */
    protected $code;

    /**
     * @var array 响应的头部信息
     */
    protected $headers = [];

    /**
     * @var array 响应的Cookie值
     */
    protected $cookies = [];


    // 格式类型
    const CONTENT_TYPE_JSON = 'json';
    const CONTENT_TYPE_TEXT = 'text';


    public function __construct()
    {
    }

    /**
     * 设置头部信息
     *
     * @param string $headerField 头部信息字段名
     * @param string $headerValue 头部信息字段值
     * @return Response
     */
    public function setHeader(string $headerField, string $headerValue)
    {
        $this->headers[$headerField] = $headerValue;

        return $this;
    }

    /**
     * 设置Cookie
     *
     * @param string $key Cookie键名
     * @param string $value Cookie键值
     * @param int $expire 有效时间 [default=0]
     * @param string $path 路径 [default=""]
     * @param string $domain 域名 [default=""]
     * @param bool $secure 是否仅通过安全的 HTTPS 连接传送 [default=false]
     * @param bool $httponly 只能通过HTTP协议访问cookie [default=true]
     * @return Response
     */
    public function setCookie(string $key, string $value, int $expire = 0, string $path = '', string $domain = '', bool $secure = false, bool $httponly = true)
    {
        $this->cookies[$key] = [
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly
        ];

        return $this;
    }


    /**
     * 设置状态码
     *
     * @param int $code 状态码
     * @return Response
     */
    public function setCode(int $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * 设置响应主体
     *
     * @param mixed $content 响应主体
     * @return Response
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * 写入头部信息
     *
     * @return void
     */
    protected function withHeaders()
    {
        foreach ($this->headers as $field => $value) {
            header($field . ':' . $value);
        }
    }

    /**
     * 写入Cookie信息
     *
     * @return void
     */
    protected function withCookies()
    {
        foreach ($this->cookies as $cookieKey => $cookieValue) {
            // $name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false
            setcookie(
                $cookieKey,
                $cookieValue['Value'],
                $cookieValue['expire'],
                $cookieValue['path'],
                $cookieValue['domain'],
                $cookieValue['secure'],
                $cookieValue['httponly']
            );
        }
    }


    /**
     * 发送响应结果
     *
     * @return void
     */
    public function send()
    {
        $this->withHeaders();
        $this->withCookies();

        http_response_code($this->code);

        exit($this->content);
    }


    /**
     * 返回json以及请求头
     * @param array $data 数据
     * @param string $message 描述 [default='']
     * @param int $code 状态码 [default=200]
     */
    public static function jsonOutput($data, string $message = '', int $code = 200)
    {
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        header('Access-Control-Allow-Origin: *');//解决跨域
        header('Content-type: application/json');
        exit(json_encode([
            'data' => $data,
            'code' => $code,
            'message' => $message
        ]));
    }
}
