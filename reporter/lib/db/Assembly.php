<?php

namespace reporter\lib\db;

use reporter\lib\db\DB;
use reporter\lib\db\Base;
use reporter\lib\db\Vaildate;

// SQL组装
class Assembly extends Base
{


    /**
     * 查询单条数据
     *
     * @param string $tableName 表名
     * @param string $alias 别名
     * @param array $joins 链表查询配置
     * @param array $fields 查询的字段
     * @param array $where 筛选的条件
     * @param array $orderBy 排序条件
     * @param array $groupBy 分组字段
     * @return string
     */
    public function find($tableName, $alias, $joins, $fields, $where, $orderBy, $groupBy): string
    {
        // 校验
        $Vaildate = new Vaildate();
        $Vaildate->select($tableName, $alias, $joins, $fields, $where, $orderBy, $groupBy);

        $joinsString = self::analysisJoin($joins);
        $fieldString = self::analysisField($fields);
        $whereString = self::analysisWhere($where);
        $orderByString = self::analysisOrderBy($orderBy);
        $groupByString = self::analysisGroupBy($groupBy);
        $tableName = DB::getRealTableName($tableName);

        if (!empty($alias)) {
            $sql = 'SELECT ' . $fieldString . ' FROM `' . $tableName . '` as ' . $alias;
        } else {
            $sql = 'SELECT ' . $fieldString . ' FROM `' . $tableName . '`';
        }

        // 链表操作
        if (!empty($joinsString)) {
            $sql .= $joinsString;
        }
        // 筛选条件
        if (!empty($whereString)) {
            $sql .= $whereString;
        }
        // 排序
        if (!empty($orderByString)) {
            $sql .= $orderByString;
        }
        // 分组
        if (!empty($groupByString)) {
            $sql .= $groupByString;
        }

        $sql .= ' LIMIT 1';

        return $sql;
    }

    /**
     * 查询多条数据
     *
     * @param string $tableName 表名
     * @param string $alias 别名
     * @param array $joins 链表查询配置
     * @param array $fields 查询的字段
     * @param array $where 筛选的条件
     * @param array $orderBy 排序条件
     * @param array $groupBy 分组字段
     * @param array $limit 分页
     * @return string
     */
    public function select($tableName, $alias, $joins, $fields, $where, $orderBy, $groupBy, $limit): string
    {
        // 校验
        $Vaildate = new Vaildate();
        $Vaildate->select($tableName, $alias, $joins, $fields, $where, $orderBy, $groupBy);


        $joinsString = self::analysisJoin($joins);
        $fieldString = self::analysisField($fields);
        $whereString = self::analysisWhere($where);
        $orderByString = self::analysisOrderBy($orderBy);
        $groupByString = self::analysisGroupBy($groupBy);
        $limitString = self::analysisLimit($limit);
        $tableName = DB::getRealTableName($tableName);


        if (!empty($alias)) {
            $sql = 'SELECT ' . $fieldString . ' FROM `' . $tableName . '` as ' . $alias;
        } else {
            $sql = 'SELECT ' . $fieldString . ' FROM `' . $tableName . '`';
        }

        // 链表操作
        if (!empty($joinsString)) {
            $sql .= $joinsString;
        }
        // 筛选条件
        if (!empty($whereString)) {
            $sql .= $whereString;
        }
        // 排序
        if (!empty($orderByString)) {
            $sql .= $orderByString;
        }
        // 分组
        if (!empty($groupByString)) {
            $sql .= $groupByString;
        }

        // 分页
        if (!empty($limitString)) {
            $sql .= $limitString;
        }

        return $sql;
    }

    /**
     * 添加单条数据
     *
     * @param string $tableName 表名
     * @param array $insertData 添加的数据
     * @return string
     */
    public function insert($tableName, array $insertData): string
    {
        // 校验
        $Vaildate = new Vaildate();
        $Vaildate->insert($tableName, [$insertData]);

        $tableName = DB::getRealTableName($tableName);
        $insertCount = count($insertData);
        $fields = array_keys($insertData);
        $fieldsString = self::analysisField($fields);
        $valueString = [];
        for ($i = 0; $i < $insertCount; $i++) {
            $valueString[] = '?';
        }
        $valueString = implode(', ', $valueString);
        $sql = 'INSERT INTO `' . $tableName . '`(' . $fieldsString . ') VALUES(' . $valueString . ')';


        return $sql;
    }

