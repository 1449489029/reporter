<?php

namespace application\index\controller;

use mysql_xdevapi\Exception;
use reporter\lib\Model;
use reporter\lib\Controller;
use reporter\lib\Log;
use application\index\model\Demo;

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

    public function show_view()
    {
        $this->assign('name', '显示视图');
        $this->display('/index/show_view.html');
    }

    public function write()
    {
        $Log = Log::init();

        $Log::record(['测试写入日志1']);
        $Log::record('测试写入日志2');
        $Log::record('测试写入日志3');
        $Log::record('测试写入日志4');
        throw new \Exception('ddd');

        $a = apache_request_headers();
        print_r($a);

        print_r($_SERVER);
    }
}