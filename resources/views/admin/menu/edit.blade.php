@include('layouts.header')

<script src="/public/js/jquery.min.js"></script>
<div class="content-wrapper">
    <!-- 内容标题 -->
    <section class="content-header">
      <h1>
        <small>菜单管理/</small> 编辑菜单
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li><a href="#">菜单管理</a></li>
        <li class="active">编辑菜单</li>
      </ol>
    </section>

    <!-- 内容区 -->
    <form action="{{url('admin/Menu/update')."?id=".$id}}"
          method="post"
          id='EditForm'>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header with-border">
                   <h3 class="box-title">基础信息 </h3>
                </div>
                <div class="box-body detail-page-sy">
                    <dl class="dl-horizontal">
                        <?php
                        if($menu->parent_id > 0){
                        ?>
                          <dt>上级菜单：</dt>
                          <dd>
                            <div class="col-xs-4 row">
                                <input type="hidden" name="menu[parent_id]" fld='parent_id'
                                    style='width:245px;' class="form-control"
                                    value="<?= $menu->parent_id ?>">
                                <?= $parent->name ?>
                            </div>
                            <div style='color:red;'></div>
                          </dd>
                        <?php
                        }
                        ?><br>
                      <dt>菜单名：</dt>
                      <dd>
                        <div class="col-xs-4 row">
                          <input type="text" name="menu[name]" fld='name'
                            style='width:245px;' class="form-control"
                            value="<?php echo (isset($menu->name)?$menu->name:'') ?>">
                        </div>
                        <div style='color:red;'></div>
                      </dd><br>
                        <?php
                            if($menu->parent_id > 0){
                        ?>
                      <dt>url地址：</dt>
                      <dd>
                        <div class="col-xs-4 row">
                            <input type="text" name="menu[url]" fld='url'
                                style='width:245px;' class="form-control"
                            value="<?php echo (isset($menu->url)?$menu->url:'') ?>">
                        </div>
                        <div style='color:red;'></div>
                      </dd><br>
                      <dt>是否菜单展示：</dt>
                      <dd>
                        <div class="col-xs-4 row">
                            <select name="menu[type]" fld='type' style='width:150px;' class='form-control'>
                                <option value="1" <?php if($menu->type == 1){ echo 'selected';} ?>>展示</option>
                                <option value="2" <?php if($menu->type == 2){ echo 'selected';} ?>>不展示</option>
                            </select>
                        </div>
                        <div style='color:red;'></div>
                      </dd><br>
                      <?php
                        }
                        ?>
                      <dt>描述：</dt>
                      <dd>
                         <div class="col-xs-4 row">
                            <input type="text" name="menu[remark]" fld='remark'
                            style='width:245px;' class="form-control"
                            value="<?php echo (isset($menu->remark)?$menu->remark:'') ?>">
                        </div>
                        <div style='color:red;'></div>
                      </dd><br>
                      <dt>排序：</dt>
                      <dd>
                         <div class="col-xs-4 row">
                            <input type="text" name="menu[sort]" fld='sort'
                            style='width:245px;' class="form-control"
                            value="<?php echo (isset($menu->sort)?$menu->sort:'') ?>">
                        </div>
                        <div style='color:red;'></div>
                      </dd>

                    </dl>
                </div>
                <!-- /.box-body -->
                <div class="box-footer align-center">
                    <button type="button" class="btn btn-primary btn-big bg-pink" id='submitBtn'>编辑</button>
                    <a href="{{url('admin/Menu/index')}}" class="btn btn-default btn-big">返回</a>
                </div>
              </div>
            </div>
            <!-- /.col -->
          </div>
    </section>
    </form>
</div>
@include('layouts.footer')
<script>
$(function(){
    var is_submit = 1;
    $('#submitBtn').on('click',function(){
        var idx = 1;
        $('#EditForm input[fld=name]').val($.trim($('#EditForm input[fld=name]').val()));
        if($('#EditForm input[fld=name]').val() == ''){
            $('#EditForm input[fld=name]').parent().next().html('请输入权限组名称。');
            idx = 0;
        }else{
            $('#EditForm input[fld=name]').parent().next().html('');
        }
        if(0 == idx){
            return false;
        }
        if(1 != is_submit){
            return false;
        }
        is_submit = 2;
        $('#EditForm').submit();
    });
    $('#cancelBtn').on('click',function(){
        location.href = "{{url('admin/Menu/index')}}";
    });
    var error_code = "<?= (isset($error_code)?$error_code:'')?>";
    if(error_code != ''){
        $('#EditForm input[fld=name]').parent().next().html(error_code);
    }
});
</script>
