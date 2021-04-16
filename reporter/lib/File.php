<?php

namespace reporter\lib;

use reporter\lib\interfaces\File as FileInterface;

// 文件基类
class File implements FileInterface
{
    /**
     * @var \SplFileInfo 实例
     */
    protected static $Instance = NULL;

    /**
     * 架构函数
     *
     * @param string $fileName 文件名
     * @return File
     */
    public function __construct(string $fileName)
    {
        self::$Instance = new \SplFileInfo($fileName);
    }

    /**
     * 获取文件的最后访问时间
     *
     * @return int
     */
    public function getATime(): int
    {
        return self::$Instance->getATime();
    }

    /**
     * 获取文件的基本名称
     *
     * @return string
     */
    public function getBasename(): string
    {
        return self::$Instance->getBasename();
    }

    /**
     * 获取inode更改时间
     *
     * @return int
     */
    public function getCTime(): int
    {
        return self::$Instance->getCTime();
    }

    /**
     * 获取文件扩展名
     *
     * @return string
     */
    public function getExtension(): string
    {
        return self::$Instance->getExtension();
    }

    /**
     * 获取文件的SplFileInfo对象
     *
     * @return \SplFileInfo
     */
    public function getFileInfo(): \SplFileInfo
    {
        return self::$Instance->getFileInfo();
    }

    /**
     * 获取文件名
     *
     * @return string
     */
    public function getFilename(): string
    {
        return self::$Instance->getFilename();
    }

    /**
     * 获取文件组
     *
     * @return int
     */
    public function getGroup(): int
    {
        return self::$Instance->getGroup();
    }

    /**
     * 获取文件的索引节点
     *
     * @param int
     */
    public function getInode(): int
    {
        return self::$Instance->getInode();
    }

    /**
     * 获取链接的目标
     *
     * @param string
     */
    public function getLinkTarget(): string
    {
        return self::$Instance->getLinkTarget();
    }

    /**
     * 获取上次修改的时间
     *
     * @return int
     */
    public function getMTime(): int
    {
        return self::$Instance->getMTime();
    }

    /**
     * 获取文件的所有者
     *
     * @return int
     */
    public function getOwner(): int
    {
        return self::$Instance->getOwner();
    }

    /**
     * 获取不带文件名的路径
     *
     * @return string
     */
    public function getPath(): string
    {
        return self::$Instance->getPath();
    }

    /**
     * 获取路径的SplFileInfo对象
     *
     * @return \SplFileInfo
     */
    public function getPathInfo(): \SplFileInfo
    {
        return self::$Instance->getPathInfo();
    }

    /**
     * 获取文件路径
     *
     * @return string
     */
    public function getPathname(): string
    {
        return self::$Instance->getPathname();
    }

    /**
     * 获取文件权限
     *
     * @return int
     */
    public function getPerms(): int
    {
        return self::$Instance->getPerms();
    }

    /**
     * 获取文件的绝对路径
     *
     * @return string
     */
    public function getRealPath(): string
    {
        return self::$Instance->getRealPath();
    }

    /**
     * 获取文件大小
     *
     * @return int
     */
    public function getSize(): int
    {
        return self::$Instance->getSize();
    }

    /**
     * 获取文件的类型
     *
     * @return string
     */
    public function getType(): string
    {
        return self::$Instance->getType();
    }

    /**
     * 判断文件是否为目录
     *
     * @return bool
     */
    public function isDir(): bool
    {
        return self::$Instance->isDir();
    }

    /**
     * 告诉文件是否可执行
     *
     * @return bool
     */
    public function isExecutable(): bool
    {
        return self::$Instance->isExecutable();
    }

    /**
     * 告知对象是否引用常规文件
     *
     * @return bool
     */
    public function isFile(): bool
    {
        return self::$Instance->isFile();
    }

    /**
     * 告诉文件是否为链接
     *
     * @return bool
     */
    public function isLink(): bool
    {
        return self::$Instance->isLink();
    }

    /**
     * 告诉文件是否可读
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return self::$Instance->isReadable();
    }


    /**
     * 告诉条目是否可写
     *
     * @return bool
     */
    public function isWritable(): bool
    {
        return self::$Instance->isWritable();
    }

    /**
     * 获取文件的SplFileObject对象
     *
     * @param string $openMode 打开模式
     * @param bool $useIncludePath 设置true为时 还将在include_path中搜索文件名 [default=false]
     * @param string $context [default=null]
     * @return \SplFileObject
     */
    public function openFile(string $openMode, bool $useIncludePath, string $context = null): \SplFileObject
    {
        return self::$Instance->openFile($openMode, $useIncludePath, $context);
    }

    /**
     * 设置与SplFileInfo :: openFile一起使用的类
     *
     * @param string $className 调用时SplFileInfo::openFile要使用的类名 [default='SplFileObject']
     * @return void
     */
    public function setFileClass(string $className = 'SplFileObject'): void
    {
        self::$Instance->setFileClass($className);

        return;
    }

    /**
     * 设置与SplFileInfo::getFileInfo和SplFileInfo::getPathInfo一起使用的类
     *
     * @param string $className 类名 [default='SplFileInfo']
     * @return void
     */
    public function setInfoClass(string $className = 'SplFileInfo'): void
    {
        self::$Instance->setInfoClass($className);

        return;
    }

    /**
     * 以字符串形式返回文件的路径
     *
     * @return string
     */
    public function __toString(): string
    {
        return self::$Instance->__toString();
    }

    /**
     * 获取随机的文件名
     *
     * @param string $extension 文件后缀 [default='']
     * @reutrn string
     */
    public function getRandomName(string $extension = ''): string
    {
        $fileName = time() . '_' . mt_rand(1000000000, 9999999999);
        if (empty($extension)) {
            $fileName .= '.' . $this->getExtension();
        } else {
            $fileName .= '.' . $extension;
        }


        return $fileName;
    }


    /**
     * 移动文件
     *
     * @param string $path 移动的目标目录
     * @param string $fileName 文件名 [default='']
     * @return string
     */
    public function move(string $path, string $fileName = '')
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // 获取当前文件的绝对路径
        $oldFilePath = $this->getRealPath();
        // 如果没有传入文件名
        if (empty($fileName)) {
            // 获取之前的文件名称
            $fileName = $this->getFilename();
        }
        // 组装移动的路径
        $newFilePath = $path . '/' . $fileName;
        // 拷贝文件
        copy($oldFilePath, $newFilePath);
        unlink($oldFilePath);

        return $newFilePath;
    }
}