@include('layouts.header')

        <!-- 内容区域 -->
<div class="content-wrapper">
    <!-- 内容标题 -->
    <section class="content-header">
        <h1>
            用户管理
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
                        <form class="box-form form-inline chead submitForm" action="{{url()->current()}}" method="get">
                            <a href="javascript:;" class="btn btn-primary bg-pink btn-big" data-toggle="modal" data-target="#addModal">+ 添加新用户</a>
                            <div class="form-group">
                                <label>所属机构：</label>
                                <select name="group"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php foreach($grouplist as $key=>$value){?>
                                        <option value="<?php echo $value['id'];?>" <?php if($value['id'] == $group){echo 'selected';};?>><?php echo $value['name'];?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>角色：</label>

                                <select name="permis" class="form-control">
                                    <option value="">请选择</option>
                                    <?php foreach($permislist as $key=>$value){?>
                                        <option value="<?php echo $value['id'];?>" <?php if($value['id'] == $permis){echo 'selected';};?>><?php echo $value['name'];?></option>
                                    <?php }?>
                                </select>

                            </div>
                            <div class="form-group">
                                <input type="text" name='name' class="form-control" placeholder="输入用户名/真实姓名搜索" style="width: 200px;" value="<?php if(!empty($name)){echo $name;};?>" >
                            </div>
                            <button type="submit" class="btn search-btn">搜索</button>

                        </form>

                        <table class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <th>用户名</th>
                                <th>机构</th>
                                <th>角色</th>
                                <th>真实姓名</th>
                                <th>是否启用</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $item)
                                <tr>
                                    <td>{{$item['name']}}</td>
                                    <td>{{$item['gname']}}</td>
                                    <td>{{$item['pname']}}</td>
                                    <td>{{$item['nick_name']}}</td>
                                    <td>
                                        @if($item['status']=='1')
                                            <span> 启用</span>
                                        @else
                                            <span> 禁用</span>
                                        @endif
                                    </td>
                                    <td style='padding-left: 4px;'>{{$item['update_time']}}</td>
                                    <td style='padding-left: 4px;'>
                                        <a class="btn btn-default btn-xs btn-flat margin" data-target="#editModal"  data-toggle="modal" href='javascript:;'
                                           onclick="return tj({{$item['id']}})"
                                           tag='edit_link'>编辑</a>
                                        <a class="btn btn-default btn-xs btn-flat margin" data-target="#pwdModal"  data-toggle="modal" href='javascript:;'
                                           onclick="return edits({{$item['id']}})"
                                           tag='edit_link'>修改密码</a>
                                        <a class="btn btn-default btn-xs btn-flat margin"
                                           href='{{url('/admin/User/enable/').'?id='.$item['id'].'&status='.$item['status']}}'
                                        tag='enable_link'>
                                            @if($item['status']=='1')
                                                <span> 禁用</span>
                                            @else
                                                <span> 启用</span>
                                            @endif
                                        </a>
                                        <a class="btn btn-default btn-xs btn-flat margin"  href='javascript:;' onclick="return del({{$item['id']}})" tag='edit_link'>删除</a>
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
    <div class="modal fade" id="pwdModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog " id="mana_modal_dialogpwd" style="width: 50%;">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabelpwd">
                        编辑密码
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal submitForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">原始密码：</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="yqpwd">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">新密码：</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" placeholder="" id="newpwd">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">确认密码：</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="newpwds">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center !important">
                <input type="hidden" value="" id="idd" name="idd" />
                <button type="button" class="btn btn-primary" id="submitBtnss">确认保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog " id="mana_modal_dialog" style="width: 50%;">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        编辑用户
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal submitForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">用户名：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" placeholder=""  id="names">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">真实姓名：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="nick_names">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">机构：</label>
                            <div class="col-sm-6">
                                <select name="group" id="group_ids" class="form-control">
                                    <option value="">请选择</option>
                                    <?php foreach($grouplist as $key=>$value){?>
                                    <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">角色：</label>
                            <div class="col-sm-6">
                                <select name="permis" id="permiss" class="form-control">
                                    <option value="">请选择</option>
                                    <?php foreach($permislist as $key=>$value){?>
                                    <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">手机号码：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="mobiles">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">办公室电话：</label>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class=" form-control" id="tel2s">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">常用邮箱：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="emaills">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center !important">
                <input type="hidden" value="" id="id" name="id" />
                <button type="button" class="btn btn-primary" id="submitBtns">确认保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog " id="add_modal_dialog" style="width: 50%;">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">
                        添加新用户
                    </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal submitForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">用户名：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" placeholder=""  id="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">真实姓名：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="nick_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">密码：</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" placeholder="密码最少8个字符，最多20个字符" id="pass">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">重复密码：</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="pwd">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">机构：</label>
                            <div class="col-sm-6">
                                <select name="group" id="group_id" class="form-control">
                                    <option value="">请选择</option>
                                    <?php foreach($grouplist as $key=>$value){?>
                                    <option value="<?php echo $value['id'];?>" ><?php echo $value['name'];?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">角色：</label>
                            <div class="col-sm-6">
                                <select name="permis" id="permis" class="form-control">
                                    <option value="">请选择</option>
                                    <?php foreach($permislist as $key=>$value){?>
                                    <option value="<?php echo $value['id'];?>" ><?php echo $value['name'];?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">手机号码：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="mobile">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">办公室电话：</label>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <input type="text" class=" form-control" id="tel2">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">常用邮箱：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="emaill">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center !important">
                <button type="button" class="btn btn-primary" id='submitBtn'>确认保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->

    <!-- /.content -->
</div>
</div>
<script src="/public/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
    //删除数据
    function del(id)
    {
        var id=id;
        $.ajax({
            url:"{{url('admin/User/deluser')}}",
            type:'post',
            data:{id:id},
            success:function(obj)
            {
                if(obj.code!=200)
                {
                    alert(obj.data);
                    return false;
                }else
                {
                    alert(obj.data);
                    window.location.href="{{url('admin/User/index')}}";
                }
            }
        })
    }

    $(function()
    {
        //新增数据
        var is_submit = 1;
        $('#submitBtn').on('click',function(){
            var name      = $("#name").val();
            var nick_name = $("#nick_name").val();
            var pass      = $("#pass").val();
            var pwd       = $("#pwd").val();
            var group_id  = $("#group_id").val();
            var permis    = $("#permis").val();

            var tel2      = $("#tel2").val();
            var mobile    = $("#mobile").val();
            var emall     = $("#emaill").val();
            if(name=='')
            {
                alert("用户名不能为空");
                return false;
            }
            var i=/^([0-9a-zA-Z_]+)$/;
            if(!name.match(i))
            {
                alert('用户名只能由英文数字下划线组成');
                return false;
            }
            if(pwd!=pass){
                alert("确认密码和密码不一致");
                return false;
            }
            if(permis==''){
                alert("请选择角色");
                return false;
            }
            if(group_id==''){
                alert("请选择机构");
                return false;
            }
            $.ajax({
                url:"{{url('admin/User/store')}}",
                type:'post',
                data:{name:name,nick_name:nick_name,password:pass,pwd:pwd,group_id:group_id,permis_id:permis,tel2:tel2,mobile:mobile,emall:emall},
                success:function(obj){
                    if(obj.code!=200){
                        alert(obj.data);
                        return false;
                    }else{
                        alert(obj.data);
                        window.location.href="{{url('admin/User/index')}}";
                    }
                }
            })
        });


        $('#cancelBtn').on('click',function(){
            location.href = URL();
        });
        var error_code = "<?= (isset($error_code)?$error_code:'')?>";
        if(error_code != ''){
            $('#CreateForm input[fld=name]').parent().next().html(error_code);
        }
        //提交编辑数据
        $('#submitBtns').on('click',function()
        {
            var name    =   $("#names").val();
            var id      =   $("#id").val();
            var nick_name = $("#nick_names").val();
            var group_id  = $("#group_ids").val();
            var permis    = $("#permiss").val();
            var tel2      = $("#tel2s").val();
            var mobile    = $("#mobiles").val();
            var emall     = $("#emaills").val();
            if(name==''){
                alert("用户名不能为空");
                return false;
            }
            var i=/^([0-9a-zA-Z_]+)$/;
            if(!name.match(i)){
                alert('用户名只能由英文数字下划线组成');
                return false;
            }
            if(permis==''){
                alert("请选择角色");
                return false;
            }
            if(group_id==''){
                alert("请选择机构");
                return false;
            }
            $.ajax({
                url:"{{url('admin/User/update')}}",
                type:'post',
                data:{id:id,name:name,nick_name:nick_name,group_id:group_id,permis_id:permis,tel2:tel2,mobile:mobile,emall:emall},
                success:function(obj)
                {
                    if(obj.code!=200)
                    {
                        alert(obj.data);
                        return false;
                    }else{
                        alert(obj.data);
                        window.location.href="{{url('admin/User/index')}}";
                    }
                }
            })
        });
        //修改密码
        $('#submitBtnss').on('click',function()
        {
            var password=$("#yqpwd").val();
            var newpwd=$("#newpwd").val();
            var upwd=$("#newpwds").val();
            var id=$("#idd").val();
            if(password==''){
                alert("原始密码不能为空");
                return false;
            }
            if(newpwd==''){
                alert("新密码不能为空");
                return false;
            }
            if(newpwd!=upwd){
                alert("确认密码和新密码不一致");
                return false;
            }
            $.ajax({
                url:"{{url('admin/User/editpass')}}",
                type:'post',
                data:{id:id,password:password,newpwd:newpwd,upwd:upwd},
                success:function(obj)
                {
                    if(obj.code!=200){
                        alert(obj.data);
                        return false;
                    }else{
                        alert(obj.data);
                        window.location.href="{{url('admin/User/index')}}";
                    }
                }
            })
        });
    });

    //编辑获取数据
    function tj(id)
    {
        var id=id;
        $.ajax({
            url:"{{url('admin/User/edit')}}",
            type:'post',
            data:{id:id},
            success:function(data)
            {
                $("#names").attr("value",data.data.name);
                $("#nick_names").attr("value",data.data.nick_name);
                $("#mobiles").attr("value",data.data.mobile);
                $("#emaills").attr("value",data.data.emall);
                $("#tel2s").attr("value",data.data.tel);
                $("#id").attr("value",data.data.id);
                $("#group_ids").val(data.data.group_id).attr("selected",true);
                $("#permiss").val(data.data.permis_id).attr("selected",true);
            }
        })
    }
    function edits(id)
    {
        var id=id;
        $.ajax({
            url:"{{url('admin/Permis/store')}}",
            type:'post',
            data:{id:id},
            success:function(msg){
                var obj  = eval( "(" + msg + ")" );
                $("#idd").attr("value",obj.id);
            }
        })
    }

</script>
@include('layouts.footer')
