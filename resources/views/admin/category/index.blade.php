@include('layouts.header')
        <!-- 内容区域 -->

<!-- 内容区域 -->
<div class="content-wrapper">
    <!-- 内容标题 -->
    <section class="content-header">
        <h1>
            分类管理
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
                        <button type='button' class="btn btn-primary bg-pink btn-big" data-toggle="modal"
                                data-target="#addModal">+ 添加新分类</button>
                        <p>&nbsp;</p>
                        <table class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <th>分类名称</th>
                                <th>logo</th>
                                <th>long_logo</th>
                                <th>排序</th>
                                <th>是否在前台显示</th>
                                <th>创建时间</th>
                                <th>类型</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list['list'] as $key => $value)
                            <tr>
                                <td>
                                    <p style="text-align:left;padding-left:
                                    <?php echo $value['deep']*10?>px;"
                                    >
                                        {{$value['prefix_name']}}
                                         {{$value['cat_name']}}
                                    </p>
                                </td>
                                <td>
                                    <img src="{{$value['logo']}}" alt="" style="width:50px;height:50px;;">
                                </td>
                                <td>
                                    <img src=" {{$value['long_logo']}}" alt="" style="width:50px;height:50px;;">
                                </td>
                                <td>
                                    {{$value['sort']}}
                                </td>
                                <td>
                                    @if($value['include_in_menu']=='1')
                                        <span>是</span>
                                    @else
                                        <span>否</span>
                                    @endif
                                </td>
                                <td>
                                    <?php echo $value['created_at'];?>
                                </td>
                                <td>
                                    <?php echo $value['category_type'];?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#addModals"
                                            onclick="return givevalue(<?php echo $value['cat_id'];?>)">添加
                                    </button>
                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal"  data-target="#editModal"
                                            onclick="return xgs(<?php echo $value['cat_id'];?>)">修改
                                    </button>
                                    <button type="button" class="btn btn-default btn-xs"
                                            onclick="return sc(<?php echo $value['cat_id'];?>)">删除
                                    </button>
                                </td>
                            </tr>

                            @endforeach
                            </tbody>
                        </table>
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
                        添加新分类
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal submitForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分类名称：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name='name' id='name' placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">排序：</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" placeholder=""  name='sort' id='sort'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分类类型：</label>
                            <div class="col-sm-6">
                                <input type="radio" value="1" name="menu_fcategory_type" />积分商城
                                <input type="radio" value="2" name="menu_fcategory_type" />在线商城
                                <input type="radio" value="3" name="menu_fcategory_type" checked/>通用
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否前台菜单展示：</label>
                            <div class="col-sm-6">
                                <input type="radio" value="1" name="menu_fstatus" />是
                                <input type="radio" value="0" name="menu_fstatus" checked/>否
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 control-label">
                                logo:
                            </div>
                            <div class="col-xs-8 tl img_con">
                                <form enctype="multipart/form-data" method="post" id="uploadForm">
                                    <input type="file" name="file" accept="image/png,image/jpeg"  id="menu_bgimg" class="up_file_input" m-change="upFile" style="display: inline-block;">
                                    <p style="font-size: 12px;">*建议尺寸:500×500像素</p>
                                </form>
                                <img src="" class="img_tag" alt="" style="max-height: 200px;max-width: 250px;">
                                <input type="hidden" id="inp_img_url" class="needEle add1_base_image" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 control-label">
                                long_logo:
                            </div>
                            <div class="col-xs-8 tl img_con">
                                <form enctype="multipart/form-data" method="post" id="uploadForm">
                                    <input type="file" name="file" accept="image/png,image/jpeg"  id="menu_bgimg" class="up_file_input" m-change="upFile" style="display: inline-block;">
                                    <p style="font-size: 12px;">*建议尺寸:500×500像素</p>
                                </form>

                                <img src="" class="img_tag" alt="" style="max-height: 200px;max-width: 250px;">
                                <input type="hidden" id="inp_img_url" class="needEle long_logo_1"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center !important">
                <button type="button" class="btn btn-primary" onclick="return tj(0)">确认保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
    <div class="modal fade" id="addModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog " id="add_modal_dialog" style="width: 40%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">
                        添加新分类
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal submitForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分类名称：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name='names' id='names' placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">排序：</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" placeholder=""  name='sorts' id='sorts'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分类类型：</label>
                            <div class="col-sm-6">
                                <input type="radio" value="1" name="menu_category_type" />积分商城
                                <input type="radio" value="2" name="menu_category_type" />在线商城
                                <input type="radio" value="3" name="menu_category_type" checked/>通用
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否前台菜单展示：</label>
                            <div class="col-sm-6">
                                <input type="radio" value="1" name="menu_status" />是
                                <input type="radio" value="0" name="menu_status" checked/>否
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 control-label">
                                logo:
                            </div>
                            <div class="col-xs-8 tl img_con">
                                <form enctype="multipart/form-data" method="post" id="uploadForm" action="" style="">
                                    <input type="file" name="file" accept="image/png,image/jpeg"  id="menu_bgimg" class="up_file_input" m-change="upFile" style="display: inline-block;">
                                    <p style="font-size: 12px;">*建议尺寸:500×500像素</p>
                                </form>
                                <img src="" class="img_tag" alt="" style="max-height: 200px;max-width: 250px;">
                                <input type="hidden" id="inp_img_url" class="needEle add2_base_image" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 control-label">
                                long_logo:
                            </div>
                            <div class="col-xs-8 tl img_con">
                                <form enctype="multipart/form-data" method="post" id="uploadForm">
                                    <input type="file" name="file" accept="image/png,image/jpeg"  id="menu_bgimg" class="up_file_input" m-change="upFile" style="display: inline-block;">
                                    <p style="font-size: 12px;">*建议尺寸:500×500像素</p>
                                </form>

                                <img src="" class="img_tag" alt="" style="max-height: 200px;max-width: 250px;">
                                <input type="hidden" id="inp_img_url" class="needEle long_logo_add"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center !important">
                <input type="hidden" value="" id="fid" name="fid" />
                <button type="button" class="btn btn-primary" onclick="return tjs()">确认保存</button>
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
                        修改分类
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal submitForm">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分类名称：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name='updatenames' id='updatenames' placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分类排序：</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" placeholder=""  name='updatesort' id='updatesort'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">分类类型：</label>
                            <div class="col-sm-6">
                                <input type="radio" value="1" name="category_type" />积分商城
                                <input type="radio" value="2" name="category_type" />在线商城
                                <input type="radio" value="3" name="category_type" checked/>通用
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否前台菜单展示：</label>
                            <div class="col-sm-6">
                                <input type="radio" value="1" name="status" />是
                                <input type="radio" value="0" name="status" />否
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 control-label">
                                logo:
                            </div>
                            <div class="col-xs-8 tl img_con">
                                <form enctype="multipart/form-data" method="post" id="uploadForm" action="" style="">
                                    <input type="file" name="file" accept="image/png,image/jpeg"  id="menu_bgimg" class="up_file_input" m-change="upFile" style="display: inline-block;">
                                    <p style="font-size: 12px;">*建议尺寸:500×500像素</p>
                                </form>
                                <img src="" class="img_tag" alt="" style="max-height: 200px;max-width: 250px;">
                                <input type="hidden" id="inp_img_url" class="needEle edit_base_image">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 control-label">
                                long_logo:
                            </div>
                            <div class="col-xs-8 tl img_con">
                                <form enctype="multipart/form-data" method="post" id="uploadForm" action="" style="">
                                    <input type="file" name="file" accept="image/png,image/jpeg"  id="menu_bgimg" class="up_file_input" m-change="upFile" style="display: inline-block;">
                                    <p style="font-size: 12px;">*建议尺寸:500×500像素</p>
                                </form>
                                <img src="" class="img_tag" alt="" style="max-height: 200px;max-width: 250px;">
                                <input type="hidden" id="inp_img_url" class="needEle edit_base_image_logo2">
                            </div>
                        </div>
                    </div>
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
<div class="loading">
    <div class="bouncing-loader">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

