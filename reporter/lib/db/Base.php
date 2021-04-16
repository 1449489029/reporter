<?php

namespace reporter\lib\db;

class Base
{
    // 链表方式
    const JOIN_METHED_LEFT = 'LEFT'; // 左外连接
    const JOIN_METHED_RIGHT = 'RIGHT'; // 右外链接

    // 执行操作
    const ACTION_SELECT = 'SELECT'; // 查询
    const ACTION_INSERT = 'INSERT'; // 写入
    const ACTION_UPDATE = 'UPDATE'; // 更新
    const ACTION_DELETE = 'DELETE'; // 删除
    const ACTION_DESC = 'DESC'; // 表结构详细
    const ACTION_FIELD = 'FIELD'; // 字段
    const ACTION_WHERE = 'WHERE'; // 条件
    const ACTION_ORDER = 'ORDER'; // 排序
    const ACTION_GROUP = 'GROUP'; // 分组
    const ACTION_JOIN = 'JOIN'; // 链表

    // 函数大全
    const FUNCTIONS = ['MIN', 'MAX', 'SUM', 'COUNT'];


    // 正则
    const RULE_OPERATOR = '/\s*\[(?<operator>.*)\]$/'; // 用于匹配操作符的正则
    // 设置操作符的格式：
    // fieldName[+]
    // fieldName[-]
    const RULE_FUNCTION = '/[?<=\(]*(?<function>[a-z]+)(?=\()/'; // 用于匹配函数的正则
    // 设置函数的格式：
    // sum(fieldName)
    // sum(min(fieldName) / 60)
    const RULE_FIELD = '/(?<=\()(?<field>[a-zA-Z0-9]+)(?=\))/'; // 用于匹配字段名的正则
    // sum(fieldName)


    /**
     * @var array 操作符
     */
    protected $operator = ['+', '-'];


}