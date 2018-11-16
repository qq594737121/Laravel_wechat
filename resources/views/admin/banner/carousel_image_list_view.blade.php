@include('layouts.header')


        <!-- 内容区域 -->
<div class="content-wrapper">
    <!-- 内容标题 -->
    <section class="content-header">
        <h1>轮播图管理</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="#">系统管理</a></li>
            <li class="active">轮播图管理</li>
        </ol>
    </section>
    <!-- 内容区 -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box">
                    <div class="tabbable"> <!-- Only required for left/right tabs -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="box-header with-border">
                                    <div class="row">
                                        <form method="post" action="{{URL('admin/CarouselImage/get_child_carousel?id='.$parent_id)}}">
                                            <div class="col-xs-1 w140">
                                                <a href="{{URL('admin/CarouselImage/add_child_carousel?parent_id='.$parent_id)}}" class="btn btn-block bg-pink btn-flat">新增</a>
                                            </div>
                                            <div class="col-xs-2">
                                                <input type="text" name='title' class="form-control" placeholder="标题" value="<?php if(isset($title)){ echo $title; } ?>">
                                            </div>
                                            <div class="col-xs-2">
                                                <select name="is_show" class="form-control">
                                                    <option value="">请选择</option>
                                                    <option value="1" <?php if(isset($is_show) && $is_show ==1){ echo "selected=selected";}?>>不展示</option>
                                                    <option value="2" <?php if(isset($is_show) && $is_show ==2){ echo "selected=selected";}?>>展示</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-1">
                                                <button type="submit" class="btn btn-block btn-default btn-flat">筛选</button>
                                            </div>
                                            
                                            <div class="col-xs-1">
                                                <a href="{{URL('admin/CarouselImage/index')}}" class="btn btn-block btn-default btn-flat">返回</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                        <tr>
                                            <th style="word-wrap: break-word;width: 10%;">标题</th>
                                            <th style="word-wrap: break-word;width: 10%;">图片</th>
                                            <th style="word-wrap: break-word;width: 10%;">跳转链接</th>
                                            <th style="word-wrap: break-word;width: 10%;">排序</th>
                                            <th style="word-wrap: break-word;width: 10%;">是否展示</th>
                                            <th style="word-wrap: break-word;width: 10%;">添加时间</th>
                                            <th style="word-wrap: break-word;width: 10%;">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($data as $key=>$val){ ?>
                                            <tr>
                                                <td><?php echo $val['title'];?></td>
                                                <td>
                                                    <img id="imghead" width=50px  border=0 src='<?php echo $val['image_url']; ?>'>
                                                </td>
                                                <td><?php echo $val['jump_url'];?></td>
                                                <td><?php echo $val['sort'];?></td>
                                                <td>
                                                    @if($val['is_show']=='1')
                                                        <span> 不展示</span>
                                                    @else
                                                        <span> 展示</span>
                                                    @endif
                                                </td>
                                                <td><?php echo $val['add_time'];?></td>
                                                <td>
<!--                                                    <a href="--><!--">查看</a>-->
                                                    <a class="btn btn-default btn-xs btn-flat margin" href="{{URL("admin/CarouselImage/add_child_carousel?id=".$val['id']."&parent_id=".$val['parent_id'])}}">编辑</a>
                                                    @if($val['is_show']=='1')
                                                        <a class="btn btn-default btn-xs btn-flat margin" href="{{URL("admin/CarouselImage/show?id=".$val['id']."&parent_id=".$val['parent_id'])}}">展示</a>
                                                    @else
                                                        <a class="btn btn-default btn-xs btn-flat margin" href="{{URL("admin/CarouselImage/no_show?id=".$val['id']."&parent_id=".$val['parent_id'])}}">不展示</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix">
                                    <div class="pageMsg">
                                        <span class="page1">第<span> {{$data->currentPage()}}</span>页</span>
                                        <span class="page1">共<span>{{$data->lastPage()}}</span>页</span>
                                        <span class="page1">共<span>{{$data->total()}}</span>条</span>
                                    </div>
                                    <div class="page-input">
                                        {{$data->links()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
@include('layouts.footer')
