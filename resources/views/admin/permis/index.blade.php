@include('layouts.header')

<div class="content-wrapper">
    <!-- 内容标题 -->
    <section class="content-header">
        <h1>
            角色管理
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="#">生成</a></li>
            <li class="active">添加</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content pd-top0">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box">

                    <div class="box-body solist">
                        <form class="box-form form-inline chead submitForm"   action="{{url()->current()}}" method="get">
                            <a href="javascript:;" class="btn btn-primary bg-pink btn-big" data-toggle="modal"
                               data-target="#addModal">+ 添加新角色</a>

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="输入角色名称搜索"
                                       style="width: 200px;" value="<?php if(isset($_GET['name'])){echo $_GET['name'];}?>" name="name">
                            </div>
                            <button type="submit" class="btn search-btn">搜索</button>
                        </form>
                        <table class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <th>角色ID</th>
                                <th>角色名称</th>
                                <th>角色编码</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $item)
                                <tr>
                                    <td>{{$item['id']}}</td>
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['description']}}</td>
                                    <td>
                                        @if($item['status']=='1')
                                            <span> 启用</span>
                                        @else
                                            <span> 禁用</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-default btn-xs btn-flat margin" data-target="#editModal"  data-toggle="modal" href="javascript:;" onclick="return sel({{$item['id']}})" tag='edit_link'>编辑</a>
                                        <a class="btn btn-default btn-xs btn-flat margin" href='{{url('admin/Permis/setpermis').'?id='.$item['id']}}' tag='edit_link'>编辑权限</a>
                                        <a class="btn btn-default btn-xs btn-flat margin" href='{{url('admin/Permis/enable').'?id='.$item['id'].'&status='.$item['status']}}' tag='enable_link'>
                                            @if($item['status']=='1')
                                                <span> 禁用</span>
                                            @else
                                                <span> 启用</span>
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer clearfix">
                        <div class="pageMsg">
                            <span class="page1">第<span> {{$lists->currentPage()}}</span>页</span>
                            <span class="page1">共<span>{{$lists->lastPage()}}</span>页</span>
                            <span class="page1">共<span>{{$lists->total()}}</span>条</span>
                        </div>
                        <div class="page-input">
                            {{$lists->links()}}
                        </div>
                    </div>

                </div>
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </section>
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog " id="add_modal_dialog" style="width: 40%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">
                        添加新角色
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal submitForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">角色名称：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name='name' id='name' placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">编码：</label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control" value="<?php echo 'ROLE'.date('YmdHis');?>"  name='description' id='description'>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center !important">
                <button type="button" class="btn btn-primary" onclick="return tj()">确认保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog " id="edit_modal_dialog" style="width: 40%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">
                        修改角色
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal submitForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">角色名称：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name='names' id='names' placeholder="机构名称在1-20个字符">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">角色编码：</label>
                            <div class="col-sm-6">
                                <input type="text"  class="form-control" name='descriptions' id='descriptions' readonly>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center !important">
                <input type="hidden" value="" id="id" name="id">
                <button type="button" class="btn btn-primary" onclick="return updates()">确认保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
    <!-- /.content -->
</div>
<script type="text/javascript">
    //删除
    function del(id){
        var id=id;
        $.ajax({
            url:"{{url('admin/Permis/edit')}}",
            type:'post',
            data:{id:id},
            success:function(obj){
                if(obj.code!=200){
                    alert(obj.data);
                    return false;
                }else{
                    alert(obj.data);
                    window.location.href="{{url('admin/Permis/index')}}";
                }
            }
        })
    }
    //编辑
    function sel(id)
    {
        var id=id;
        $.ajax({
            url:"{{url('admin/Permis/edit')}}",
            type:'post',
            data:{id:id},
            success:function(data)
            {
                if(data.code == 200)
                {
                    $("#names").attr("value",data.data.name);
                    $("#descriptions").attr("value",data.data.description);
                    $("#id").attr("value",data.data.id);
                }

            }
        })
    }
    //提交编辑数据
    function updates()
    {
        var name    = $("#names").val();
        var id      = $("#id").val();
        if(name==''){
            alert("请输入角色名称");
            return false;
        }
        $.ajax({
            url:"{{url('admin/Permis/update')}}",
            type:'post',
            data:{name:name,id:id},
            success:function(obj)
            {
                if(obj.code!=200)
                {
                    alert(obj.data);
                    return false;
                }else{
                    alert(obj.data);
                    window.location.href="{{url('admin/Permis/index')}}";
                }
            }
        })
    }
    function tj(){
        var name        = $("#name").val();
        var description = $("#description").val();
        if(name ==  '')
        {
            alert("请输入角色名称");
            return false;
        }
        $.ajax({
            url:"{{url('admin/Permis/store')}}",
            type:'post',
            data:{name:name,description:description},
            success:function(obj)
            {
                if(obj.code!=200){
                    alert(obj.data);
                    return false;
                }else{
                    alert(obj.data);
                    window.location.href="{{url('admin/Permis/index')}}";
                }
            }
        })

    }
</script>
@include('layouts.footer')