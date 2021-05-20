<?php

namespace application\common\library;

// 解析数据库结构
class AnalysisDatabase
{
    /**
     * 解析创建表的SQL
     * 
     * @param array     $createTableData 创建表SQL的信息(mysql中show create table `table_name`返回的数据)
     * @return array
     */
    public function analysisCreateTableSQL($createTableData)
    {
        $createTableSQL = explode("\n", $createTableData[0]['Create Table']);
        // 获取表注释
        $createTableSQLEnd = end($createTableSQL);
        $createTableSQLEnd = explode(' ', $createTableSQLEnd);

        unset($createTableSQLEnd[0]);
        $tableInfo = [];
        foreach ($createTableSQLEnd as $value) {
            $newValue = explode('=', $value);
            if (isset($newValue[0]) == true) {
                $tableInfo[$newValue[0]] = isset($newValue[1]) ? $newValue[1] : '';
            }
        }
        $tableInfo['NAME'] = $createTableData[0]['Table'];
        if (!empty($tableInfo['COMMENT'])) {
            $tableInfo['COMMENT'] = trim($tableInfo['COMMENT'], "'");
        } else {
            $tableInfo['COMMENT'] = '';
        }



        // 解析表字段
        $tableFieldsData = [];
        // 解析表索引
        $tableIndexData = [];
        $i = 0;
        foreach ($createTableSQL as $key => $value) {

            // 过滤非定义字段的SQL
            $newValue = explode(' ', $value);
            $rule = '/^`.*`?$/';
            // 解析表字段
            if (empty($newValue[0]) && empty($newValue[1]) && preg_match($rule, $newValue[2])) {


                // 是否允许为NULL,默认为true
                $tableFieldsData[$i]['null'] = true;

                // 判断是否允许为NULL
                $ifNUllRule = '/NOT NULL/';
                if (preg_match($ifNUllRule, $value)) {
                    $tableFieldsData[$i]['null'] = false;
                }

                // 获取字段名
                $tableFieldsData[$i]['field'] = $newValue[2];
                $tableFieldsData[$i]['field'] = str_replace('`', '', $tableFieldsData[$i]['field']);

                // 获取字段类型
                $tableFieldsData[$i]['type'] = $newValue[3];

                // 获取默认值
                $tableFieldsData[$i]['detailt_status'] = false;
                $tableFieldsData[$i]['detailt'] = '';

                // 定义匹配默认值的正则
                $getDefaultRule = '/DEFAULT \'(.*?)\'/';
                $getDefaultNullRule = '/DEFAULT NULL/';
                preg_match($getDefaultRule, $value, $defaultData);
                if (!empty($defaultData)) {
                    $tableFieldsData[$i]['detailt_status'] = true;
                    $tableFieldsData[$i]['detailt'] = isset($defaultData[1]) ? $defaultData[1] : '';
                } else if (preg_match($getDefaultNullRule, $value)) {
                    $tableFieldsData[$i]['detailt_status'] = true;
                    $tableFieldsData[$i]['detailt'] = null;
                }

                // 获取注释
                $getCommonRule = '/COMMENT \'(.*?)\'/';
                preg_match($getCommonRule, $value, $commonData);
                $tableFieldsData[$i]['common'] = isset($commonData[1]) ? $commonData[1] : '';

                $i++;
                // 解析表索引
            } else if ($newValue[3] == 'KEY' || $newValue[2] == 'KEY') {
                if ($newValue[2] == 'PRIMARY') {
                    $indexType = "主键";
                    $indexName = '';
                    $indexFieldName = rtrim($newValue[4], ',');
                } else if ($newValue[2] == 'KEY') {
                    $indexType = "索引";
                    $indexName = trim($newValue[3], '`');
                    $indexFieldName = rtrim($newValue[4], ',');
                } else if ($newValue[2] == 'UNIQUE') {
                    $indexType = '唯一索引';
                    $indexName = trim($newValue[4], '`');
                    $indexFieldName = rtrim($newValue[5], ',');
                }
                $tableIndexData[] = [
                    'type' => $indexType,
                    'name' => $indexName,
                    'fieldName' => $indexFieldName
                ];
            }
        }

        // 解析表索引


        $result = [
            'tableFieldsData' => $tableFieldsData, // 表字段数据
            'tableIndexData' => $tableIndexData, // 表索引数据
            'tableData' => $tableInfo
        ];

        return $result;
    }
}
