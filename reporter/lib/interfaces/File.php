<?php

namespace reporter\lib\interfaces;

interface File
{

    /**
     * 架构函数
     *
     * @param string $fileName 文件名
     * @return File
     */
    public function __construct(string $fileName);

    /**
     * 获取文件的最后访问时间
     *
     * @return int
     */
    public function getATime(): int;

    /**
     * 获取文件的基本名称
     *
     * @return string
     */
    public function getBasename(): string;

    /**
     * 获取inode更改时间
     *
     * @return int
     */
    public function getCTime(): int;

    /**
     * 获取文件扩展名
     *
     * @return string
     */
    public function getExtension(): string;

    /**
     * 获取文件的SplFileInfo对象
     *
     * @return \SplFileInfo
     */
    public function getFileInfo(): \SplFileInfo;

    /**
     * 获取文件名
     *
     * @return string
     */
    public function getFilename(): string;

    /**
     * 获取文件组
     *
     * @return int
     */
    public function getGroup(): int;

    /**
     * 获取文件的索引节点
     *
     * @param int
     */
    public function getInode(): int;

    /**
     * 获取链接的目标
     *
     * @param string
     */
    public function getLinkTarget(): string;

    /**
     * 获取上次修改的时间
     *
     * @return int
     */
    public function getMTime(): int;

    /**
     * 获取文件的所有者
     *
     * @return int
     */
    public function getOwner(): int;

    /**
     * 获取不带文件名的路径
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * 获取路径的SplFileInfo对象
     *
     * @return \SplFileInfo
     */
    public function getPathInfo(): \SplFileInfo;

    /**
     * 获取文件路径
     *
     * @return string
     */
    public function getPathname(): string;

    /**
     * 获取文件权限
     *
     * @return int
     */
    public function getPerms(): int;

    /**
     * 获取文件的绝对路径
     *
     * @return string
     */
    public function getRealPath(): string;

    /**
     * 获取文件大小
     *
     * @return int
     */
    public function getSize(): int;

    /**
     * 获取文件的类型
     *
     * @return string
     */
    public function getType(): string;

    /**
     * 判断文件是否为目录
     *
     * @return bool
     */
    public function isDir(): bool;

    /**
     * 告诉文件是否可执行
     *
     * @return bool
     */
    public function isExecutable(): bool;

    /**
     * 告知对象是否引用常规文件
     *
     * @return bool
     */
    public function isFile(): bool;

    /**
     * 告诉文件是否为链接
     *
     * @return bool
     */
    public function isLink(): bool;

    /**
     * 告诉文件是否可读
     *
     * @return bool
     */
    public function isReadable(): bool;


    /**
     * 告诉条目是否可写
     *
     * @return bool
     */
    public function isWritable(): bool;

    /**
     * 获取文件的SplFileObject对象
     *
     * @param string $openMode 打开模式
     * @param bool $useIncludePath 设置true为时 还将在include_path中搜索文件名 [default=false]
     * @param string $context [default=null]
     * @return \SplFileObject
     */
    public function openFile(string $openMode, bool $useIncludePath, string $context = null): \SplFileObject;

    /**
     * 设置与SplFileInfo :: openFile一起使用的类
     *
     * @param string $className 调用时SplFileInfo::openFile要使用的类名 [default='SplFileObject']
     * @return void
     */
    public function setFileClass(string $className = 'SplFileObject'): void;

    /**
     * 设置与SplFileInfo::getFileInfo和SplFileInfo::getPathInfo一起使用的类
     *
     * @param string $className 类名 [default='SplFileInfo']
     * @return void
     */
    public function setInfoClass(string $className = 'SplFileInfo'): void;

    /**
     * 以字符串形式返回文件的路径
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * 获取随机的文件名
     *
     * @param string $extension 文件后缀 [default='']
     * @reutrn string
     */
    public function getRandomName(string $extension): string;


    /**
     * 移动文件
     *
     * @param string $path 移动的目标目录
     * @param string $fileName 文件名
     * @return string
     */
    public function move(string $path, string $fileName);

}