    /**
     * 添加多条数据
     *
     * @param string $tableName 表名
     * @param array $insertDatas 添加的数据
     * @return string
     */
    public function insertAll($tableName, array $insertDatas): string
    {
        // 校验
        $Vaildate = new Vaildate();
        $Vaildate->insert($tableName, $insertDatas);

        $tableName = DB::getRealTableName($tableName);
        $insertCount = count($insertDatas);
        $fields = array_keys($insertDatas[0]);
        $fieldsString = self::analysisField($fields);
        $valueStrings = [];
        for ($i = 0; $i < $insertCount; $i++) {
            $fieldCount = count($insertDatas[$i]);
            $valueString = [];
            for ($o = 0; $o < $fieldCount; $o++) {
                $valueString[] = '?';
            }
            $valueStrings[] = '(' . implode(', ', $valueString) . ')';
        }
        $valueStrings = implode(', ', $valueStrings);


        $sql = 'INSERT INTO `' . $tableName . '`(' . $fieldsString . ') VALUES' . $valueStrings;

        return $sql;
    }

    /**
     * 更新数据
     *
     * @param string $tableName 表名
     * @param array $updateData 更新数据
     * @param array $where 筛选条件
     * @return string
     */
    public function update($tableName, array $updateData, $where): string
    {
        // 校验
        $Vaildate = new Vaildate();
        $Vaildate->update($tableName, $updateData, $where);

        $tableName = DB::getRealTableName($tableName);
        // 解析更新的数据
        $updateDataString = [];
        foreach ($updateData as $fieldName => $fieldValue) {
            // 正则匹配是否设置操作符
            preg_match(self::RULE_OPERATOR, $fieldName, $matchResult);
            if (!empty($matchResult['operator'])) {
                $fieldName = str_replace('[' . $matchResult['operator'] . ']', '', $fieldName);
                $updateDataString[] = '`' . $fieldName . '` = `' . $fieldName . '` ' . $matchResult['operator'] . ' ?';
            } else {
                $updateDataString[] = '`' . $fieldName . '` = ?';
            }
        }
        $updateDataString = implode(', ', $updateDataString);

        $sql = 'UPDATE `' . $tableName . '` SET ' . $updateDataString;

        // 解析筛选条件
        $whereString = self::analysisWhere($where);
        if (!empty($whereString)) {
            $sql .= $whereString;
        }

        return $sql;
    }

    /**
     * 删除数据
     *
     * @param string $tableName 表名
     * @param array $where 筛选条件
     * @return string
     */
    public function delete($tableName, array $where): string
    {
        // 校验
        $Vaildate = new Vaildate();
        $Vaildate->delete($tableName, $where);

        $tableName = DB::getRealTableName($tableName);
        $sql = 'DELETE FROM `' . $tableName . '`';

        // 解析筛选条件
        $whereString = self::analysisWhere($where);
        if (!empty($whereString)) {
            $sql .= $whereString;
        }

        return $sql;
    }

    /**
     * 获取表字段
     *
     * @param string $tableName 表名
     * @return string
     */
    public function desc(string $tableName): string
    {
        $tableName = DB::getRealTableName($tableName);
        if (empty($tableName)) {
            return '';
        }

        $sql = 'DESC `' . $tableName . '`';

        return $sql;
    }

    /**
     * 自增
     *
     * @param string $tableName 表名
     * @param array $where 筛选条件
     * @param string $fieldName 字段名
     * @param int $increment 增加的值
     * @return string
     */
    public function increment(string $tableName, array $where, string $fieldName, int $increment): string
    {
        $updateData = [
            $fieldName . '[+]' => $increment
        ];
        $sql = $this->update($tableName, $updateData, $where);

        return $sql;
    }

    /**
     * 自减
     *
     * @param string $tableName 表名
     * @param array $where 筛选条件
     * @param string $fieldName 字段名
     * @param int $decrement 增加的值
     * @return string
     */
    public function decrement(string $tableName, array $where, string $fieldName, int $decrement): string
    {
        $updateData = [
            $fieldName . '[-]' => $decrement
        ];
        $sql = $this->update($tableName, $updateData, $where);

        return $sql;
    }

