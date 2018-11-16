<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

</head>
<body>
<!-- 加载编辑器的容器 -->
<script id="ue-editor" name="content" type="text/plain">
    这里写你的初始化内容
</script>

</body>
</html>
<!-- 配置文件 -->
<script src="/laravel-u-editor/ueditor.config.js"></script>
{{--<script src="/laravel-u-editor/lang/zh-cn/zh-cn.js"></script>--}}
<!-- 编辑器源码文件 -->
<script src="/laravel-u-editor/ueditor.all.min.js"></script>

<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('ue-editor', {
        initialFrameHeight: 450
    });
    ue.ready(function () {
        console.log("ue ready");
    });
</script>
