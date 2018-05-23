<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
<style>
    .bar {
        height: 18px;
        background: green;
    }
</style>
<div id="page-wrapper">


    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">增加记录</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            增加
            <div style="clear: both;"></div>
        </div>
        <!-- /.panel-heading -->

            <!-- /.table-responsive -->
        <div class="container">
            <form class="xq" action="<?php echo U('Oiladd/index');?>" method="post" enctype="multipart/form-data" onsubmit="return toVaild()" >
                <div class="lei">
                    <p>
                        <label><font>*</font>加油时间：</label>

                        <input class="btn btn-default"
                               onclick="laydate({istime: true, format: 'YYYY-MM-DD'})"
                               name="start_time" value="<?php echo ($Stime); ?>" style="width: 25%;">
                    </p>
                </div>
                <div class="lei">
                    <p>
                        <label><font>*</font>车牌号：</label>
                        <input class ="sr" type="text"  name="chepai" value=""   style="width: 25%;"id="chepai"/>

                    </p>

                </div>

                <div class="lei">
                    <p>
                        <label><font>*</font>车主：</label>
                        <input class ="sr" type="text"  name="chezhu" value=""  style="width: 25%;" id="chezhu"/>

                    </p>

                </div>
                <div class="lei">


                </div>
                <div class="lei">
                    <p>
                        <label><font>*</font>单次加油量(L)：</label>
                        <input class ="sr" type="text"  name="danci" value=""   style="width: 25%;" id="danci"/>
                    </p>

                </div>
                <div class="lei">
                    <p>
                        <label><font>*</font>单价/L：</label>
                        <input class ="sr" type="text"  name="danjia" value=""  style="width: 25%;" id="danjia"/>
                    </p>

                </div>
                <div class="lei">
                    <p>
                        <label><font>*</font>总加油金额/L：</label>
                        <input class ="sr" type="text"  name="jine" value=""   style="width: 25%;" id="jine"/>
                    </p>

                </div>
                <div class="lei">
                    <p>
                        <label><font>*</font>备注：</label>
                        <input class ="sr" type="text"  name="beizhu" value=""   style="width: 25%;" id="jine"/>
                    </p>

                </div>




                <button type="submit"class="btn btn-primary" style="margin:20px auto;display: block;">确定</button>
            </form>


        </div>
        </div>
        <!-- /.panel-body -->
    </div>


</div>

</body>
</html>
<script type="text/javascript">
    function toVaild(){
        /*var start_time=$("#start_time").val();*/
        var chepai=$("#chepai").val();
        var pinpai=$("#pinpai").val();
        var chezhu=$("#chezhu").val();
        var danci=$("#danci").val();
        var danjia=$("#danjia").val();
        var jine=$("#jine").val();
        var licheng=$("#licheng").val();
        if(chepai==""){
            alert("车牌不可为空");
            return false;
        }else if(chezhu==""){
            alert("车主不可为空");
            return false;
        }
        else if((danci=="")&&(jine=="")){
            alert("单次加油量、总加油金额必须填一个");
            return false;
        } else if(danjia==""){
            alert("单价不可为空");
            return false;
        } else{
            return true
        }


    }
</script>