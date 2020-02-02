<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use Session;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        if($request->session()->exists('name'))
        {
            $name = session('name');
            return view('afterLogin',compact('name'));
        }
        return view('index');
    }
    public function registered(Request $request)
    {
        if($request->session()->exists('name'))
        {
            $name = session('name');
            return view('afterLogin',compact('name'));
        }
        if($request -> method() == 'POST')
        {
            //自动验证
            $request -> validate([
                'name' => 'required|max:10',
                'password' => 'required|max:10',
                'captcha' => 'required|captcha',
            ]);
            //数据库操作
            $db = DB::table('user');
            $data = $request -> all();
            $exist = $db -> where('name',$data['name']) -> value('id');
            if($exist)
            {
                echo '用户已存在，请重新输入：';
                return view('registered');
            }
            else
            {
                $db -> insert([
                    'name' => $data['name'],
                    'password' => $data['password']
                ]);
                return redirect('/login');
            }
        }
        else
        {
            return view('registered');
        }

    }
    public function login(Request $request)
    {
        if($request->session()->exists('name'))
        {
            $name = session('name');
            return view('afterLogin',compact('name'));
        }
        if($request -> method() == 'POST')
        {
            $request -> validate([
                'name' => 'required|max:10',
                'password' => 'required|max:10',
                'captcha' => 'required|captcha',
            ]);
            $data = $request -> all();
            //数据库查询
            $db = DB::table('user');
            $name = $data['name'];
            $pwd = $db -> where('name',$name) -> value('password');
            
            if($pwd == $data['password'])
            {
                session(['name' => $name]);
                return view('afterLogin',compact('name'));
            }
            else
            {              
                echo '登录失败！';
                $value = $request->session()->pull('name', 'default');
            }
        }
        else
        {
            return view('login');
        }
    }
    public function logout(Request $request)
    {
        if($request->session()->exists('name'))
        {
            $name = session('name');
            $value = $request->session()->pull('name', 'default');
            return redirect('/login');
        }
    }
}
