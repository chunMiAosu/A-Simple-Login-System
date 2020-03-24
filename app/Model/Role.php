<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //关联的数据表
    public $table = 'role';
    
    //主键
    public $primaryKey = 'id';

    //允许批量操作的字段
  //  public $fillable = ['name','password','email'];
    public $guarded = []; 

    //是否维护created_at和updated_at字段
    public $timestamps = false;

    //添加动态属性，关联权限模型
    public function permission()
    {
      return $this -> belongsToMany('App\Model\Permission','role_permission','role_id','permission_id');
    }
}
