<?php

namespace reporter\lib;

use Closure;
use ReflectionClass;

// 服务容器类
class Container
{
    /**
     * @var array 已绑定的服务列表
     */
    protected $bindings = [];

    /**
     * @var array 服务别名关联
     */
    protected $aliases = [];

    /**
     * @var array 已实例化的单例服务
     */
    protected $instances = [];

    /**
     * @var Container|NULL 实例
     */
    public static $that;


    private function __construct()
    {
    }

    /**
     * 获取实例
     *
     * @return Container
     */
    public static function instance()
    {
        if (!self::$that instanceof Container) {
            self::$that = new self();
        }

        return self::$that;
    }


    /**
     * 绑定服务
     *
     * @param string $name 服务名称
     * @param string|Closure|null $server 具体的服务 [default=null]
     * @param bool $isSingleton 是否单例 [default=false]
     * @return void
     */
    public function bind(string $name, $server = null, bool $isSingleton = false): void
    {
        if (is_null($server)) {
            $server = $name;
        }

        // 如果不是闭包类型的
        if (!$server instanceof Closure) {
            // 创建一个闭包
            $server = $this->getClosure($name, $server);
        }

        $this->bindings[$name] = compact('server', 'isSingleton');

        return;
    }

    /**
     * 是否已绑定
     *
     * @param string $name 服务名称
     * @return bool
     */
    protected function isBind(string $name): bool
    {
        return isset($this->bindings[$name]);
    }

    /**
     * 创建闭包
     *
     * @param string $name 服务名称
     * @param string $server 具体的服务
     * @return Closure
     */
    protected function getClosure(string $name, string $server): Closure
    {
        return function ($container) use ($name, $server) {
            if ($name == $server) {
                return $container->build($name, $server);
            }

            return $container->make($server);
        };
    }

    /**
     * 给服务名称设置别名
     *
     * @param string $name 服务名称
     * @param string $alias 服务别名
     * @return bool
     */
    public function alias(string $name, string $alias): bool
    {
        $this->aliases[$alias] = $name;

        return true;
    }

    /**
     * 验证给定的名称是否是别名
     *
     * @param string $name 服务名称
     * @return bool
     */
    protected function isAlias(string $name): bool
    {
        return isset($this->aliases[$name]);
    }


    /**
     * 获取别名对应的服务名称
     *
     * @param string $alias 服务别名
     * @return string
     */
    protected function getAliasToServerName(string $alias): string
    {
        return $this->aliases[$alias];
    }

    /**
     * 查找服务
     *
     * @param string $name 服务名称
     * @return mixed
     */
    public function make(string $name)
    {
        // 判断是否是别名
        if ($this->isAlias($name)) {
            $name = $this->getAliasToServerName($name);
        }

        // 如果当前是单例模式 并且 已经实例化过了的话
        if (isset($this->instances[$name])) {
            // 直接返回这个实例
            return $this->instances[$name];
        }

        // 如果当前服务已经绑定过
        if ($this->isBind($name)) {
            // 获取对应的服务
            $server = $this->getServer($name);
        } else {
            $server = $name;
        }

        // 构建
        if ($name == $server || $server instanceof Closure) {
            $server = $this->build($name, $server);
        } else {
            $server = $this->make($name);
        }


        // 判断当前服务是否是单例
        if ($this->isSingleton($name)) {
            $this->instances[$name] = $server;
        }


        return $server;
    }

    /**
     * 获取服务
     *
     * @param string $name 服务名称
     * @return Closure
     */
    protected function getServer(string $name): Closure
    {
        return $this->bindings[$name]['server'];
    }

    /**
     * 判断给定的服务是否是单例
     *
     * @param string $name 服务名称
     * @return bool
     */
    public function isSingleton(string $name): bool
    {
        return isset($this->bindings[$name]) && $this->bindings[$name]['isSingleton'];
    }


    /**
     * 构建服务
     *
     * @param string $name 服务名称
     * @param string|Closure $server 具体的服务
     * @return mixed
     */
    public function build(string $name, $server)
    {
        if ($server instanceof Closure) {
            return $server($this);
        }

        // 反转类信息
        $class = new ReflectionClass($server);

        // 获取构造函数
        $classConstructor = $class->getConstructor();

        // 获取构造函数的参数
        $classConstructorParams = $classConstructor->getParameters();

        // 如果为空，说明没有定义构造函数，也就是没有参数依赖，可以直接实例化
        if (empty($classConstructor)) {
            return $class->newInstanceArgs([]);
            // 如果参数为空
        } else if (count($classConstructorParams) == 0) {
            return $class->newInstanceArgs([]);
        }
        $classConstructorParamsRely = [];
        foreach ($classConstructorParams as $classConstructorParam) {
            if ($paramClass = $classConstructorParam->getClass()) {
                // 获取这个参数依赖
                $classConstructorParamsRely[] = $this->make($paramClass->name);
            }
        }

        return $class->newInstanceArgs($classConstructorParamsRely);
    }

}