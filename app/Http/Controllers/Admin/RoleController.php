<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Role;
use App\Model\Permission;
use Illuminate\Support\Facades\Validator;
use DB;

class RoleController extends Controller
{
    //获取授权页面
    public function auth($id)
    {
        //获取当前角色
        $role = Role::find($id);
        //获取所有的权限列表
        $perms = Permission::get();
        //获取当前用户拥有的权限 user-role role-permission
        $role_perms = $role -> permission;

        //角色拥有的权限id
        $role_perms_id = [];
        foreach($role_perms as $v)
        {
            $role_perms_id[] = $v -> id;
        }
        return view('admin.role.auth',compact('role','perms','role_perms_id'));
    }
    //处理授权的方法
    public function doAuth(Request $request)
    {
        $input = $request -> all();
        
        //删除当前角色已有的权限
        DB::table('role_permission') -> where('role_id',$input['role_id']) -> delete();

        if(!empty($input['permission_id']))
        {
            foreach($input['permission_id'] as $v)
            {
                DB::table('role_permission') -> insert(['role_id' => $input['role_id'],'permission_id' => $v]);
            }
        }

        return redirect('admin/role');
        
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //获取数据
        $role = Role::get();

        return view('admin.role.list',compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.role.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //获取表单数据
        $input = $request -> all();

        //验证
        $rule = [ 
            'role_name' => 'required',
        ];
        $msg = [
            'role_name.required' => '角色名必须输入',
        ];
        $validator = Validator::make($input,$rule,$msg);

        //添加到数据库
        $res = Role::create($input);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