    /**
     * 解析查询的字段
     *
     * @param array $fields 查询的字段 格式为:['字段1', '字段2', ...]
     * @return string
     */
    protected static function analysisField($fields): string
    {
        if (empty($fields) || (count($fields) == 1 && $fields[0] == '*')) {
            return '*';
        }

        $fieldString = [];
        foreach ($fields as $field) {
            // 验证是否存在别名
            $fieldAlias = '';
            if (strpos($field, ' as ') !== false) {
                $field = explode(' as ', $field);
                $fieldAlias = '`' . $field[1] . '`';
                $field = $field[0];
            }

            // 验证是否存在
            preg_match_all(self::RULE_FUNCTION, $field, $matchResult);
            // 如果存在函数
            if (!empty($matchResult['function'])) {
                preg_match(self::RULE_FIELD, $field, $matchResult);
                if (!empty($matchResult['field'])) {
                    $field = str_replace($matchResult['field'], '`' . $matchResult['field'] . '`', $field);
                }
            } else {
                $field = '`' . $field . '`';
            }

            if (!empty($fieldAlias)) {
                $fieldString[] = $field . ' as ' . $fieldAlias;
            } else {
                $fieldString[] = $field;
            }
        }

        $fieldString = implode(', ', $fieldString);

        return $fieldString;
    }

    /**
     * 解析链表操作
     *
     * @param array $joins 链表的配置 格式为:[["链接方式", "链接的表名", [["链接条件字段A", "链接表达式", "链接条件字段B"], ...]], ...]
     * @return string
     */
    protected static function analysisJoin(array $joins): string
    {
        if (empty($joins)) {
            return '';
        }

        $joinsSQL = [];
        foreach ($joins as $join) {
            // 将链接的条件转换为字符串格式
            $onString = [];
            foreach ($join[2] as $on) {
                $onFieldA = str_replace('.', '`.`', $on[0]);
                $onFieldA = '`' . $onFieldA . '`';

                $onFieldB = str_replace('.', '`.`', $on[2]);
                $onFieldB = '`' . $onFieldB . '`';

                $onString[] = $onFieldA . ' ' . $on[1] . ' ' . $onFieldB;
            }
            $onString = implode(' AND ', $onString);

            // 判断是否增加表名 ################################
            // 存在别名
            if (strpos($join[1], ' as ') !== false) {
                $joinTableName = explode(' as ', $join[1]);
                $joinTableAlias = $joinTableName[1];
                $joinTableName = $joinTableName[0];
            } else { // 不存在别名
                $joinTableAlias = $join[1];
                $joinTableName = $join[1];
            }
            // 判断是否增加表名 ################################
            $joinsSQL[] = $join[0] . ' JOIN `' . DB::getRealTableName($joinTableName) . '` as ' . $joinTableAlias . ' ON ' . $onString;
        }

        $joinsSQL = ' ' . implode(' ', $joinsSQL);

        return $joinsSQL;

    }

    /**
     * 解析筛选条件
     *
     * @param array $where 筛选的条件 格式为:[["字段名", "表达式", "字段值"], ["字段名", "表达式", "字段值"], ...]
     * @return string
     */
    protected static function analysisWhere($where): string
    {
        if (empty($where)) {
            return '';
        }

        $whereString = [];
        foreach ($where as $key => $value) {
            $whereString[] = '`' . $value[0] . '` ' . $value[1] . ' ?';
        }
        $whereString = ' WHERE ' . implode(' AND ', $whereString);

        return $whereString;
    }

    /**
     * 解析排序条件
     *
     * @param array $orderBy 排序条件 格式为:[["排序字段", "排序方式"], ["排序字段", "排序方式"], ...]
     * @return string
     */
    protected static function analysisOrderBy(array $orderBy): string
    {
        if (empty($orderBy)) {
            return '';
        }

        $orderByString = [];
        foreach ($orderBy as $key => $value) {
            $orderByString[] = '`' . $value[0] . '` ' . $value[1];
        }
        $orderByString = ' ORDER BY ' . implode(', ', $orderByString);

        return $orderByString;
    }


    /**
     * 解析分组操作
     *
     * @param array $groupBy 分组条件 格式为:["分组字段A", "分组字段B", ...]
     * @return string
     */
    protected static function analysisGroupBy(array $groupBy): string
    {
        if (empty($groupBy)) {
            return '';
        }

        $groupByString = [];
        foreach ($groupBy as $field) {
            $groupByString[] = '`' . $field . '`';
        }

        $groupByString = ' GROUP BY ' . implode(', ', $groupByString);

        return $groupByString;
    }

    /**
     * 解析分页
     *
     * @param array $limit 分页 格式为:["起始行数", "长度"]
     * @return string
     */
    protected static function analysisLimit(array $limit): string
    {
        if (empty($limit)) {
            return '';
        }

        $limitString = ' LIMIT ' . $limit[0] . ', ' . $limit[1];

        return $limitString;
    }

}