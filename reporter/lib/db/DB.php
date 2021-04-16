<?php

namespace reporter\lib\db;

use reporter\lib\db\Connect;
use reporter\lib\db\Base;
use reporter\lib\Log;
use reporter\lib\Config;

class DB extends Base
{
    /**
     * 当前对象
     */
    protected static $that = NULL;

    /**
     * @var object PDO对象
     */
    protected static $pdo = NULL;

    /**
     * @var string 表前缀
     */
    protected static $prefix = '';

    /**
     * @var string 表名
     */
    protected static $tableName = '';

    /**
     * @var string 别名
     */
    protected static $alias = '';


    /**
     * @var array 链表查询配置
     * @example 格式为:[["链接方式", "链接的表名", [["链接条件字段A", "链接表达式", "链接条件字段B"], ...]], ...]
     */
    protected $joins = [];

    /**
     * @var array 查询的字段
     */
    protected $fields = ['*'];

    /**
     * @var array 筛选条件
     * @example 格式为:[["字段名", "表达式", "字段值"], ["字段名", "表达式", "字段值"], ...]
     */
    protected $where = [];

    /**
     * @var array 排序条件
     * @example 格式为:[["排序字段", "排序方式"], ["排序字段", "排序方式"], ...]
     */
    protected $orderBy = [];

    /**
     * @var array 分组字段
     * @example 格式为:["字段名A", "字段名B", ...]
     */
    protected $groupBy = [];

    /**
     * @var array 分页
     * @example 格式为:["起始行数", "长度"]
     */
    protected $limit = [];

    /**
     * @var array 添加的数据集合
     * @example 格式为:[["ID" => 1, "Name" => "Yaf"], ...]
     */
    protected $insertDatas = [];

    /**
     * @var array 更新的数据
     * @example 格式为:["字段A" => "字段值", "字段B" => "字段值", ...]
     */
    protected $updateData = [];

    private function __construct()
    {
        // 初始化参数
        $this->fields = ['*'];
        $this->where = [];
        $this->orderBy = [];
        $this->groupBy = [];

        // 获取数据库连接
        self::$pdo = Connect::getConnect();

        // 表前缀
        self::$prefix = Config::get('prefix', 'database');
    }

    /**
     * 设置表名
     *
     * @param string $tableName
     * @return Object
     */
    public static function name($tableName)
    {
        self::$that = new self();
        self::$tableName = $tableName;
        self::$alias = $tableName;

        return self::$that;
    }

    /**
     * 设置别名
     *
     * @param string $alias 别名
     * @return Object
     */
    public function alias($alias)
    {
        self::$alias = $alias;

        return $this;
    }

    /**
     * 链表查询
     *
     * @param string $method 链接方式
     * @param string $tableName 链接的表名
     * @param array $on 链接条件
     * @return Object
     */
    public function join($method, $tableName, $on)
    {
        $this->joins[] = [$method, $tableName, $on];

        return $this;
    }


