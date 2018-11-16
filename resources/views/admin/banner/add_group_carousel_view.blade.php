@include('layouts.header')


        <!-- 内容区域 -->
<div class="content-wrapper">
    <!-- 内容标题 -->
    <section class="content-header">
        <h1>
            轮播图管理
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="#">商品管理</a></li>
            <li class="active">轮播图管理</li>
        </ol>
    </section>

    <!-- 内容区 -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">新增轮播图</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{URL('admin/CarouselImage/save_group_carousel')}}" method="post">
                            <dl class="dl-horizontal">
                                <dt>标题：</dt>
                                <dd>
                                    <div class="col-md-4 row">
                                        <input type="text" class="form-control" name="title" value="<?php if(isset($data['title'])){ echo $data['title']; }?>"/>
                                        <input type="hidden" class="form-control" name="id" value="<?php if(isset($data['id'])){ echo $data['id']; }else{ echo 0;}?>"/>
                                    </div>
                                </dd>
                                <dt>轮播显示页面：</dt>
                                <dd>
                                    <div class="col-md-4 row">
                                        <input type="text" class="form-control" name="show_page" value="<?php if(isset($data['show_page'])){ echo $data['show_page']; }?>"/>
                                    </div>
                                </dd>
                                <dt>排序：</dt>
                                <dd>
                                    <div class="col-md-4 row">
                                        <input type="text" class="form-control" name="sort" value="<?php if(isset($data['sort'])){ echo $data['sort']; }?>"/>
                                    </div>
                                </dd>
                                
                               
                            </dl>
                            <div class="footer-diply-cernter">
                                <input type="submit" class="btn bg-pink btn-flat" value="提交"  style="width:110px;"/>
                                <a href="{{URL('admin/CarouselImage/index')}}" class="btn bg-gray btn-flat" id='cancelBtn' style="width:110px;">返回</a>
                            </div>
                        </form>
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
@include('layouts.footer')

