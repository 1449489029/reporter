<?php

namespace application\index\controller;

use mysql_xdevapi\Exception;
use reporter\lib\Model;
use reporter\lib\Controller;
use reporter\lib\Log;
use application\index\model\Demo;
use reporter\lib\db\DB;
use reporter\lib\Request;
use reporter\lib\File;
use reporter\lib\UploadFile;
use reporter\lib\Container;

class Index extends Controller
{
    public function index()
    {
        show('<h1>Hello world</h1>');
    }

    public function test_medoo()
    {
        $Demo = new Demo();
        // 查询单条数据
        $result = $Demo->getOne(2);

        show($result);

        // 更新单条数据
        $result = $Demo->setOne(2, [
            'name' => 'reporter-2021-02-24-中午'
        ]);

        show($result);

        // 删除单条数据
        $result = $Demo->delOne(3);

        show($result);
    }

    public function test_db()
    {
        $data = DB::name('role_planet')->where([["WorldID", '=', 24]])->select();
        print_r($data);
    }

    public function show_view()
    {
        $this->assign('name', '显示视图');
        $this->display('show_view.html');
    }

    public function write()
    {
        Log::record(['测试写入日志1']);
        Log::record('测试写入日志2');
        Log::record('测试写入日志3');
        Log::record('测试写入日志4');
        throw new \Exception('ddd');

        $a = apache_request_headers();
        print_r($a);

        print_r($_SERVER);
    }

    public function test(Request $request, $get)
    {
        $this->assign('get', $get);
        $this->assign('name', '显示视图');
        $this->display('show_view.html');
    }

    /**
     * 测试文件基础类
     *
     */
    public function testFile()
    {
        $File = new File(ROOT_PATH . '/.env');
        print_r($File->getRealPath());
    }

    /**
     * 测试上传文件
     */
    public function testUploadFile(Request $request, UploadFile $uploadFile)
    {
        if ($request->isGet()) {
            $this->assign('name', '测试上传文件');
            $this->display('upload_file.html');
        } else if ($request->isPost()) {
            $server = Container::make('UploadFile');
            $file = Container::make('UploadFile')->getFile('file')->move(UPLOAD_PATH);
            echo $file;
        }
    }
}

//设计容器类，容器类装实例或提供实例的回调函数
class Container
{        //用于装提供实例的回调函数，真正的容器还会装实例等其他内容
    //从而实现单例等高级功能
    protected $bindings = [];          //绑定接口和生成相应实例的回调函数

    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (!$concrete instanceof Closure) {
            //如果提供的参数不是回调函数，则产生默认的回调函数
            $concrete = $this->getClosure($abstract, $concrete);
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    //默认生成实例的回调函数
    protected function getClosure($abstract, $concrete)
    {
        //生成实例的回调函数，$c一般为IoC容器对象，在调用回调生成实例时提供
        //即build函数中的
        $concrete($this)
        return function ($c) use ($abstract, $concrete) {
            $method = ($abstract == $concrete) ? 'build' : 'make';
            //调用的是容器的build或make方法生成实例
            return $c->$method($concrete);
        };
    }

    //生成实例对象，首先解决接口和要实例化类之间的依赖关系
    public function make($abstract)
    {
        $concrete = $this->getConcrete($abstract);
        if ($this->isBuildable($concrete, $abstract)) {
            $object = $this->build($concrete);
        } else {
            $object = $this->make($concrete);
        }
        return $object;
    }

    protected function isBuildable($concrete, $abstract)
    {
        return $concrete === $abstract || $concrete instanceof Closure;
    }

    //获取绑定的回调函数
    protected function getConcrete($abstract)
    {
        if (!isset($this->bindings[$abstract])) {
            return $abstract;
        }

        return $this->bindings[$abstract]['concrete'];
    }

    //实例化对象
    public function build($concrete)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        $reflector = new ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            echo $message = "Target [$concrete] is not instantiable.";
        }
        $constructor =
            $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }
        $dependencies = $constructor->getParameters();
        $instances = $this->getDependencies($dependencies);
        return $reflector->newInstanceArgs($instances);
    }

    //解决通过反射机制实例化对象时的依赖
    protected function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if (is_null($dependency)) {
                $dependencies[] = NULL;
            } else {
                $dependencies[] = $this->resolveClass($parameter);
            }
        }
        return (array)$dependencies;
    }

    protected function resolveClass(ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name);
    }
}

class Traveller
{
    protected $trafficTool;

    public function __construct(Visit $trafficTool)
    {
        $this->trafficTool = $trafficTool;
    }

    public function visitTibet()
    {
        $this->trafficTool->go();
    }
}

//实例化IoC容器
$app = new Container();
//完成容器的填充
$app->bind("Visit", "Train");
$app->bind("traveller", "Traveller");
//通过容器实现依赖注入，完成类的实例化
$tra = $app->make("traveller");
$tra->visitTibet();