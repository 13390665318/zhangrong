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
            <h1 class="page-header">流失用户等级分布</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            流失用户等级分布趋势（注：页面所选日期为用户注册日期
注：流失天数为注册日起第几天没登录（定义为N）
所选日期的注册用户，在第N天没登录的用户的等级分布） <div style="clear: both;"></div>
        </div>

        <select style="margin-left: 0px; width: 10%;" onchange="choice(1)" id="clothes" class="btn btn-default">
            <option>...选择游戏服务器...</option>
            <?php if(is_array($clostu)): $i = 0; $__LIST__ = $clostu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["db_id"]) == $db_id): ?><option value="<?php echo ($vo["db_id"]); ?>" selected="selected"><?php echo ($vo["clothes"]); ?></option>
                    <?php else: ?>
                    <option value="<?php echo ($vo["db_id"]); ?>"><?php echo ($vo["clothes"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </select><input class="btn btn-default" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})" id="stime" value="<?php echo ($stime); ?>">
        <select style="margin-left: 0px; width: 10%;" class="btn btn-default" id="cesa" >
            <?php if($cesa == 24): ?><option value="24" selected="selected">24小时内未登录</option>
                <option value="48" >48小时内未登录</option>
                <option value="72" >72小时内未登录</option>
                <?php elseif($cesa == 48): ?>
                    <option value="24" >24小时内未登录</option>
                    <option value="48" selected="selected">48小时内未登录</option>
                    <option value="72" >72小时内未登录</option>
                <?php else: ?>
                <option value="24" >24小时内未登录</option>
                <option value="48" >48小时内未登录</option>
                <option value="72" selected="selected">72小时内未登录</option><?php endif; ?>

        </select>
        <button class="btn btn-default" id="search-operator-btn" type="button"  onclick="idselect()"><i class="fa fa-search"></i>搜索</button>
        <button class="btn btn-default" id="search-operator-btn" type="button"  onclick="exl()">导出</button>




        <div class="panel-body">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div id="chart1"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">

                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>
                            <tr>
                                <td>等级</td>
                                <td>人数(占比)</td>
                            </tr>
                            </thead>
                            <tbody id="ad">
                            <?php if(is_array($arr)): $i = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                    <td width="10%"><?php echo ($vo["level"]); ?></td>
                                    <td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td>

                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- /.table-responsive -->

        </div>
        <!-- /.panel-body -->
    </div>




</div>
<script>
    var www_url="<?php echo ($www_url); ?>";

    function idselect() {
        var clothes=$("#clothes").val();
        var stime=$("#stime").val();
        var cesa=$("#cesa").val();
        if(clothes=="...选择游戏服务器..."){
            alert("请选择游戏服务器");
            return
        }else{
            location.href=www_url+"index.php?m=Home&c=LossLevel&a=index&db_id="+clothes+"&stime="+stime+"&cesa="+cesa;
        }
    }
    function exl(){
        var clothes=$("#clothes").val();
        var stime=$("#stime").val();
        var cesa=$("#cesa").val();
        if(clothes=="...选择游戏服务器..."){
            alert("请选择游戏服务器");
            return
        }else{
            location.href=www_url+"index.php?m=Home&c=LossLevel&a=exl&db_id="+clothes+"&stime="+stime+"&cesa="+cesa;
        }
    }

    var obj='<?php echo ($jsoBj); ?>';
    var obj = eval(obj);
    var stu=new Array();
    var rus= new Array();
    for (var i =0; i<obj.length; i++) {
        stu[i]=obj[i].num;

    }
    //  var a=<?php echo ($Stime); ?>;
    var maxN = eval("Math.max(" + stu.toString() + ")")+100;
    stu=stu.reverse();
    var data = [stu];
    var data_max = maxN; //Y轴最大刻度
    var line_title = ["登录用户","B"]; //曲线名称
    var y_label = "人数"; //Y轴标题
    var x_label = "等级"; //X轴标题
    var x = [<?php echo ($stu); ?>]; //定义X轴刻度值
    var title = "这是标题"; //统计图标标题
    j.jqplot.diagram.base("chart1", data, line_title, "等级分布", x, x_label, y_label, data_max, 1);
    // 两分钟刷新
    setTimeout(function(){  //使用  setTimeout（）方法设定定时2000毫秒
        window.location.reload();//页面刷新
    },240000);

 var type=parseInt("<?php echo ($type); ?>")-1;
    $("input[name='optionsRadios']").get(type).checked=true;
    var parr="<?php echo ($Parr); ?>";
    var ss = parr.split(",");
    var obj=document.getElementsByName('source');
    for(var i=0;i<obj.length;i++){
        for(var j=0;j<ss.length;j++) {
            if(obj[i].value==ss[i]){
                $(":checkbox[value='"+ss[i]+"']").prop("checked",true);
            }
        }
    }


</script>
<script>
    $(function() {
        $(window).bind("load resize", function() {
            var topOffset = 50;
            var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
            if (width < 768) {
                $('div.navbar-collapse').addClass('collapse');
                topOffset = 100; // 2-row-menu
            } else {
                $('div.navbar-collapse').removeClass('collapse');
            }
            var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
            height = height - topOffset;
            if (height < 1) height = 1;
            if (height > topOffset) {
                $("#page-wrapper").css("min-height", (height) + "px");
            }
        });

        $("#side-menu>li").each(function () {
            $(this).click(function(){
            $(this).find('ul').slideDown();
              //  $(this).siblings().find('ul').slideUp();
            })
        })
    });
</script>
</body>
</html>