    /**
     * 设置查询的字段
     *
     * @param array $fields 查询的字段
     * @return Object
     */
    public function field(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * 设置筛选的条件
     *
     * @param array $where 筛选条件
     * @return Object
     */
    public function where($where)
    {
        if (!empty($where)) {
            $this->where = array_merge($this->where, $where);
        }

        return $this;
    }

    /**
     * 设置排序规则
     *
     * @param array $orderBy 排序条件
     * @return Object
     */
    public function order(array $orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * 设置分组字段
     *
     * @param array $groupBy 分组字段
     * @return Object
     */
    public function group(array $groupBy)
    {
        $this->orderBy = $groupBy;

        return $this;
    }

    /**
     * 分页
     *
     * @param int $page 页码
     * @param int $limit 显示条数
     * @return Object
     */
    public function page($page, $limit)
    {
        $start = ($page - 1) * $limit;

        $this->limit = [$start, $limit];

        return $this;
    }

    /**
     * 查询单条数据
     *
     * @return array
     */
    public function find()
    {
        // 组装SQL
        $Assembly = new Assembly();
        $sql = $Assembly->find(
            self::$tableName,
            self::$alias,
            $this->joins,
            $this->fields,
            $this->where,
            $this->orderBy,
            $this->groupBy
        );

        // 查询
        $result = $this->executeSql($sql);

        return $result;
    }

    /**
     * 查询多条数据
     *
     * @return array
     */
    public function select()
    {
        // 组装SQL
        $Assembly = new Assembly();
        $sql = $Assembly->select(
            self::$tableName,
            self::$alias,
            $this->joins,
            $this->fields,
            $this->where,
            $this->orderBy,
            $this->groupBy,
            $this->limit
        );

        // 查询
        $result = $this->executeSql($sql);


        return $result;
    }

    /**
     * 写入当条数据
     *
     * @param array $insertData 写入的数据
     * @return int 影响的条数
     */
    public function insert(array $insertData)
    {
        $this->insertDatas = [$insertData];

        // 组装SQL
        $Assembly = new Assembly();
        $sql = $Assembly->insert(self::$tableName, $insertData);

        // 执行SQL
        $result = $this->executeSql($sql, self::ACTION_INSERT);
        if (empty($result)) {
            throw new DBException('写入失败');
        }

        return $result;
    }

    /**
     * 写入当条数据
     *
     * @param array $insertData 写入的数据
     * @return int 影响的条数
     */
    public function insertGetId(array $insertData)
    {
        $this->insertDatas = [$insertData];

        // 组装SQL
        $Assembly = new Assembly();
        $sql = $Assembly->insert(self::$tableName, $insertData);

        // 执行SQL
        $result = $this->executeSql($sql, self::ACTION_INSERT);
        if (empty($result)) {
            throw new DBException('写入失败');
        }

        // 获取写入的ID
        $newId = self::$pdo->lastInsertId();

        return $newId;
    }

    /**
     * 更新数据
     *
     * @param array $updateData 更新的数据
     * @return int 影响的条数
     */
    public function update(array $updateData)
    {
        $this->updateData = $updateData;

        // 组装SQL
        $Assembly = new Assembly();
        $sql = $Assembly->update(self::$tableName, $updateData, $this->where);

        // 执行SQL
        $result = $this->executeSql($sql, self::ACTION_UPDATE);

        return $result;
    }

    /**
     * 删除数据
     *
     * @return int
     */
    public function delete()
    {
        // 组装SQL
        $Assembly = new Assembly();
        $sql = $Assembly->delete(self::$tableName, $this->where);

        // 执行SQL
        $result = $this->executeSql($sql, self::ACTION_DELETE);

        return $result;
    }

    /**
     * 自增
     *
     * @param string $fieldName 字段名
     * @param int $increment 增加的值 [default=1]
     * @return bool
     */
    public function increment(string $fieldName, int $increment = 1)
    {
        // 组装SQL
        $Assembly = new Assembly();
        $sql = $Assembly->increment(self::$tableName, $this->where, $fieldName, $increment);

        // 执行SQL
        $result = $this->executeSql($sql, self::ACTION_UPDATE);

        return $result;
    }

    /**
     * 自减
     *
     * @param string $fieldName 字段名
     * @param int $decrement 增加的值 [default=1]
     * @return bool
     */
    public function decrement(string $fieldName, int $decrement = 1)
    {
        // 组装SQL
        $Assembly = new Assembly();
        $sql = $Assembly->decrement(self::$tableName, $this->where, $fieldName, $decrement);

        // 执行SQL
        $result = $this->executeSql($sql, self::ACTION_UPDATE);

        return $result;
    }

    /**
     * 获取表字段
     *
     * @return array
     */
    public function getTableField(): array
    {
        // 组装SQL
        $Assembly = new Assembly();
        $sql = $Assembly->desc(self::$tableName);

        // 执行SQL
        $result = $this->executeSql($sql, self::ACTION_DESC);

        return $result;
    }

    /**
     * 执行SQL
     *
     * @param string $sql SQL
     * @param string $action 执行的操作
     * @return array|int
     */
    public function executeSql($sql, $action = self::ACTION_SELECT)
    {
        if (empty($sql)) {
            throw new \Exception('Param error');
        }

        // 准备SQL语句
        $stmt = self::$pdo->prepare($sql);

        // 获取所有参数
        $params = $this->getAllParams($action);

        foreach ($params as $param) {
            $sql = preg_replace('/\?/', "'" . $param . "'", $sql, 1);
        }

        $startTime = microtime(true);
        // 预处理并设置参数
        $executeResult = $stmt->execute($params);
        $endTime = microtime(true);


        // 是否执行成功
        if ($executeResult == true) {
            Log::record('[ sql ] ' . $sql . ' [RunTime:' . round($endTime - $startTime, 5) . 's]');
        }

        if ($action == self::ACTION_SELECT || $action == self::ACTION_DESC) {
            // 设置返回的数据格式
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            // 获取所有数据集的数据
            $result = $stmt->fetchAll();
        } else {
            // 查询影响的行数
            $result = $stmt->rowCount();
        }


        return $result;
    }

    /**
     * 获取所有的参数值
     *
     * @param string $action 执行的操作
     * @return array
     */
    public function getAllParams($action)
    {
        $params = [];

        // 查询
        if ($action == self::ACTION_SELECT) {
            $params = array_column($this->where, 2);
            // 写入
        } else if ($action == self::ACTION_INSERT) {
            $params = [];
            foreach ($this->insertDatas as $insertData) {
                $insertValues = array_values($insertData);
                $params = array_merge($params, $insertValues);
            }
            // 更新
        } else if ($action == self::ACTION_UPDATE) {
            $params = array_values($this->updateData);
            if (!empty($this->where)) {
                $whereVars = array_column($this->where, 2);
                $params = array_merge($params, $whereVars);
            }
            // 删除
        } else if ($action == self::ACTION_DELETE) {
            $params = array_column($this->where, 2);
        }


        return $params;
    }

    /**
     * 获取真实的表名
     *
     * @param string $tableName 表名
     * @return string
     */
    public static function getRealTableName($tableName)
    {
        if (empty($tableName)) {
            return false;
        }

        $realTableName = self::$prefix . $tableName;

        return $realTableName;
    }

    /**
     * 是否是真实的表名
     *
     * @param string $tableName 表名
     * @return bool
     */
    public static function isRealTableName(string $tableName)
    {
        if (empty($tableName)) {
            return false;
        }
        $tableNamePrefix = substr($tableName, 0, count(self::$prefix));
        if ($tableNamePrefix == self::$prefix) {
            return true;
        }

        return false;
    }

}