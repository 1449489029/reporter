<?php

use reporter\lib\Env;

return [
    // 数据库类型
    'database_type' => Env::get('database.database_type', 'mysql'),
    // 服务器地址
    'server' => Env::get('database.server', 'localhost'),
    // 数据库名
    'database_name' => Env::get('database.database_name', 'reporter'),
    // 用户名
    'username' => Env::get('database.username', 'reporter'),
    // 密码
    'password' => Env::get('database.password', ''),
    // 端口
    'port' => Env::get('database.port', 3306),
    // 数据库默认编码
    'charset' => Env::get('database.charset', 'utf8mb4'),
    // 排序的规则
    'collation' => Env::get('database.collation', 'utf8mb4_general_ci'),
    // 数据库前缀
    'prefix' => Env::get('database.prefix', 'r_')
];