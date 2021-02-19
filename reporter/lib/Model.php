<?php

namespace reporter\lib;

class Model extends \PDO
{
    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=reporter';
        $username = 'reporter';
        $passwd = 'reporter';
        try{
            parent::__construct($dsn, $username, $passwd);
        }catch (\PDOException $e){
            show($e->getMessage());
        }
    }
}