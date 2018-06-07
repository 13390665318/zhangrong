<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>运营数据</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Public/yy/mobile/bootstrap.min.css" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Public/css/ssi-uploader.css">
    <script src="/Public/js/ssi-uploader.js"></script>
    <script src="/Public/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/yy/mobile/html5shiv.js"></script>
    <script src="/Public/yy/mobile/respond.min.js"></script>
    <script src="/Public/yy/mobile/jquery.min.js"></script>
    <script src="/Public/yy/mobile/bootstrap.min.js"></script>

    <link href="/Public/yy/mobile/style.css" rel="stylesheet">
   <script src="/Public/yy/laydate/laydate.js"></script>
   <link rel="stylesheet" href="/Public/yy/mobile/pagination.css">
    <script type="text/javascript" src="/Public/yy/js/jqplot.js"></script>
<script type="text/javascript" src="/Public/jedate/jedate.js"></script>
    <link rel="stylesheet" href="/Public/yy/select/bootstrap-3.3.4.css">
    <link rel="stylesheet" href="/Public/yy/select/dist/css/bootstrap-select.css">
<script src="/Public/yy/select/dist/js/bootstrap-select.js"></script>
</head>

<body>
<nav class="navbar navbar-color navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0;background:#3294DD" >
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-tu"></span>
            <span class="icon-tu"></span>
            <span class="icon-tu"></span>
        </button>
        <a class="logo fl" href="index.html"><img src="http://qpht.hbyouyou.com/txyx/Public/admin/images/logo1.png"></a>

    </div>
    <div class="header_right fr">
        <ul>
            <li>
                <a href="<?php echo U('Index/index');?>" class="selected">

                    <span>首页</span>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Login/dologin');?>">

                    <span>注销</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- /.navbar-top-links -->
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">

            <ul class="nav in" id="side-sy">
                <li>
                    <a href="<?php echo U('Index/index');?>" class="active"><i class="fa fa-dashboard fa-fw"></i> 主页</a>
                </li>
                <li>
                    <a href="<?php echo U('Login/index');?>" class="active"><i class="fa fa-spinner fa-fw"></i> 注销</a>
                </li>
            </ul>
          






  <ul class="nav in" id="side-menu">
<li>
                    <a href="<?php echo U('Index/index');?>"><i class="fa fa-bar-chart fa-fw"></i>首页<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                    </ul>
                </li>

<?php if(is_array($Mune1)): $i = 0; $__LIST__ = $Mune1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                    <a href="#"><i class="fa fa-bar-chart fa-fw"></i> <?php echo ($vo["title"]); ?><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <?php if(is_array($Mune2)): $i = 0; $__LIST__ = $Mune2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ko): $mod = ($i % 2 );++$i; if(($vo['status']) == $ko['type']): if(($ko['id']) == $id): ?><li style="background-color: #00A1EC">
                            	<a href="<?php echo ($ko["condition"]); ?>"><?php echo ($ko["title"]); ?></a>
                        		</li>       
                             <?php else: ?>
				<li>
                            	<a href="<?php echo ($ko["condition"]); ?>"><?php echo ($ko["title"]); ?></a>
                        		</li><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>

               


                    </ul>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>








       


            </ul>
        </div>

    </div>
</nav>

<script>
  var index = '<?php echo ($num); ?>';
console.log(index);
  $("#side-menu>li").find('ul').eq(index).slideDown();
</script>
<div id="page-wrapper">


    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">元宝消耗</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            区域时间内元宝消耗 <div style="clear: both;"></div>
        </div>

        <!-- /.panel-heading -->
        <div class="panel-body">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                <hr>
                <div class="row">
                    <div class="col-sm-6" style="width: 70%;">
                        <div class="dataTables_length" id="dataTables-example_length">
                            <!--<label>转生等级:
                                <div class="form-group input-group">
                                    <input class="form-control" id="changelevel" type="text" value="<?php echo ($role_ChangeLife); ?>" placeholder="请输入玩家转生等级">
                                </div>
                            </label>
                            <label>VIP等级:
                                <div class="form-group input-group">
                                    <input class="form-control" id="viplevel" type="text" value="<?php echo ($vip); ?>" placeholder="请输入VIP等级">
                                </div>
                            </label>
                            <label>项目:
                                <div class="form-group input-group">
                                    <input class="form-control" id="resource" type="text" value="<?php echo ($reason); ?>" placeholder="请输入来源">
                                </div>
                            </label>-->

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <form action="<?php echo U('add');?>" method="post">
                            <select style="margin-left: 0px; width: 20%;"  name="type"
                                    class="btn btn-default" autocomplete="off">
                                <option value="0">.发布类型.</option>
                                <option value="1" >公告</option>
                                <option value="2" >新闻</option>
                            </select>
                            <input class="form-control" name="title" type="text"   style="width: 70%;" placeholder="请输入文章标题 ">
                        <textarea  type="text"  name="content" id="EditorId" placeholder="请输入内容"></textarea>
                            <input type="submit" value="提交" align="center" style="text-align:center;"/>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="b-page"><?php echo ($page); ?></div>
                </div>
            </div>
            <!-- /.table-responsive -->

        </div>
        <!-- /.panel-body -->
    </div>




</div>
<script type="text/javascript" charset="utf-8" src="/Public/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/ueditor/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8">//初始化编辑器
window.UEDITOR_HOME_URL = "/Public/ueditor/";//配置路径设定为UEditor所放的位置
window.onload=function(){
    window.UEDITOR_CONFIG.initialFrameHeight=600;//编辑器的高度
    window.UEDITOR_CONFIG.initialFrameWidth=1200;//编辑器的宽度
    var editor = new UE.ui.Editor({
        imageUrl : '',
        fileUrl : '',
        imagePath : '',
        filePath : '',
        imageManagerUrl:'', //图片在线管理的处理地址
        imageManagerPath:'/'
    });
    editor.render("EditorId");//此处的EditorId与<textarea name="content" id="EditorId">的id值对应 </textarea>
}
</script>


</body>
</html>