<?php

namespace reporter\lib\db;

use reporter\lib\db\Base;
use reporter\lib\Config;

// 数据库连接
class Connect extends \PDO
{
    /**
     * @var object PDO对象
     */
    protected static $pdo = NULL;

    public function __construct()
    {
        $databaseConfig = Config::all('database');
        // 数据库类型
        $databaseType = $databaseConfig['database_type'];
        // 数据库主机名
        $host = $databaseConfig['server'];
        // 使用的数据库
        $dbName = $databaseConfig['database_name'];
        // 数据库连接用户名
        $username = $databaseConfig['username'];
        // 对应的密码
        $password = $databaseConfig['password'];
        $dsn = "$databaseType:host=$host;dbname=$dbName";
        parent::__construct($dsn, $username, $password, []);
    }

    /**
     * 获取数据库连接
     *
     * @return \PDO
     */
    public static function getConnect()
    {
        if (!self::$pdo instanceof \PDO) {
            self::$pdo = new self();
        }

        return self::$pdo;
    }


}