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

<div id="page-wrapper">


    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">活跃用户等级分布</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            活跃用户等级分布趋势 <div style="clear: both;"></div>
        </div>

        <select style="margin-left: 0px; width: 15%;" onchange="choice(1)" id="clothes"
                class="btn btn-default">
            <option>...选择游戏服务器...</option>
            <option value="0">全服</option>
            <?php if(is_array($clostu)): $i = 0; $__LIST__ = $clostu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["db_id"]) == $db_id): ?><option value="<?php echo ($vo["db_id"]); ?>" selected="selected"><?php echo ($vo["clothes"]); ?></option>
                    <?php else: ?>
                    <option value="<?php echo ($vo["db_id"]); ?>"><?php echo ($vo["clothes"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </select><input class="btn btn-default" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})" id="stime" value="<?php echo ($stime); ?>">
        <button class="btn btn-default" id="search-operator-btn" type="button"  onclick="idselect()"><i class="fa fa-search"></i>搜索</button>
        <hr>
        <div id="show" style="font-size: 18px">
            0重:<input type="radio" name="test" value="0" style="width: 30px;height: 20px;" checked="checked"/>
            &nbsp;1重:<input type="radio" value="1"style="width: 30px;height: 20px;"name="test" onclick="online()"/>
            &nbsp;2重:<input type="radio" value="2"style="width: 30px;height: 20px;"name="test" onclick="online()"/>
            &nbsp;3重:<input type="radio" value="3"style="width: 30px;height: 20px;" name="test"onclick="online()"/>
            &nbsp;4重:<input type="radio" value="4" style="width: 30px;height: 20px;"name="test"onclick="online()"/>
            &nbsp;5重:<input type="radio" value="5" style="width: 30px;height: 20px;"name="test"onclick="online()"/>
            &nbsp;6重:<input type="radio" value="6" style="width: 30px;height: 20px;"name="test"onclick="online()"/>
            &nbsp;7重:<input type="radio" value="7" style="width: 30px;height: 20px;"name="test"onclick="online()"/>
            &nbsp;8重:<input type="radio" value="8" style="width: 30px;height: 20px;"name="test"onclick="online()"/>
            &nbsp;9重:<input type="radio" value="9" style="width: 30px;height: 20px;"name="test"onclick="online()"/>

        </div>



        <div class="panel-body">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div id="chart1"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">

                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>
                            <tr >
                                <td></td>
                               <?php if(is_array($number)): $i = 0; $__LIST__ = $number;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td >【0】</td>
								<?php if(is_array($dataling)): $i = 0; $__LIST__ = $dataling;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td>【1】</td>
                                <?php if(is_array($datayi)): $i = 0; $__LIST__ = $datayi;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td>【2】</td>
                                <?php if(is_array($dataer)): $i = 0; $__LIST__ = $dataer;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td>【3】</td>
                                <?php if(is_array($datasan)): $i = 0; $__LIST__ = $datasan;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td>【4】</td>
                                <?php if(is_array($datasi)): $i = 0; $__LIST__ = $datasi;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td>【5】</td>
                                <?php if(is_array($datawu)): $i = 0; $__LIST__ = $datawu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td>【6】</td>
                                <?php if(is_array($dataliu)): $i = 0; $__LIST__ = $dataliu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td>【7】</td>
                                <?php if(is_array($dataqi)): $i = 0; $__LIST__ = $dataqi;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td>【8】</td>
                                <?php if(is_array($databa)): $i = 0; $__LIST__ = $databa;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            <tr>
                                <td>【9】</td>
                                <?php if(is_array($datajiu)): $i = 0; $__LIST__ = $datajiu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>
                            </thead>
                            <tbody id="ad">
                        
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

        if(clothes=="...选择游戏服务器..."){
            alert("请选择游戏服务器");
            return
        }else{
            location.href=www_url+"index.php?m=Home&c=ActivityLevel&a=index&db_id="+clothes+"&stime="+stime;
        }
    }
    function exl(){
        var clothes=$("#clothes").val();
        var stime=$("#stime").val();

        if(clothes=="...选择游戏服务器..."){
            alert("请选择游戏服务器");
            return
        }else{
            location.href=www_url+"index.php?m=Home&c=ActivityLevel&a=exl&db_id="+clothes+"&stime="+stime;
        }
    }
    function online(){
        $("#chart1").show();
        $("#chart1").html(" ");
        var obj=document.getElementsByName('test');
        var obj0 ='<?php echo ($jsoBj0); ?>';
        var obj1 ='<?php echo ($jsoBj1); ?>';
        var obj2 ='<?php echo ($jsoBj2); ?>';
        var obj3 ='<?php echo ($jsoBj3); ?>';
        var obj4 ='<?php echo ($jsoBj4); ?>';
        var obj5 ='<?php echo ($jsoBj5); ?>';
        var obj6 ='<?php echo ($jsoBj6); ?>';
        var obj7 ='<?php echo ($jsoBj7); ?>';
        var obj8 ='<?php echo ($jsoBj8); ?>';
        var obj9 ='<?php echo ($jsoBj9); ?>';

        for(var i=0; i<obj.length; i++){
            if(obj[i].checked) {
                var s=obj[i].value; //如果选中，将value添加到变量s中
                if(s==0){
                    var  arr=obj0;
                    var arr2="0重";
                }
                else if(s==1){
                    var  arr=obj1;
                    var arr2="1重";

                }else if(s==2){
                    var  arr=obj2;
                    var  arr2="2重";
                }else if(s==3){
                    var   arr= obj3;
                    var   arr2="3重";
                }else if(s==4){
                    var   arr= obj4;
                    var   arr2="4重";
                }else if(s==5){
                    var   arr= obj5;
                    var   arr2="5重";
                } else if(s==6){
                    var    arr= obj6;
                    var    arr2="6重";
                }else if(s==7){
                    var   arr= obj7;
                    var   arr2="7重";
                }else if(s==8){
                    var    arr= obj8;
                    var    arr2="8重";
                }else if(s==9){
                    var   arr= obj9;
                    var    arr2="9重";
                }

            }
        }

        var obj=arr;
        var obj = eval(obj);
        var stu=new Array();
        var rus= new Array();
        for (var i =0; i<obj.length; i++) {
            stu[i]=obj[i].num;

        }
        //  var a=<?php echo ($Stime); ?>;
        var maxN = Math.ceil(eval("Math.max(" + stu.toString() + ")")*1.2);
        stu=stu.reverse();
        var data = [stu];
        var data_max = maxN; //Y轴最大刻度
        var line_title = [arr2]; //曲线名称
        var y_label = "人数"; //Y轴标题
        var x_label = "等级"; //X轴标题
        var x = [<?php echo ($stu); ?>]; //定义X轴刻度值
        var title = "这是标题"; //统计图标标题
        j.jqplot.diagram.base("chart1", data, line_title, "等级分布", x, x_label, y_label, data_max, 1);

    }
    var obj='<?php echo ($jsoBj0); ?>';
    var obj = eval(obj);
    var stu=new Array();
    var rus= new Array();
    for (var i =0; i<obj.length; i++) {
        stu[i]=obj[i].num;

    }
    //  var a=<?php echo ($Stime); ?>;
    var maxN =  Math.ceil(eval("Math.max(" + stu.toString() + ")")*1.2)+5;
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