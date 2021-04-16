<?php

namespace reporter\lib\interfaces;

// 文件上传接口类
interface UploadFile
{
    /**
     * 获取单个文件
     *
     * @param string $fileInputName 上传的文件传入值
     * @return UploadFile
     */
    public function getFile(string $fileInputName): \reporter\lib\UploadFile;

    /**
     * 获取本次上传的所有文件
     *
     * @return UploadFile
     */
    public function getFiles(): \reporter\lib\UploadFile;

    /**
     * 验证文件
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * 获取文件上传时的原始名称
     *
     * @return string
     */
    public function getClientName(): string;

    /**
     * 获取临时文件的名称
     *
     * @return string
     */
    public function getTempName(): string;

    /**
     * 获取文件的扩展名
     *
     * @return string
     */
    public function getClientExtension(): string;

    /**
     * 获取文件的媒体类型
     *
     * @return string
     */
    public function getClientType(): string;

    /**
     * 获取文件大小
     *
     * @return int
     */
    public function getSize(): int;

    /**
     * 移动文件
     *
     * @param string $path 移动的目标目录
     * @return string
     */
    public function move(string $path): string;
}