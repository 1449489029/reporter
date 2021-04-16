<?php

namespace reporter\lib\db;

use reporter\lib\db\Base;
use reporter\lib\db\DataStructure;
use reporter\lib\db\DBException;

// 验证器
class Vaildate extends Base
{

    /**
     * @var string 主表名称
     */
    public $mainTableName = '';

    /**
     * @var array 表别名 格式为:["表名A" => "别名A", "表名B" => "别名B", ...]
     */
    public $tableAlias = [];

    /**
     * @var array 表字段 格式为:["表名A" => ["字段a", "字段b", ...], "表名B" => ["字段a", "字段b", ...], ...]
     */
    public $tableFields = [];

    /**
     * 验证查询操作
     *
     * @param string $tableName 表名
     * @param string $alias 别名
     * @param array $joins 链表查询配置
     * @param array $fields 查询的字段
     * @param array $where 筛选的条件
     * @param array $orderBy 排序条件
     * @param array $groupBy 分组字段
     * @return void
     */
    public function select(string $tableName, string $alias, array $joins, array $fields, array $where, array $orderBy, array $groupBy)
    {
        // 记录主表名称
        $this->mainTableName = $tableName;

        // 别名
        if (!empty($alias)) {
            $this->tableAlias[$tableName] = $alias;
        }

        // 链表配置
        $this->analysisJoins($joins);

        // 字段
        $this->analysisFields($fields);

        // 筛选条件
        $this->analysisWhere($where);

        // 排序规则
        $this->analysisOrderBy($orderBy);

        // 分组字段
        $this->analysisGroupBy($groupBy);

        // 校验
        $this->verify();

        return;
    }


    /**
     * 验证写入操作
     *
     * @param string $tableName 表名
     * @param array $insertData 添加的数据
     * @return void
     */
    public function insert(string $tableName, array $insertData)
    {
        // 记录主表名称
        $this->mainTableName = $tableName;

        // 写入的数据
        $this->analysisInsertDatas($insertData);

        // 校验
        $this->verify();

        return;
    }

    /**
     * 验证更新操作
     *
     * @param string $tableName 表名
     * @param array $updateData 更新数据
     * @param array $where 筛选条件
     * @return void
     */
    public function update(string $tableName, array $updateData, array $where)
    {
        // 记录主表名称
        $this->mainTableName = $tableName;

        // 更新的数据
        $this->analysisUpdateData($updateData);

        // 筛选条件
        $this->analysisWhere($where);

        // 校验
        $this->verify();

        return;
    }

    /**
     * 验证更新操作
     *
     * @param string $tableName 表名
     * @param array $where 筛选条件
     * @return void
     */
    public function delete(string $tableName, array $where)
    {
        // 记录主表名称
        $this->mainTableName = $tableName;

        // 筛选条件
        $this->analysisWhere($where);

        // 校验
        $this->verify();

        return;
    }

    /**
     * 校验
     *
     * @return void
     */
    protected function verify()
    {
        $DataStructure = new DataStructure();

        // 验证主表是否存在
        $DataStructure->getTableField($this->mainTableName);

        // 反转表名与表别名的关联
        $aliasToTableName = array_flip($this->tableAlias);

        foreach ($this->tableFields as $tableName => $tableField) {
            // 判断当前表名是否是表别名
            if (isset($aliasToTableName[$tableName]) == true) {
                $tableName = $aliasToTableName[$tableName];
            }
            // 查询数据库中的表字段
            $newTableField = $DataStructure->getTableField($tableName);

            // 取"数据库中的表字段" 与 "操作的字段" 的 差集
            $notFindFields = array_diff($tableField, $newTableField);

            if (!empty($notFindFields)) {
                throw new DBException(current($notFindFields) . '字段不存在', 600);
            }
        }
        return;
    }

    /**
     * 解析字段
     *
     * @param array $fields 字段
     * @return void
     */
    protected function analysisFields(array $fields)
    {
        if (empty($fields) || (count($fields) == 1 && $fields[0] == "*")) {
            return;
        }
        foreach ($fields as $field) {
            if (strpos($field, '.') !== false) {
                $fieldData = explode('.', $field);
                $alias = $fieldData[0];
                $fieldName = $fieldData[1];
                if (strpos($fieldName, ' as ') !== false) {
                    $fieldName = explode(' as ', $fieldName);
                    $fieldName = $fieldName[0];
                }
            } else {
                $alias = isset($this->tableAlias[$this->mainTableName]) ? $this->tableAlias[$this->mainTableName] : $this->mainTableName;
                $fieldName = $field;
            }
            $alias = trim($alias);
            $fieldName = trim($fieldName);

            // 验证是否存在函数
            preg_match_all(self::RULE_FUNCTION, $fieldName, $matchResult);
            if (!empty($matchResult['function'])) {
                // 验证函数是否支持
                $this->verifyFunctionsName($matchResult['function']);

                // 提取真实的字段名
                preg_match(self::RULE_FIELD, $fieldName, $matchResult);
                if (!empty($matchResult['field'])) {
                    $fieldName = $matchResult['field'];
                } else {
                    throw new DBException($fieldName . '字段格式错误', 600);
                }
            }

            if (isset($this->tableFields[$alias])) {
                $this->tableFields[$alias][] = $fieldName;
            } else {
                $this->tableFields[$alias] = [$fieldName];
            }
        }

        return;
    }

