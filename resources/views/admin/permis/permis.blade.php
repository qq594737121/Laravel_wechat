@include('layouts.header')

<div class="content-wrapper">
    <!-- 内容标题 -->
    <section class="content-header">
        <h1>
            <small>角色管理</small>
            /权限编辑
        </h1>
        <ol class="breadcrumb display-none">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="#">生成</a></li>
            <li class="active">编辑</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content pd-top0">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body solist">
                        <div class="detail-page-sy">
                            <form class="form-horizontal submitForm"
                                  action = "{{url('admin/Permis/permismodule')."?id=".$permis_id}}"
                                  method="post"
                                  id='PermisForm'>
                                <div class="detail-page-border">
                                    <div class="detail_con">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>角色编码</th>
                                                    <th>角色名称</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $row['description'];?></td>
                                                    <td><?php echo $row['name'];?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="detail_title">
                                        权限设置
                                    </div>
                                    <div class="detail_con">
                                        <table class='table table-bordered'>
                                            <thead>
                                            <tr style='background-color: #dedede;'>
                                                <th style='text-align: center;'><input type='checkbox' id='allbox'></th>
                                                <th nowrap style='text-align: center;'>权限</th>
                                                <th nowrap style='text-align: center;'>地址</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($permis_module_list as $key=>$permis_module)
                                            {
                                                if(isset($permis_list[$permis_module['info']['url']])){
                                                    $is_checked = 'checked';
                                                }else{
                                                    $is_checked = '';
                                                }
                                            ?>
                                            <tr style='height: 28px;' pid="<?= $key?>">
                                                <td style='text-align: center;'>
                                                    <input type='checkbox'
                                                           name='permis_module_id[]'
                                                           <?=$is_checked?>
                                                           tag value="<?= $key?>"
                                                           parent_id="<?= $permis_module['info']['parent_id']?>" >
                                                    <input
                                                            type='hidden'
                                                            name='permis_module[<?= $key?>][url]'
                                                           value='<?= $permis_module['info']['url']?>'>
                                                </td>
                                                <td nowrap style='padding-left: 8px;'><?= $permis_module['info']['name']?></td>
                                                <td nowrap><?= $permis_module['info']['url']?></td>
                                            </tr>
                                            <?php
                                            if(isset($permis_module['list']))
                                            {
                                                foreach ($permis_module['list'] as $key=>$module)
                                                {
                                                    if(isset($permis_list[$module['info']['url']])){
                                                        $is_checked = 'checked';
                                                    }else{
                                                        $is_checked = '';
                                            }
                                            $left_width = '40';
                                            $module['info']['url'] = strtr($module['info']['url'],['\\'=>'/']);
                                            $namearr = explode('/', $module['info']['url']);
                                            if(isset($namearr['2']) && 'index' != $namearr['2']){
                                                $left_width = '70';
                                            }
                                            ?>
                                            <tr style='height: 28px;' pid="<?= $key?>">
                                                <td style='text-align: center;'>
                                                    <input type='checkbox' name='permis_module_id[]' <?=$is_checked?>
                                                    tag value="<?= $key?>"
                                                           parent_id="<?= $module['info']['parent_id']?>">
                                                    <input type='hidden' name='permis_module[<?= $key?>][url]'
                                                           value='<?= $module['info']['url']?>'>
                                                </td>
                                                <td style='padding-left: <?=$left_width?>px;'><?= $module['info']['name']?></td>
                                                <td style='padding-left: 10px;'><?= $module['info']['url']?></td>
                                            </tr>
                                            <?php
                                            }
                                            }
                                            }
                                            ?>
                                            </tbody>
                                        </table>


                                    </div>


                                </div>
                                <div class="box-footer align-center">
                                    <button type="button" id='submitBtn' class="btn btn-primary btn-big bg-pink">确定</button>
                                    <a
                                        href = "{{url('admin/Permis/index')}}"
                                        class="btn btn-default btn-big">取消</a>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/.col (left) -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
</div>
<script src="/public/plugins/jQuery/jquery-2.2.3.min.js"></script>
@include('layouts.footer')
<script>
    $(function(){
        //全选、取消全选
        $('#allbox').on('click',function(){
            $('input[type=checkbox][tag]').prop('checked',$(this).prop('checked'));
        });
        $('input[type=checkbox][tag]').on('click',function(){
            var parent_id = $(this).attr('parent_id');
            var pid = $(this).val();
            if(0 == parent_id){//一级模块
                // if(!$(this).prop('checked')){//取消一级模块，则一并取消所有二级模块权限
                $('input[type=checkbox][parent_id='+pid+']').prop('checked',$(this).prop('checked'));
                // }
            }else{//二级模块
                if($(this).prop('checked')){//选择二级模块后，需一并选择一级模块
                    $('input[type=checkbox][value='+parent_id+']').prop('checked',$(this).prop('checked'));
                }
                if($(this).attr('list_id') && $(this).attr('list_id') > 0){//关联操作
                    var list_id = $(this).attr('list_id');
                    $(this).parent().parent().siblings('ul[pid='+list_id+']').
                    find('input[type=checkbox]').prop('checked',$(this).prop('checked'));
                }
            }
        });
        var is_submit = 1;
        $('#submitBtn').on('click',function(){
            var idx = 1;
            if(1 != is_submit){
                return false;
            }
            is_submit = 2;
            $('#PermisForm').submit();
        });
        $('#cancelBtn').on('click',function(){
            location.href = '../index';
        });
    });
</script>