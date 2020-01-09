<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    //
    public function __construct()
    {
        # code...
        $this->middleware('auth', ['except' => ['show']]);
    }
    public function show(User $user)
    {
        # code...
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        # code...
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, ImageUploadHandler $uploads, User $user)
    {
        $this->authorize('update', $user);
        //  将表单数据全部赋值 $data 变量
        $data = $request->all();

        // 如果表单字段avatar不为false,那么调用ImageUploadHander
        if ($request->avatar) {
            $result =$uploads->save($request->avatar, 'avatars', $user->id, 416);
            // 如图片处理逻辑中后缀名正常,将合并出的网站相对路径+完成文件名进行赋值给数据库avarat字段保存
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        //执行图片上传更新
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');

    }


}
