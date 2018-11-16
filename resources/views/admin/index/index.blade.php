@include('layouts.header')
<script type="text/javascript">
    $(document).ready(function() {$("#active").select2(); });
</script>
<div class="content-wrapper">
    <!-- 内容标题 -->
    <section class="content-header">
        <h1>
            首页
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="#">生成</a></li>
            <li class="active">列表</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content pd-top0">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body solist">

                        <table class="table table-bordered text-center">
                            <thead>
                            <tr>

                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer solist clearfix page-wrap">
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
<link rel="stylesheet" href="/public/css/daterangepicker/daterangepicker-bs3.css"><!--日期选择css-->
<script src="/public/plugins/daterangepicker/daterangepicker.js"></script><!--日期选择js-->
<script type="text/javascript">
    $(function() {
        // 日期控件
        $('#reservation').daterangepicker({
            format: 'yyyy-mm-dd',
            //  "singleDatePicker": true,
            language: 'auto',
            // maxDate : moment(), //最大时间
            applyClass : 'btn-small btn-primary blue',
        });

        $('#reservation').daterangepicker().val('');
        var time=$('#timehidden').val();
        if(time!=''){
            $('#reservation').val(time);
        }
    })
</script>


@include('layouts.footer')