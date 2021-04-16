<?php

namespace reporter\lib\db;

use reporter\lib\db\DB;
use reporter\lib\db\Base;
use reporter\lib\Cache;

// 数据结构
class DataStructure extends Base
{
    /**
     * 获取表字段
     *
     * @param string $tableName 表名
     * @return array
     */
    public function getTableField($tableName): array
    {
        $Cache = Cache::getConnect();
        $keyName = 'table_field_' . $tableName;
        $result = $Cache->get($keyName);

        if (empty($result)) {
            // 获取表字段
            $result = DB::name($tableName)->getTableField();
            // 提取出字段名称
            $result = array_column($result, 'Field');
            // 保存到缓存中
            $Cache->set($keyName, $result);
        }


        return $result;
    }
}