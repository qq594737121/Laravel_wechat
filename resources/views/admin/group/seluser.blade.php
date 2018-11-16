@include('layouts.header')
<!-- 内容区域 -->
<div class="content-wrapper">

    <!-- 内容标题 -->
    <section class="content-header">
        <h1>
            <small>机构管理</small>
            /用户列表
        </h1>
        <ol class="breadcrumb display-none">
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
                        <div class="detail-page-sy">
                            <form class="form-horizontal submitForm">
                                <div class="detail-page-border">
                                    <div class="detail_con">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                            <tr>
                                                <th>用户名</th>
                                                <th>昵称</th>
                                                <th>状态</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($lists as $item)
                                                <tr>
                                                    <td>{{$item['name']}}</td>
                                                    <td>{{$item['nick_name']}}</td>
                                                    <td>
                                                        @if($item['status']=='1')
                                                            <span> 激活</span>
                                                        @else
                                                            <span> 关闭</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="box-footer align-center">
                                        <a href="javascript:window.history.go(-1);" class="btn btn-default btn-big">取消</a>
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
@include('layouts.footer')