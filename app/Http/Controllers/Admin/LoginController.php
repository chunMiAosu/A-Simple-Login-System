<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use App\Model\Users;
use App\Model\Role;
use App\Model\Permission;

class LoginController extends Controller
{
    //后台登录页面
    public function login()
    {
       if(session()->get('name'))
       {
           return redirect('admin/index');
       }
        return view('admin.login');
    }

    //处理后台登录
    public function doLogin(Request $request)
    {
        //表单验证 
        $data = $request -> all();
        $rule = [ 
            'name' => 'required|min:5',
            'password' => 'required|max:16|min:6',
            'captcha' => 'required|captcha'
        ];
        $msg = [
            'name.required' => '用户名必须输入',
            'name.max' => '用户名长度必须大于5',
            'password.required' => '密码必须输入',
            'password.max' => '密码长度必须小于16',
            'password.min' => '密码长度必须大于6',
            'captcha.required' => '验证码必须输入',
            'captcha.captcha' => '验证码错误'
        ];

        // $validator = Validator::make('需要验证的表单数据','验证规则','错误提示信息');
        $validator = Validator::make($data,$rule,$msg);

        if ($validator->fails()) {
            return redirect('admin/login')
                        ->withErrors($validator)
                        ->withInput();
        }

        //验证用户是否存在
        $db = DB::table('users');
        $user = $db -> where('name',$data['name']) -> first();
        if(!$user)
        {
            return redirect('admin/login')
                        ->withErrors('用户不存在');
        }
        
        if($data['password'] != $user->password)
        {
            return redirect('admin/login')
                        ->withErrors('密码错误');
        }

        //存用户到session
        session(['name' => $data['name']]);
        //将用户拥有的权限存到session
        //获取当前用户权限组  用户-角色-权限
        $user = Users::where('name','=',$data['name']) -> first(); //获取用户
        $roles = $user -> role; //获取用户的角色
        $arr = []; //存放权限对应的per_url字段值
        foreach($roles as $v)
        {
            $perms = $v -> permission;
            foreach($perms as $u)
            {
                $arr[] = $u -> per_url;
            }
        }
        //去掉重复的权限
        $arr = array_unique($arr);
        session(['permission' => $arr]);

        return redirect('admin/index');

    }
    public function index()
    {
        return view('admin.index');
    }
    public function logout()
    {
        //清空session
        session() -> flush();

        return redirect('/admin/login');
    }
    public function welcome()
    {
        return view('admin.welcome');
    }

    public function noaccess()
    {
        return view('errors.noaccess');
    }
}
