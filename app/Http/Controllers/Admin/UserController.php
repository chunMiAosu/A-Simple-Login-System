<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{
    /**
     * 获取用户列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //获取数据，查询用户 (每页用户数、用户名)
        $db = DB::table('users');
        $user = $db -> where(function($query) use($request){
            $username = $request -> input('name');
            $email = $request -> input('email');
            if(!empty($username))
            {
                $query -> where('name','like','%'.$username.'%');
            }
            if(!empty($email))
            {
                $query -> where('email','like','%'.$email.'%');
            }
        }) -> paginate($request->input('num')?$request->input('num'):3);
        return view('admin.user.list',compact('user','request'));
    }

    /**
     * 返回用户添加页面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.add');
    }

    /**
     * 执行添加操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //接受数据 username email pass repass
        $input = $request -> all();
        
        if($input['pass'] == $input['repass'])
        {
            //表单验证 
            $rule = [ 
                'username' => 'required|min:5',
                'pass' => 'required|max:16|min:6',
                'email' => 'email:rfc,dns'
            ];
            $msg = [
                'username.required' => '用户名必须输入',
                'username.max' => '用户名长度必须大于5',
                'pass.required' => '密码必须输入',
                'pass.max' => '密码长度必须小于16',
                'pass.min' => '密码长度必须大于6',
                'email.email' => '邮箱错误',
            ];
            $validator = Validator::make($input,$rule,$msg);

            //添加到数据库user表
            $db = DB::table('users');
            $res = $db -> insert([
                'name' => $input['username'],
                'password' => $input['pass'],
                'email' => $input['email'],
            ]);
        }
        else
        {
            $res = false;
        }
       
        //给客户端返回一个json格式的结果
        if($res)
        {
            $data = [
                'status' => 0,
                'message' => '添加成功'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'message' => '添加失败'
            ];
          
        }
        return $data;
    }

    /**
     * 显示一条用户记录
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 返回一个修改页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $db = DB::table('users');
        $user = $db -> find($id);
        return view('admin.user.edit',compact('user'));
    }

    /**
     * 执行一个修改操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $db = DB::table('users');
        //获取要修改成的用户名
        $uname = $request -> input('username');
        $res = $db
              ->where('id', $id)
              ->update(['name' => $uname]);
        //给客户端返回一个json格式的结果
        if($res)
        {
            $data = [
                'status' => 0,
                'message' => '修改成功'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'message' => '修改失败'
            ];

        }
        return $data;
    }

    /**
     * 执行删除操作
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $db = DB::table('users');
        $user = $db -> where('id','=',$id);
        $res = $user -> delete();
        //给客户端返回一个json格式的结果
        if($res)
        {
            $data = [
                'status' => 0,
                'message' => '删除成功'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'message' => '删除失败'
            ];

        }
        return $data;
    }

    //删除所有选中用户
    public function delAll(Request $request)
    {
        $temp = $request -> all();
        $input = $temp['ids'];
        $res = 0;
        for($i=0;$i<count($input);$i++)
        {
            $res += DB::table('users') -> where('id',"=",$input[$i]) -> delete();
        }
  
        //给客户端返回一个json格式的结果
        if($res == count($input))
        {
            $data = [
                'status' => 0,
                'message' => '删除成功'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'message' => '删除失败'
            ];

        }
        return $data;
        
    }
}
