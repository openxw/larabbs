<?php

namespace App\Handlers;

use Illuminate\Support\Str;

class ImageUploadHandler
{
    // 只允许以下后缀名的图片文件上传
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg"];

    public function save($file, $folder, $file_prefix)
    {
        // 构建存储文件夹的规则,值如: uploads/images/avatars/2020/01/9/
        // 文件夹切割能让查找效率更高
        $folder_name = "uploads/images/$folder/" .date("Ym/d",time());

        // 文件的具体物理路径, 'public_path()' 获取的是 'public' 文件夹的物理路径.
        // 值如: /home/vagrant/Code/larabbs/public/uploads/images/avatars/202001/9/
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名,因图片从剪贴板黏贴时后缀名为空,所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名,加前缀是为了增加辨识度,前缀可以是相关数据模型的 ID
        // 值如:1_1493521050_7BVc9v9ujP.jpg
        $filename = $file_prefix . '_' .time() .'_' .Str::random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移到到我们的目标存储路径
        $file->move($upload_path, $filename);

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];

    }
}
