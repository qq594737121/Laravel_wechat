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
                        <form action="{{URL('admin/CarouselImage/upload_image')}}" method="post" enctype="multipart/form-data">
                            <dl class="dl-horizontal">
                                <dt>标题：</dt>
                                <dd>
                                    <div class="col-md-3 row">
                                        <input type="text" class="form-control" name="title" value="<?php if(isset($child_carousel->title)){ echo $child_carousel->title;}else{ echo '';}?>"/>
                                        <input type="hidden" class="form-control" name="parent_id" value="<?php if(isset($parent_id)){ echo $parent_id;}else{ echo 0;}?>"/>
                                        <input type="hidden" class="form-control" name="id" value="<?php if(isset($child_carousel->id)){ echo $child_carousel->id;}else{ echo 0;}?>"/>
                                    </div>
                                </dd>
                                <dt>跳转地址：</dt>
                                <dd>
                                    <div class="col-md-3 row">
                                        <input type="text" class="form-control" name="jump_url" value="<?php if(isset($child_carousel->jump_url)){ echo $child_carousel->jump_url;}else{ echo '';}?>"/>
                                    </div>
                                </dd>
                                <dt>排序：</dt>
                                <dd>
                                    <div class="col-md-3 row">
                                        <input type="text" class="form-control" name="sort"
                                               value="<?php if(isset($child_carousel->sort)){ echo $child_carousel->sort;}else{ echo '';}?>"/>
                                    </div>
                                </dd>
                                <dt>上传图片：</dt>
                                <dd>
                                    <div class="col-md-3 row">
                                        <input type="file" class="form-control" name="image_url"
                                               value="<?php if(isset($child_carousel->image_url)){ echo $child_carousel->image_url;}else{ echo '';}?>"/>
                                    </div>
                                </dd>
                                <?php if(isset($child_carousel->image_url)){ ?>
                                    <dt>图片：</dt>
                                    <dd>
                                        <div class="col-md-3 row">
                                            <img src="<?php echo $child_carousel->image_url;?>">
                                        </div>
                                    </dd>
                                <?php } ?>
                                
                            </dl>
                            <div class="footer-diply-cernter">
                                <input type="submit" class="btn bg-pink btn-flat" value="提交"  style="width:110px;"/>
                                <a href="{{URL('admin/CarouselImage/get_child_carousel?id='.$parent_id)}}" class="btn bg-gray btn-flat" id='cancelBtn' style="width:110px;">返回</a>
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
<!-- ./wrapper -->
@include('layouts.footer')
