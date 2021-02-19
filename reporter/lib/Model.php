<?php

namespace reporter\lib;

use reporter\lib\Config;

class Model extends \PDO
{
    public function __construct()
    {
        // 读取数据库配置
        $databaseConfig = Config::all('database');
        $type = $databaseConfig['type'];
        $host = $databaseConfig['host'];
        $database = $databaseConfig['database'];
        $username = $databaseConfig['username'];
        $passwd = $databaseConfig['password'];

        $dsn = $type . ':host='. $host .';dbname=' . $database;

        try{
            parent::__construct($dsn, $username, $passwd);
        }catch (\PDOException $e){
            show($e->getMessage());
        }
    }
}