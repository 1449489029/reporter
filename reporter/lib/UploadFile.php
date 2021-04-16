<?php

namespace reporter\lib;

use reporter\lib\interfaces\UploadFile as UploadFileInterface;
use reporter\lib\Request;
use reporter\lib\File;

// 文件上传接口类
class UploadFile implements UploadFileInterface
{
    /**
     * @var Request 请求类
     */
    protected $request;

    /**
     * @var string 文件上传目录
     */
    protected $uploadPath = '';

    /**
     * @var array 选择的文件对象
     */
    protected $file;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->uploadPath = UPLOAD_PATH . '/' . date('Y-m') . '/';
    }

    /**
     * 获取单个文件
     *
     * @param string $fileInputName 上传的文件传入值
     * @return UploadFile
     */
    public function getFile(string $fileInputName): UploadFile
    {
        if (empty($fileInputName)) {
            throw new \Exception('参数错误');
        }

        $fileObject = $this->request->file($fileInputName);
        if (empty($fileObject)) {
            throw new \Exception('文件不存在');
        }
        $File = new File($fileObject['tmp_name']);

        $this->file = $fileObject;

        return $this;
    }

    /**
     * 获取本次上传的所有文件
     *
     * @return UploadFile
     */
    public function getFiles(): UploadFile
    {
    }

    /**
     * 验证文件
     *
     * @return bool
     */
    public function isValid(): bool
    {
        /*
        文件大小超过了 upload_max_filesize 配置的值。
        文件大小超过了表单定义的上传限制。
        文件仅部分被上传。
        没有文件被上传。
        无法将文件写入磁盘。
        无法上传文件：缺少临时目录。
        PHP扩展阻止了文件上传。
        */

    }

    /**
     * 获取文件上传时的原始名称
     *
     * @return string
     */
    public function getClientName(): string
    {
        return $this->files['name'];
    }

    /**
     * 获取临时文件的名称
     *
     * @return string
     */
    public function getTempName(): string
    {
        return $this->file['tmp_name'];
    }

    /**
     * 获取文件的扩展名
     *
     * @return string
     */
    public function getClientExtension(): string
    {
        $fileNameData = explode('.', $this->file['name']);
        $clientExtension = end($fileNameData);

        return $clientExtension;
    }

    /**
     * 获取文件的媒体类型
     *
     * @return string
     */
    public function getClientType(): string
    {
        return $this->file['type'];
    }

    /**
     * 获取文件大小
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->file['size'];
    }

    /**
     * 移动文件
     *
     * @param string $path 移动的目标目录
     * @return string
     */
    public function move(string $path): string
    {
        // 加载文件
        $File = new File($this->file['tmp_name']);
        // 生成一个新文件名
        $fileName = $File->getRandomName($this->getClientExtension());
        // 移动文件
        $newFilePath = $File->move($path, $fileName);

        return $newFilePath;
    }
}