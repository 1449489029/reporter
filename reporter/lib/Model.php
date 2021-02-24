<?php

namespace reporter\lib;

use reporter\lib\Config;

class Model extends \Medoo\Medoo
{
    public function __construct()
    {
        // 读取数据库配置
        $databaseConfig = Config::all('database');

        try {
            parent::__construct($databaseConfig);
        } catch (\PDOException $e) {
            show($e->getMessage());
        }
    }

    /**
     * @var string 表名
     */
    protected $table = '';

    /**
     * 获取所有数据
     *
     * @return array
     */
    public function lists()
    {
        $result = $this->select($this->table, '*');

        return $result;
    }

    /**
     * 获取单条数据
     *
     * @param int $id ID
     * @return array
     */
    public function getOne($id)
    {
        $result = $this->select($this->table, '*', [
            'id' => $id
        ]);

        return $result;
    }

    /**
     * 更新数据
     *
     * @param int $id ID
     * @param array $updateData 更新的数据
     * @return int
     */
    public function setOne($id, $updateData)
    {
        $result = $this->update($this->table, $updateData, [
            'id' => $id
        ]);

        $result = $result->rowCount();

        return $result;
    }

    /**
     * 删除数据
     *
     * @param int $id ID
     * @return int
    */
    public function delOne($id)
    {
        $result = $this->delete($this->table, [
            'id' => $id
        ]);

        $result = $result->rowCount();

        return $result;
    }


}