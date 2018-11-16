<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>swellfun</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="/public/dist/css/ionicons.min.css">
    <link rel="stylesheet" href="/public/css/daterangepicker/daterangepicker-bs3.css"><!--日期选择css-->
    <link rel="stylesheet" href="/public/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/public/dist/css/skins/_all-skins.min.css">
    <!-- 自定义样式 -->
    <link rel="stylesheet" href="/public/css/admin.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<script src="/public/plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="/public/js/select2.js"></script>
<script>

    var menuinfo;
    $.ajax({
        type : "get",
        url: '{{URL('menu/getMenuInfo')}}',
        data:{url:"<?php echo app('request')->path(); ?>"},
//        dataType: 'json',
        async: false, //同步传输，并添加返回值，返回值应为已定义的全局变量 如a
        success : function(data)
        {
            menuinfo = data;
        }
    });
//    console.log(menuinfo);

    // var menuinfo = '{"active":["19","27"],"dataList":[{"id":"19","title":"\u7efc\u5408\u7ba1\u7406","icon":"fa fa-dashboard","url":"","list":[{"id":"72","title":"\u4ea7\u54c1\u4fe1\u606f","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ProductManage\/product_index"},{"id":"27","title":"\u6d3b\u52a8\u5217\u8868","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ActiveManage\/index"},{"id":"59","title":"\u8f6e\u64ad\u56fe\u5217\u8868","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/CarouselImage\/index"},{"id":"91","title":"\u4e2d\u8f6c\u4e8c\u7ef4\u7801","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ActiveManage\/qrcode_index"},{"id":"38","title":"\u4f1a\u5458\u65e5\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/MemberActivity\/index"}]},{"id":"32","title":"\u7edf\u8ba1\u7ba1\u7406","icon":"fa fa-cubes","url":"","list":[{"id":"80","title":"tracking\u76d1\u63a7","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Prpostlog\/prpostlog_index"},{"id":"63","title":"member_byday","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/MemberManage\/member_byday"},{"id":"64","title":"share_byday","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/MemberManage\/share_byday"},{"id":"33","title":"\u7edf\u8ba1\u5217\u8868","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Cancelmember\/index"},{"id":"43","title":"\u6d3b\u8dc3\u5ea6\u7edf\u8ba1","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Report\/active_count"},{"id":"90","title":"\u6d3b\u8dc32\u6b21by_day","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Report\/active_byday"}]},{"id":"30","title":"\u4f1a\u5458\u7ba1\u7406","icon":"fa fa-table","url":"","list":[{"id":"31","title":"\u4f1a\u5458\u5217\u8868","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/MemberManage\/index"}]},{"id":"34","title":"\u5206\u7c7b\u7ba1\u7406","icon":"fa fa-money","url":"","list":[{"id":"37","title":"\u5206\u7c7b\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/GoodsCategory\/index"}]},{"id":"40","title":"\u8ba2\u5355\u7ba1\u7406","icon":"fa fa-gift","url":"","list":[{"id":"41","title":"\u8ba2\u5355\u5217\u8868","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Order\/index"}]},{"id":"20","title":"\u77ed\u4fe1\u7ba1\u7406","icon":"fa fa-home","url":"","list":[{"id":"21","title":"\u77ed\u4fe1\u5217\u8868","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/SendManage\/index"},{"id":"24","title":"\u65b0\u5efa\u77ed\u4fe1","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/SendManage\/add"}]},{"id":"3","title":"\u7cfb\u7edf\u8bbe\u7f6e","icon":"fa fa-group","url":"","list":[{"id":"7","title":"\u673a\u6784\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Group\/index"},{"id":"12","title":"\u89d2\u8272\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/permis\/index"},{"id":"17","title":"\u7528\u6237\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/User\/index"}]},{"id":"60","title":"\u975e\u9057\u7ba1\u7406","icon":"fa fa-key","url":"","list":[{"id":"61","title":"\u79ef\u5206\u6350\u8d60\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Donation\/donation_index"},{"id":"62","title":"\u5439\u91d1\u7b94\u6570\u636e","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Gold\/gold_index"}]},{"id":"86","title":"\u79ef\u5206\u5546\u57ce","icon":"fa fa-cogs","url":"","list":[{"id":"36","title":"\u79ef\u5206\u5546\u54c1","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Goods\/index"}]},{"id":"65","title":"\u5728\u7ebf\u5546\u57ce","icon":"fa fa-dashboard","url":"","list":[{"id":"66","title":"\u5546\u54c1\u5217\u8868","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Product\/good_index"},{"id":"87","title":"\u5728\u7ebf\u5546\u57ce\u8ba2\u5355\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ShopManage\/order_index"},{"id":"88","title":"\u5728\u7ebf\u5546\u57ce\u8ba2\u5355\u914d\u7f6e","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ShopManage\/order_config"},{"id":"89","title":"\u5728\u7ebf\u5546\u57ce\u6d88\u606f\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ShopManage\/message_list"}]},{"id":"67","title":"\u5361\u5238\u7ba1\u7406","icon":"fa fa-cubes","url":"","list":[{"id":"68","title":"\u5fae\u4fe1\u4f18\u60e0\u5238","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/CardManage\/card_index"},{"id":"71","title":"\u5151\u6362\u5238\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/CardManage\/exchange_index"}]},{"id":"69","title":"\u58f9\u5e2d\u7ba1\u7406","icon":"fa fa-table","url":"","list":[{"id":"70","title":"\u58f9\u5e2d\u95e8\u5e97\u5217\u8868","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/Meal\/meal_index"}]},{"id":"73","title":"\u62a5\u8868\u6c47\u603b","icon":"fa fa-money","url":"","list":[{"id":"74","title":"\u7edf\u8ba1\u4f1a\u5458\u4e14\u5173\u6ce8\u7684\u7c89\u4e1d","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ReportManage\/member_fans"},{"id":"75","title":"\u7edf\u8ba1\u622a\u81f3\u65e5\u671f\u7684\u7c89\u4e1d\u6570\u636e","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ReportManage\/fans_data"},{"id":"76","title":"\u7edf\u8ba1\u4f1a\u5458\u79ef\u5206\u589e\u52a0\u660e\u7ec6","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ReportManage\/member_scoredetail"},{"id":"77","title":"\u7edf\u8ba1\u58f9\u5e2d\u5206\u4eab\u6570\u636e","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ReportManage\/mealshare_data"},{"id":"78","title":"\u7edf\u8ba1\u641c\u7d22\u5173\u952e\u8bcd\u6570\u636e","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ReportManage\/mealsearch_data"},{"id":"79","title":"\u7edf\u8ba1\u4f18\u60e0\u5238\u6570\u636e","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ReportManage\/card_receipt_data"},{"id":"92","title":"\u7edf\u8ba1\u4e2d\u8f6c\u4e8c\u7ef4\u7801","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/ReportManage\/qrcode_data"}]},{"id":"93","title":"\u62bd\u5956\u7ba1\u7406","icon":"fa fa-gift","url":"","list":[{"id":"94","title":"\u6d3b\u52a8\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/BigwheelManage\/active_index"},{"id":"95","title":"\u5956\u54c1\u7ba1\u7406","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/BigwheelManage\/prize_index"},{"id":"96","title":"\u5956\u54c1\u6570\u636e","url":"http:\/\/www.sjfadmin.com\/index.php\/admin\/BigwheelManage\/prize_data"}]}]}';
    var  menulist = '';
    menulist = eval('('+ menuinfo +')')?eval('('+ menuinfo +')'):JSON.parse(menuinfo);
    var username = '';


</script>

<div class="wrapper">
    <!-- 头部信息 -->
    <header class="main-header"></header>
    <!--  左侧导航 -->
    <aside class="main-sidebar"></aside>
    <!-- 内容区域 -->
    <link href="/public/css/select2.css" rel="stylesheet"/>
    <link href="/public/css/select2.css" rel="stylesheet"/>
    {{--<script src="/public/js/select2.js"></script>--}}
    {{--<script>--}}
        {{--$(document).ready(function() {$("#active").select2(); });--}}
    {{--</script>--}}