    /**
     * 校验行数是否支持
     *
     * @param array $functionNames
     * @return void
     */
    public function verifyFunctionsName(array $functionNames)
    {
        if (empty($functionNames)) {
            return;
        }

        foreach ($functionNames as $functionName) {
            if (!in_array(strtoupper($functionName), self::FUNCTIONS)) {
                throw new DBException('暂不支持' . $functionName . '函数', 600);
            }
        }

        return;
    }

    /**
     * 解析链表配置
     *
     * @param array $joins 链表配置 格式为:[["链接方式", "链接的表名", [["链接条件字段A", "链接表达式", "链接条件字段B"], ...]], ...]
     * @return void
     */
    protected function analysisJoins(array $joins)
    {
        if (empty($joins)) {
            return;
        }

        // 格式为:[["链接方式", "链接的表名", [["链接条件字段A", "链接表达式", "链接条件字段B"], ...]], ...]
        foreach ($joins as $join) {
            $joinTableName = $join[1];
            // 有设置别名
            if (strpos($joinTableName, ' as ') !== false) {
                $joinTableNameData = explode(' as ', $joinTableName);
                $this->tableAlias[$joinTableNameData[0]] = $joinTableNameData[1];
            }

            $joinOnFieldsA = array_column($join[2], 0);
            $this->analysisFields($joinOnFieldsA);
            $joinOnFieldsB = array_column($join[2], 2);
            $this->analysisFields($joinOnFieldsB);
        }

        return;
    }

    /**
     * 解析筛选条件
     *
     * @param array $where 筛选条件 格式为:[["字段名", "表达式", "字段值"], ["字段名", "表达式", "字段值"], ...]
     * @return void
     */
    public function analysisWhere(array $where)
    {
        if (empty($where)) {
            return;
        }

        $whereFields = array_column($where, 0);
        $this->analysisFields($whereFields);

        return;
    }

    /**
     * 解析排序规则
     *
     * @param array $orderBy 格式为:[["排序字段", "排序方式"], ["排序字段", "排序方式"], ...]
     * @return void
     */
    public function analysisOrderBy(array $orderBy)
    {
        if (empty($orderBy)) {
            return;
        }

        $orderByFields = array_column($orderBy, 0);
        $this->analysisFields($orderByFields);

        return;
    }

    /**
     * 解析分组字段
     *
     * @param array $groupBy 格式为:["字段名A", "字段名B", ...]
     * @return void
     */
    public function analysisGroupBy(array $groupBy)
    {
        if (empty($orderBy)) {
            return;
        }

        $this->analysisFields($groupBy);

        return;
    }

    /**
     * 解析写入的数据字段
     *
     * @param array $insertDatas 格式为:[["字段名A" => "字段A值", "字段名B" => "字段名B值", ...]]
     * @return void
     */
    public function analysisInsertDatas(array $insertDatas)
    {
        if (empty($insertDatas)) {
            return;
        }

        $insertDatasFields = array_keys($insertDatas[0]);
        $this->analysisFields($insertDatasFields);

        return;
    }

    /**
     * 解析更新的数据字段
     *
     * @param array $updateData 格式为:["字段名A" => "字段A值", "字段名B" => "字段名B值", ...]
     * @return void
     */
    public function analysisUpdateData(array $updateData)
    {
        if (empty($updateData)) {
            return;
        }

        $updateDataFields = array_keys($updateData);
        foreach ($updateDataFields as $key => $fieldName) {
            // 正则匹配是否存在特殊字符
            preg_match(self::RULE_OPERATOR, $fieldName, $matchResult);
            if (!empty($matchResult['operator'])) {
                if (!in_array($matchResult['operator'], $this->operator)) {
                    throw new DBException('“' . $matchResult['operator'] . '”操作符不存在', 600);
                }
                $fieldName = str_replace('[' . $matchResult['operator'] . ']', '', $fieldName);
                $updateDataFields[$key] = $fieldName;
            }
        }
        $this->analysisFields($updateDataFields);

        return;
    }

}