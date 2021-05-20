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
use reporter\lib\Config;
use application\common\library\AnalysisDatabase;
use application\common\library\Export;


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

    public function test()
    {
//        $this->assign('get', $get);
//        $this->assign('name', '显示视图');
//        $this->display('show_view.html');
        // 反转类信息
//        $class = new \ReflectionClass(\reporter\lib\Cache::class);
//
//        // 获取构造函数
//        $classConstructor = $class->getConstructor();
//
//        // 获取构造函数的参数
//        $params = $classConstructor->getParameters();
//
//        foreach ($params as $param) {
//            if ($paramClass = $param->getClass()) {
//                print_r($paramClass->name);
//            }
//        }

//        $Container = new Container();
//        $Container->bind(\reporter\lib\DB\Connect::class, \reporter\lib\DB\Connect::class, true);
//        $Object = $Container->make(\reporter\lib\DB\Connect::class);
//        $Object2 = $Container->make(\reporter\lib\DB\Connect::class);
//        print_r($Object);
//        print_r($Object2);

        header("Content-type: application/json");
        echo json_encode(["OK"]);
//        return json_encode(["OK"]);

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

    /**
     * 导出数据结构
     *
     */
    public function export_data_structure()
    {
        $tablesData = Db::query('show tables');

        // 定义一个容器,用于收集每个表解析出来的数据
        $datas = [];

        // 解析数据
        $AnalysisDatabase = new AnalysisDatabase();
        // 获取数据库名称
        $databaseName = Config::get('database_name', 'database');
        foreach ($tablesData as $key => $value) {
            $tableDetailData = Db::query('show create table ' . $value['Tables_in_' . $databaseName]);
            $datas[] = $AnalysisDatabase->analysisCreateTableSQL($tableDetailData);
        }

        // print_r($data);exit;
        // 导出数据字典
        $Export = new Export();
        $Export->exportDataTableStructure($datas);
    }
}
