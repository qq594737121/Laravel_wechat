<!DOCTYPE html>
<html class="lockscreen">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>水井坊</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/public/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="/public/dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="/public/dist/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/public/plugins/datatables/dataTables.bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/public/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/public/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/public/css/admin.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.åjs"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-bg">
<div class="login-box">
    <div class="login-logo">
        <img src="/public/img/login-logo.jpg" alt=''>
    </div>
    <!-- /.login-logo -->
    <div class="row">
        <div class="col-xs-12">
            <div class="login-box-body login-bg">
                <form  action="{{URL('admin/login/signin')}}" method="post"  id='LoginForm'>
                    <div class="form-group">
                        <label for="">用户名</label>
                        <input type="text" class="form-control"
                               name="user[name]" id="name" fld='name' value="<?= $user_name?>" style="padding-left:60px;color:#fff">
                        <label style="color:red;"></label>
                    </div>
                    <div class="form-group">
                        <label for="">密码</label>
                        <input type="password" class="form-control"
                               name="user[password]" id="password" fld='password' style="color:#fff">
                        <label style="color:red;"></label>
                    </div>
                    <div class="form-group">
                        <label for="">验证码</label>
                        <input type="text" name="verify_text" id="verify_text" class="form-control" value="" style="width: 110px;float:left;color:#fff">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <img src="{{URL('admin/verify_image')}}" alt="验证码" id="verify_code" class="yz_img" style='width:80px;'/>
                        <a href="javascript:;" onclick="return codes();" class="change_yz" style="color:#fff;">换一张</a>

                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="login-btn">
                                <button type="button" class="btn btn-yellow" id='submitBtn'>
                                    登录
                                </button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.login-box-body -->
    <img src="/public/img/login-bottom-bg.jpg" alt="">
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="/public/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/public/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/public/plugins/iCheck/icheck.min.js"></script>
<script src="/public/base64/base64.js"></script>
<script type="text/javascript">
    function codes(){
        $('#verify_code').attr('src',"{{URL('admin/verify_image')}}?verify_image?r=" + Math.random());
    }
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        var is_submit = 1;
        $('#submitBtn').on('click',function(){
            var name     = $("#name").val();
//                var password = $("#password").val();
            var b = new Base64();
            var password = b.encode($("#password").val());
            var verify_text=$("#verify_text").val();
                if(name==''){
                    alert("请输入用户名");
                    return false;
                }
                if(password==''){
                    alert("输入密码");
                    return false;
                }
            if(verify_text==''){
                alert("请输入验证码");
                return false;
            }
            $.ajax({
                url:"{{URL('admin/login/signin')}}",
                type:'post',
                data:{name:name,password:password,verify_text:verify_text},
                success:function(msg)
                {
                    if(msg.code!=200)
                    {
                        alert(msg.data);
                        if(msg.code==6)
                        {
                             $('#verify_code').attr('src',"{{URL('admin/verify_image')}}?verify_image?r=" + Math.random());
                        }
                        return false;
                    }else{
                        window.location.href="{{URL('admin/admin/index')}}";
                    }
                }
            })
        });
    });
</script>
</body>
</html>
