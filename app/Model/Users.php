<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //关联的数据表
    public $table = 'users';
    
    //主键
    public $primaryKey = 'id';

    //允许批量操作的字段
  //  public $fillable = ['name','password','email'];
    public $guarded = []; 

    //是否维护created_at和updated_at字段
    public $timestamps = false;

    //关联role模型
    public function role()
    {
      return $this -> belongsToMany('App\Model\Role','user_role','user_id','role_id');
    }

}
