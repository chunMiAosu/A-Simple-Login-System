<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>角色列表页</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />

        @include('admin.public.styles')
        @include('admin.public.script')

        <!--[if lt IE 9]>
          <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
          <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">演示</a>
            <a>
              <cite>导航元素</cite></a>
          </span>
          <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                            <button class="layui-btn" onclick="xadmin.open('添加用户','/admin/role/create',600,400)"><i class="layui-icon"></i>添加</button>      
                        </div>
                        <div class="layui-card-body layui-table-body layui-table-main">
                            <table class="layui-table layui-form">
                                <thead>
                                  <tr>
                                    <th>
                                      <input type="checkbox" lay-filter="checkall" name="" lay-skin="primary" >
                                    </th>
                                    <th>ID</th>
                                    <th>角色名</th>
                                    <th>操作</th></tr>
                                </thead>
                                <tbody>
                                @foreach($role as $v)
                                  <tr>
                                    <td>
                                      <input type="checkbox" name="id" value='{{$v->id}}' lay-skin="primary"> 
                                    </td>
                                    <td>{{$v -> id}}</td>
                                    <td>{{$v -> role_name}}</td>
                                    <td class="td-manage">
                                      <a title="授权" href="{{'role/auth/'.$v->id}}">
                                        <i class="layui-icon">&#xe620;</i>
                                      </a>
                                      <a title="编辑"  onclick="xadmin.open('编辑','/admin/user/{{$v->id}}/edit',600,400)" href="javascript:;">
                                        <i class="layui-icon">&#xe642;</i>
                                      </a>
                                      <a title="删除" onclick="member_del(this,{{$v->id}})" href="javascript:;">
                                        <i class="layui-icon">&#xe640;</i>
                                      </a>
                                    </td>
                                  </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </body>
    <script>
      layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var  form = layui.form;


        // 监听全选
        form.on('checkbox(checkall)', function(data){

          if(data.elem.checked){
            $('tbody input').prop('checked',true);
          }else{
            $('tbody input').prop('checked',false);
          }
          form.render('checkbox');
        }); 
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });


      });


      /*用户-删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              //发异步删除数据
            $.post('/admin/user/'+id,{"_method":"delete","_token":"{{csrf_token()}}"},function(data){
              if(data.status == 0)
              {
                $(obj).parents("tr").remove();
                layer.msg(data.message,{icon:6,time:1000});
              }
              else
              {
                layer.msg(data.message,{icon:5,time:1000});
              }
              
            });
          });
      }



      function delAll (argument) {
        var ids = [];

        // $(".layui-form-checked").not('.header').each(function(i,v){
        //   var u = $(v).attr('data-id');
        //   ids.push(u);
        // });

        //获取选中的id 
       $('tbody input').each(function(index, el) {
           if($(this).prop('checked')){
              ids.push($(this).val())
           }
       });
  
        layer.confirm('确认要删除吗?'+ids.toString(),function(index){

            $.get('user/del',{'ids':ids},function(data){
              if(data.status == 0)
              {
                $(".layui-form-checked").not('.header').parents('tr').remove();
                layer.msg(data.message,{icon:6,time:1000});
              }
              else
              {
                layer.msg(data.message,{icon:5,time:1000});
              }
            });
            
        });
      }
    </script>
</html>