<script src="/public/plugins/jQuery/jquery-2.2.3.min.js"></script>
@include('layouts.footer')
<script type="text/javascript">
    var up_file_url = "{{URL('common/qiniu_upload')}}"; //编辑地址
    let sub_status = true;

    //编辑保存信息
    function updates()
    {
        if (sub_status==false) {
            alert("系统繁忙,请稍后...");
            return false;
        }
        var updatenames =   $("#updatenames").val();
        var updatesorts =   $("#updatesort").val();
        var status      =   $("input[name='status']:checked").val();
        var category_type = $("input[name='category_type']:checked").val();
        var _logo         = $(".edit_base_image").val();
        var _long_logo    = $(".edit_base_image_logo2").val();


        var id=$("#id").val();
        if(updatenames==''){
            alert('分类名字不能为空');
            return false;
        };
        if(updatesorts==''){
            alert('排序不能为空');
            return false;
        };
//        if (_logo=='') {
//            alert("请上传一张logo");
//            return false;
//        };

//        if(_long_logo==''){
//            alert(`请添加一张long_logo图片`);
//            $(".edit_base_image_logo2").parents('.img_con').find('.up_file_input').focus();
//            return false;
//        };

        let _s_data = {
            name:updatenames,
            sort:updatesorts,
            id:id,
            status:status,
            category_type:category_type,
            logo:_logo,
            long_logo:_long_logo
        };

        sub_status = false;
        $.ajax({
                    url: '{{URL('admin/Category/updates/')}}',
                    type: 'post',
                    dataType: 'json',
                    data: _s_data,
                })
                .done(function(obj) {
                    sub_status = true;
                    if(obj.code!=200){
                        alert(obj.data);
                        return false;
                    }else{
                        alert(obj.data);
                        window.location.href= '{{URL('admin/Category/index/')}}';
                    }
                })
                .fail(function() {
                    sub_status = true;
                    alert('服务器开小差了...')
                })



    }
    //编辑显示数据
    function xgs(id){
        var id=id;
        $.ajax({
            url:'{{URL('admin/Category/xg/')}}',
            type:'get',
            data:{id:id},
            success:function(data){
                obj = data.data;
                $("#updatenames").attr("value",obj.cat_name);
                $("#updatesort").attr("value",obj.sort);
                $("input[name='status'][value="+obj.include_in_menu+"]").attr("checked",'checked');
                $("input[name='category_type'][value="+obj.category_type+"]").attr("checked",'checked');
                $("#id").attr("value",obj.cat_id);

                activeNewGoods.upfun($(".edit_base_image"),obj.logo,"1"); //显示图片
                activeNewGoods.upfun($(".edit_base_image_logo2"),obj.long_logo,"1"); //显示图片长图
            }
        })
    }
    //新增顶级分类

    function tj(id)
    {
        if (sub_status==false) {
            alert("系统繁忙,请稍后...");
            return false;
        }
        var name    =   $("#name").val();
        var sort    =   $("#sort").val();
        var _logo   =   $(".add1_base_image").val();
        var status  =   $("input[name='menu_fstatus']:checked").val();
        var category_type = $("input[name='menu_fcategory_type']:checked").val();
        let _long_logo_1  = $(".long_logo_1").val();

        if(name==''){
            alert("请输入分类名称");
            return false;
        };

        if(sort==''){
            alert("请输入排序序号");
            return false;
        };

        let _s_data = {
            name:name,
            sort:sort,
            fid:id,
            status:status,
            category_type:category_type,
            logo:_logo,
            long_logo:_long_logo_1
        };

        sub_status = false;

        $.ajax({
                url: '{{URL('admin/Category/add/')}}',
                type: 'post',
                dataType: 'json',
                data: _s_data,
        })
        .done(function(obj) {
            sub_status = true;
            if(obj.code!=200){
                alert(obj.data);
                return false;
            }else{
                alert(obj.data);
                window.location.href='{{URL('admin/Category/index/')}}';
            }
        })
        .fail(function() {
            sub_status = true;
            alert('服务器开小差了...')
            console.log("error");
        })

    }
    function givevalue(id){
        var id=id;
        $("#fid").val(id);
    }
    function  sc(id)
    {
        var id=id;
        $.ajax({
            url:'{{URL('admin/Category/deletes/')}}',
            type:'post',
            data:{id:id},
            success:function(data){
                if(data.code == 200)
                {
                    alert('删除成功');
                    location.href='{{URL('admin/Category/index/')}}';
                }
                if(data.code == 1)
                {
                    alert('不能删除，此类下面有子类');
                    return false;
                }
            }
        })
    }
    //新增子分类
    function tjs()
    {
        if (sub_status==false) {
            alert('系统繁忙,请稍后...');
            return false;
        }
        var name=$("#names").val();
        var sort=$("#sorts").val();
        let _logo = $(".add2_base_image").val();
        let _long_logo_add = $(".long_logo_add").val();

        if(name==''){
            alert("请输入分类名称");
            return false;
        }

        if(sort==''){
            alert("请输入排序序号");
            return false;
        }

//        if(_logo==''){
//            alert(`请上传一张图片`);
//            return false;
//        }
//
//        if(_long_logo_add==''){
//            alert(`请添加一张long_logo图片`);
//            $(".long_logo_add").parents('.img_con').find('.up_file_input').focus();
//            return false;
//        };

        var status = $("input[name='menu_status']:checked").val();
        var category_type = $("input[name='menu_category_type']:checked").val();
        var id=$("#fid").val();

        var _s_data = {
            name:name,
            sort:sort,
            fid:id,
            status:status,
            category_type:category_type,
            logo:_logo
        };

        sub_status = false;

        $.ajax({
                url: '{{URL('admin/Category/add/')}}',
                type: 'post',
                dataType: 'json',
                data: _s_data,
                })
                .done(function(obj) {
                    sub_status = true;
                    if(obj.code!=200){
                        alert(obj.data);
                        return false;
                    }else{
                        alert(obj.data);
                        window.location.href='{{URL('admin/Category/index/')}}';
                    }
                })
                .fail(function() {
                    sub_status = true;
                    alert('服务器开小差了...');
                    console.log("error");
                })

    }

    function ActiveNewGoods(){
        var _this = this;
        this.event = function(_this,type,name)
        {
            $(document).on(type,'['+name+']',function(e){
                var event = $($(this)[0]).attr(name);
                var Fun= event.split(',');
//                try
//                {
                    _this[Fun[0]]($($(this)[0]),Fun[1],e);//在此运行代码
//                }
//                catch(err)
//                {
//                    console.log(1);//在此处理错误
//                }

            });
        }

        this.main = function(){
            this.event(this,'click','l-click');
            this.event(this,'change','m-change');
            this.init();
        }

        // 缩略图删除
        this.delImg = function(ths){
            // console.log("ppp");
            ths.parents('p').remove();
        }
        // 上传图片
        this.upFile = function(ths){
            var _ths_html = $(ths);
            var file = ths.context.files[0];
            var fileType = file.type.split('/');
            // console.log(fileType[0]);
            // console.log(fileType);
            if (fileType[0]!="image") {
                alert("上传文件格式不正确");
                _this.upfun(_ths_html,"","0");
                return false;
            };
            if (file.size>"1048576") {
                alert("图片大小不能超过1M");
                _this.upfun(_ths_html,"","0");
                return false;
            };
            // 获取form内容数据
            var file0 = $(ths).parents('form')[0];

            // 上传loading
            $(".loading").show();

            $.ajax({
                        url: up_file_url,
                        data: new FormData(file0),
                        type: 'post',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        // async:false,
                    })
                    .done(function(res) {
                        $(".loading").hide();

                        if (res.code=="200") {
                            alert("上传成功!");
                            _this.upfun(_ths_html,res.data,"1")
                        }else{
                            alert(res.data);
                            _this.upfun(_ths_html,"","0")
                        }
                    })
                    .fail(function() {
                        $(".loading").hide();
                        alert("系统开小差了~")
                    })
        };

        this.upfun = function(obj,url,type){
            if (type=="1") {
                // 上传成功
                $(obj).parents('.img_con').find('.img_tag').prop('src', url);
                $(obj).parents('.img_con').find('input[type=hidden]').val(url);
            }else if (type=="0") {
                // 上传失败
                console.log(type);
                $(obj).parents('.img_con').find('.img_tag').prop('src', '');
                $(obj).parents('.img_con').find('.menu_bgimg').prop('src', '');
                $(obj).parents('.img_con').find('input[type=hidden]').val('');
                $(obj).val("");
            }
        };
        this.init = function(){

        };
        return this.main();
    }
    var activeNewGoods = new ActiveNewGoods();

</script>
