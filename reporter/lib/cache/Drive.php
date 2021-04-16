<?php


namespace reporter\lib\cache;


interface Drive
{

    /**
     * 查询
     *
     * @param string $key 键
     * @return mixed
     */
    public function get(string $key);

    /**
     * 设置
     *
     * @param string $key 键
     * @param mixed $value 值
     * @return bool
     */
    public function set(string $key, $value);


    /**
     * 验证是否存在
     *
     * @param string $key 键
     * @return bool
     */
    public function has(string $key);


    /**
     * 删除
     *
     * @param string $key 键
     * @return bool
     */
    public function delete(string $key);
}