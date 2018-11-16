@include('layouts.header')

<script src="/public/js/jquery.min.js"></script>
<div class="content-wrapper">
    <!-- 内容标题 -->
    <section class="content-header">
        <h1>
            菜单管理

        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>首页</a></li>
            <li><a href="#">菜单管理</a></li>
            <li class="active">菜单列表</li>
        </ol>
    </section>

    <div id='content'>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- /.box -->
                    <div class="box">

                        <div class="box-header">
                            <div id='SearchForm'>
                                <div class="row " style="line-height:33px; display: flex;">
                                    <div class="col-xs-3">
                                        <a
                                            href="{{url('admin/Menu/create')}}"
                                            class="btn btn-primary bg-pink btn-big"
                                        >
                                            新建
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered" id='menutab'>
                                <thead><tr>
                                    <th>菜单</th>
                                    <th>url</th>
                                    <th>描述</th>
                                    <th>排序</th>
                                    <th>是否菜单展示</th>
                                    <th>操作</th>
                                </tr></thead>
                                <tbody>
                                <?php
                                $idx = 1;
                                foreach ($menu_list as $key=>$menu) {
                                ?>
                                <tr style='text-align: left;'>
                                    <td style='padding-left: 4px;'><?= $menu['info']['name']?></td>
                                    <td style='padding-left: 4px;'><?= $menu['info']['url']?></td>
                                    <td style='padding-left: 4px;'><?= $menu['info']['remark']?></td>
                                    <td style='padding-left: 4px;'><?= $menu['info']['sort']?></td>
                                    <td style='padding-left: 4px;'>--</td>
                                    <td style='padding-left: 4px;'>
                                        <a class="btn btn-default btn-xs btn-flat margin"
                                           href='{{url('admin/Menu/create').'?id='.$menu['info']['id']}}'
                                           tag='edit_link'>
                                            新增
                                        </a>
                                        <a class="btn btn-default btn-xs btn-flat margin"
                                           href='{{url('admin/Menu/edit').'?id='.$menu['info']['id']}}'
                                           tag='edit_link'>
                                            编辑
                                        </a>
                                        <a class="btn btn-default btn-xs btn-flat margin"
                                           href='{{url('admin/Menu/enable').'?id='.$menu['info']['id'].'&status='.$menu['info']['status']}}'
                                           tag='enable_link'>
                                            @if($menu['info']['status']=='1')
                                                <span> 禁用</span>
                                            @else
                                                <span> 启用</span>
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                if(isset($menu['list'])){
                                foreach ($menu['list'] as $key=>$list) {
                                $left_width = '30';$is_show = '';$menutag = 'menutag';
                                $list['info']['url'] = strtr($list['info']['url'],['\\'=>'/']);
                                $namearr = explode('/', $list['info']['url']);
                                $urlarr = explode('/',$list['info']['url']);
                                ?>
                                <tr style='text-align: left;cursor:pointer;display: <?=$is_show?>' mid="<?= $key?>"
                                    group='<?=$urlarr[0]?>' controller='<?=$urlarr[1]?>' method='<?=$urlarr[2]?>'
                                    pid="<?=$list['info']['parent_id']?>" <?=$menutag?>>

                                    <td style='padding-left: <?=$left_width?>px;'><?= $list['info']['name']?></td>
                                    <td style='padding-left: 4px;'><?= $list['info']['url']?></td>
                                    <td style='padding-left: 4px;'><?= $list['info']['remark']?></td>
                                    <td style='padding-left: 4px;'><?= $list['info']['sort']?></td>
                                    <td style='padding-left: 4px;'>
                                        <?php if(1 == $list['info']['type']){ ?>
                                        展示
                                        <?php }else if(2 == $list['info']['type']){ ?>
                                        不展示
                                        <?php }else{?>
                                        当前页展示
                                        <?php } ?></td>
                                    <td style='padding-left: 4px;'>
                                        <a class="btn btn-default btn-xs btn-flat margin"
                                           href='{{url('admin/Menu/edit').'?id='.$list['info']['id']}}'
                                           tag='edit_link'>编辑</a>
                                        <a class="btn btn-default btn-xs btn-flat margin"
                                           href='{{url('admin/Menu/enable').'?id='.$list['info']['id'].'&status='.$list['info']['status']}}'
                                           tag='enable_link'>
                                                @if($menu['info']['status']=='1')
                                                    <span> 禁用</span>
                                                @else
                                                    <span> 启用</span>
                                                @endif
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                }
                                }
                                }
                                ?>
                                </tbody></table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>
</div>
<script>
    $(function(){
        var is_submit = 1;
        $('a[tag=delete_link]').on('click',function(){
            if(confirm('确定要删除吗？')){
                return true;
            }
            return false;
        });
        $('#createBtn').on('click',function (){
            location.href =  "{{url('admin/Menu/create')}}";
        });
        $('#menutab tr[menutag]').on({
            'click':function (){
                var group      = $(this).attr('group');
                var controller = $(this).attr('controller');
                var method     = $(this).attr('method');
                var menutag    = $(this).attr('menutag');
                var pid        = $(this).attr('pid');
                var type       = '';
                if(1 == menutag)
                {
                    $('#menutab tr[pid='+pid+'][group='+group+'][controller='+controller+']').not('[method='+method+']').hide();
                    $(this).attr('menutag',0);
                }else{//show
                    $('#menutab tr[pid='+pid+'][group='+group+'][controller='+controller+']').not('[method='+method+']').show();
                    $(this).attr('menutag',1);
                }
            }
        });
    });
</script>
@include('layouts.footer')
