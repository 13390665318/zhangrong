<?php if (!defined('THINK_PATH')) exit();?>


<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<link rel="stylesheet" href="../../../../Public/yy/header2/style.css">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link href="/Public/yy/mobile/bootstrap.min.css" rel="stylesheet">-->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="/Public/yy/mobile/html5shiv.js"></script>
    <script src="/Public/yy/mobile/respond.min.js"></script>
    <script src="/Public/yy/mobile/jquery.min.js"></script>
    <script src="/Public/yy/mobile/bootstrap.min.js"></script>
    <link href="/Public/yy/mobile/style.css" rel="stylesheet">
    <script src="/Public/yy/laydate/laydate.js"></script>
    <link rel="stylesheet" href="/Public/yy/mobile/pagination.css">
    <script type="text/javascript" src="/Public/yy/js/jqplot.js"></script>

    <link rel="stylesheet" href="/Public/yy/select/bootstrap-3.3.4.css">
    <link rel="stylesheet" href="/Public/yy/select/dist/css/bootstrap-select.css">
    <script src="/Public/yy/select/dist/js/bootstrap-select.js"></script>
    <link href="/Public/yy/header2/style3.css" rel="stylesheet">




</head>

<body>
<nav class="navbar navbar-color navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-tu"></span>
            <span class="icon-tu"></span>
            <span class="icon-tu"></span>

        </button>
        <a class="logo fl" href="index.html"><img src="http://qpht.hbyouyou.com/txyx/Public/admin/images/logo1.png"></a>

    </div>

    </div>
 <!--   <p>切换到：<a href="?l=zh-cn">简体中文</a> | <a href="?l=en-us">English</a></p>-->
    <div class="header_right fr">
     <!--   <div  style="margin-right:20px;" >
            <font color="white"><b>欢迎您 <?php echo $_SESSION['name'];?></b></font>
        </div>-->
        <ul>
            <li>
                <a href="<?php echo U('Index/index');?>" class="selected">
                    <img src="http://qpht.hbyouyou.com/txyx/Public/admin/images/icon_sy.png">
                    <span>首页</span>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Login/dologin');?>">
                    <img src="http://qpht.hbyouyou.com/txyx/Public/admin/images/icon_zx.png">
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
                    <a href="<?php echo U('Index/index');?>" class="active"><i class="fa fa-spinner fa-fw"></i> 注销</a>
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
                            	<a href="<?php echo ($ko["condition"]); ?>"><?php echo ($ko["title"]); echo ($id); ?></a>
                        		</li>       
                             <?php else: ?>
				<li>
                            	<a href="<?php echo ($ko["condition"]); ?>"><?php echo ($ko["title"]); echo ($id); ?></a>
                        		</li><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>

              


                    </ul>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>




            </ul>
        </div>

    </div>
</nav>
<script>
    function langSwitch(type) {
        alert(type);
        if(type==1){
            document.cookie="think_language="+"zh-CN";
        }else if (type==2){
            document.cookie="think_language="+"ko-KR";
        }else {
            document.cookie="think_language="+"en-US";
        }
        //history.go(0);
        window.location.href = window.location.href;
    }
    var index = '<?php echo ($num); ?>';
   // $("#side-menu>li").find('ul').eq(index).slideDown();
</script>

<div id="page-wrapper">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">实时统计</h1>
            <input type="text" id="phone">
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="now_time clearfix now_time1">
        <ul class="clearfix">
            <li>
                <h2>今日新增用户<!--<?php echo (L("public_success")); ?>--></h2>
                <p><?php echo ($user); ?></p>
            </li>
            <li>
                <h2>今日活跃玩家</h2>
                <p><?php echo ($user2); ?></p>
            </li>
           <li onclick="show()">
                <h2>当前在线人数</h2>
               <p><?php echo ($uonline); ?><br><?php if(is_array($server)): $i = 0; $__LIST__ = $server;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["confirm"] == '1'): else: ?>服务器<?php echo ($vo["db_id"]); ?>异常  last:<?php echo ($vo["nums"]["0"]["LogTime"]); ?><br><?php endif; endforeach; endif; else: echo "" ;endif; ?></p>
            </li>
            <li>
                <h2>今日充值人数</h2>
                <p><?php echo ($paynum); ?></p>
            </li>
            <li>
                <h2>今日充值金额</h2>
                <p><?php echo ($paymoney); ?></p>
            </li>
        </ul>

        <div id="show" style="font-size: 18px;display:none">
            今日:<input type="checkbox" name="test" value="1" style="width: 30px;height: 20px;" checked="checked"/>
            &nbsp;昨日:<input type="checkbox" value="2"style="width: 30px;height: 20px;"name="test" >
            &nbsp;近3日:<input type="checkbox" value="3"style="width: 30px;height: 20px;" name="test"/>
            &nbsp;近5日:<input type="checkbox" value="5" style="width: 30px;height: 20px;"name="test"/>
            &nbsp;近7日:<input type="checkbox" value="7" style="width: 30px;height: 20px;"name="test"/>
            <input type="button" value="确定" onclick="online()"/>
        </div>

        <div class="canvas1 clearfix">
            <div class="canvas1_total">
                <div id="chart1"></div>
            </div>
            <div class="canvas1_total">
                <div id="chart2"></div>
            </div>


          <div class="canvas1_total">
                <div id="chart3"></div>
                <div id="chart33" style="display:none"></div>
            </div>
            <div class="canvas1_total">
                <div id="chart4"></div>
            </div>
            <div class="canvas1_total">
                <div id="chart5"></div>
            </div>
        </div>
    </div>
    <div class="container"><button class="btn btn-primary btn1" type="button" style="position:relative;margin:0 auto;display:block;width:100px;text-align: center">展开</button></div>
    <div class="row" id="btn2">
        <div class="col-lg-12">
            <h1 class="page-header">整体趋势</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="now_time clearfix now_time2">
        <ul class="clearfix">
            <li>
                <h2>今日ARPU</h2>
                <p><?php echo ($ARPU); ?></p>
            </li>
            <li>
                <h2>今日ARPPU</h2>
                <p><?php echo ($ARPPU); ?></p>
            </li>
            <li>
                <h2>累计充值金额</h2>
                <p><?php echo ($sum_money); ?></p>
            </li>
            <li>
                <h2>今日付费率</h2>
                <p><?php echo ($fufeilv); ?>%</p>
            </li>
            <li>
                <h2>昨日留存率</h2>
                <p><?php echo ($saves); ?>%</p>
            </li>
	<li>
                <h2>累加新增数</h2>
                <p><?php echo ($adds); ?></p>
            </li>


        </ul>
     <!--   <div class="canvas2">
            <div class="canvas2_total">
                <div class="panel-body">
                    <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                        <div class="row">
                            <div class="col-sm-12 table-responsive">

                                <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                                    <thead>
                                    <tr>
                                        <td>日期</td>
                                        <td>新增用户</td>
                                        <td>活跃用户</td>
                                        <td>流失用户</td>
                                        <td>充值</td>
                                        <td>消耗</td>
                                        <td>付费率</td>
                                        <td>元宝存量</td>

                                    </tr>
                                    </thead>
                                    <tbody id="ad">
                                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                            <td width="10%"><?php echo ($vo["time"]); ?></td>
                                            <td><?php echo ($vo["num"]); ?>(<?php echo ($vo["nums"]); ?>%)</td>

                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- /.table-responsive -->

                </div>




            </div>
            <!--     <div class="canvas2_total">
                  <div id="chart7"></div>
              </div>
              <div class="canvas2_total">
                  <div id="chart8"></div>
              </div>
              <div class="canvas2_total">
                  <div id="chart9"></div>
              </div>
              <div class="canvas2_total">
                  <div id="chart10"></div>
              </div>
          </div>-->
        </div>

-->
    </div>
    <script>

        function online(){
            var obj=document.getElementsByName('test');
            var s=new Array();

            var obj3='<?php echo ($result); ?>';
            var obj3 = eval(obj3);
            var stu3=new Array();
            var rus3= new Array();
            var rus3s= new Array();
            var rus5s= new Array();
            var rus7s= new Array();
            for (var i =0; i<obj3.length; i++) {
                stu3[i]=obj3[i].num;
                rus3[i]=obj3[i].nums;
                rus3s[i]=obj3[i].numss;
                rus5s[i]=obj3[i].numsss;
                rus7s[i]=obj3[i].numssss;
            }


            $("#chart3").hide();
            $("#chart33").show();
            $("#chart33").html(" ");
            var arr=new Array();
            var arr2=new Array();
            for(var i=0; i<obj.length; i++){
                if(obj[i].checked) {
                    s=obj[i].value; //如果选中，将value添加到变量s中
                    if(s==1){
                        arr[i]=stu3;
                        arr2[i]="今日整点人数";
                    }else if(s==2){
                        arr[i]=rus3;
                        arr2[i]="昨日整点人数";
                    }else if(s==3){
                        arr[i]= rus3s;
                        arr2[i]="前3天人数";
                    }else if(s==5){
                        arr[i]= rus5s;
                        arr2[i]="前五天人数";
                    }else if(s==7){
                        arr[i]= rus7s;
                        arr2[i]="前7天人数";
                    }
                }
            }
            for(var i=0,len=arr.length;i<len;i++){
                if(!arr[i]||arr[i]==''||arr[i] === undefined){
                    arr.splice(i,1);
                    len--;
                    i--;
                }
            }

            for(var i=0,len=arr.length;i<len;i++){
                if(!arr2[i]||arr2[i]==''||arr2[i] === undefined){
                    arr2.splice(i,1);
                    len--;
                    i--;
                }
            }
            if(arr.length==1){
                var data = [arr[0]];
                var line_title = [arr2[0]]; //曲线名称
            }else if(arr.length==2){
                var data = [arr[0],arr[1]];
                var line_title = [arr2[0],arr2[1]]; //曲线名称
            }else if(arr.length==3){
                var data = [arr[0],arr[1],arr[2]];
                var line_title = [arr2[0],arr2[1],arr2[2]]; //曲线名称
            }else if(arr.length==4){
                var data = [arr[0],arr[1],arr[2],arr[3]];
                var line_title = [arr2[0],arr2[1],arr2[2],arr2[3]]; //曲线名称
            }else if(arr.length==5){
                var data = [arr[0],arr[2],arr[3],arr[4],arr[5]];
                var line_title = [arr2[0],arr2[1],arr2[3],arr2[4],arr2[5]]; //曲线名称
            }



            console.log(data);


            var data_max = 3000 ; //Y轴最大刻度

            var y_label = "人数"; //Y轴标题
            var x_label = "时间整点"; //X轴标题
            var x3 = [00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23]; //定义X轴刻度值
            var title = "这是标题"; //统计图标标题

            j.jqplot.diagram.base("chart33", data, line_title, "整点在线人数", x3, x_label, y_label, data_max, 1);







        }

        //新增
        var obj='<?php echo ($jsoBj); ?>';
        var obj = eval(obj);
        var stu=new Array();
        var rus= new Array();
        for (var i =0; i<obj.length; i++) {
            stu[i]=obj[i].num;

        }
        //  var a=<?php echo ($Stime); ?>;
        var maxN =Math.ceil(eval("Math.max(" + stu.toString() + ")")*1.2);
        stu=stu.reverse();
        var data = [stu];
        var data_max = maxN; //Y轴最大刻度
        var line_title = ["新增用户","B"]; //曲线名称
        var y_label = "数量"; //Y轴标题
        var x_label = "时间"; //X轴标题
        var x = [<?php echo ($Stime); ?>]; //定义X轴刻度值
        //  var title = "这是标题"; //统计图标标题
        j.jqplot.diagram.base("chart1", data, line_title, "新增用户", x, x_label, y_label, data_max, 1);

        // 活跃
        var obj2='<?php echo ($jsoBj2); ?>';
        var obj2 = eval(obj2);
        var stu2=new Array();
        var rus2= new Array();
        for (var i =0; i<obj2.length; i++) {
            stu2[i]=obj2[i].num;

        }
        //  var a=<?php echo ($Stime); ?>;
        var maxN2 = Math.ceil(eval("Math.max(" + stu2.toString() + ")")*1.2);
        stu2=stu2.reverse();
        var data2 = [stu2];
        var data_max2 = maxN2; //Y轴最大刻度
        var line_title2 = ["活跃用户","B"]; //曲线名称
        var y_label2 = "数量"; //Y轴标题
        var x_label2 = "时间"; //X轴标题
        var x2 = [<?php echo ($Stime); ?>]; //定义X轴刻度值
        // var title = "这是标题"; //统计图标标题
        j.jqplot.diagram.base("chart2", data2, line_title2, "活跃用户", x2, x_label2, y_label2, data_max2, 1);

        // 在线人数
        var obj3='<?php echo ($result); ?>';
        var obj3 = eval(obj3);
        var stu3=new Array();
        var rus3= new Array();
        for (var i =0; i<obj3.length; i++) {
            stu3[i]=obj3[i].num;
            rus3[i]=obj3[i].nums;
           

        }
console.log(obj3 );
        var maxN2 = Math.ceil(eval("Math.max(" + stu3.toString() + ")")*1.2);

      //  stu3=stu3.reverse();
     //   rus3=rus3.reverse();

        var data = [stu3];
        var data_max = maxN2 ; //Y轴最大刻度
        var line_title = ["今日整点人数","昨日整点人数"]; //曲线名称
        var y_label = "人数"; //Y轴标题
        var x_label = "时间整点"; //X轴标题
        var x3 = [00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23]; //定义X轴刻度值
      
        j.jqplot.diagram.base("chart3", data, line_title, "整点在线人数", x3, x_label, y_label, data_max, 1);
// 充值人数

        var obj4='<?php echo ($jsoBj4); ?>';
        var obj4 = eval(obj4);
        var stu4=new Array();
        var rus4= new Array();
        for (var i =0; i<obj4.length; i++) {
            stu4[i]=obj4[i].num;

        }
        //  var a=<?php echo ($Stime); ?>;
        var maxN4 = Math.ceil(eval("Math.max(" + stu4.toString() + ")")*1.2);
        stu4=stu4.reverse();
        var data4 = [stu4];
        var data_max4 = maxN4; //Y轴最大刻度
        var line_title4 = ["充值人数","B"]; //曲线名称
        var y_label4 = "数量"; //Y轴标题
        var x_label4 = "时间"; //X轴标题
        var x2 = [<?php echo ($Stime); ?>]; //定义X轴刻度值
        // var title = "这是标题"; //统计图标标题
        j.jqplot.diagram.base("chart4", data4, line_title4, "充值人数", x2, x_label4, y_label4, data_max4, 1);

        // 充值金额
        var obj5='<?php echo ($jsoBj5); ?>';
        var obj5 = eval(obj5);
        var stu5=new Array();
        var rus5= new Array();
        for (var i =0; i<obj5.length; i++) {
            stu5[i]=obj5[i].num;

        }
        //  var a=<?php echo ($Stime); ?>;
        var maxN5 = Math.ceil(eval("Math.max(" + stu5.toString() + ")")*1.2);
        stu5=stu5.reverse();
        var data5 = [stu5];
        var data_max5 = maxN5; //Y轴最大刻度
        var line_title5 = ["充值金额","B"]; //曲线名称
        var y_label5 = "数量"; //Y轴标题
        var x_label5 = "时间"; //X轴标题
        var x2 = [<?php echo ($Stime); ?>]; //定义X轴刻度值
        // var title = "这是标题"; //统计图标标题
        j.jqplot.diagram.base("chart5", data5, line_title5, "充值金额", x2, x_label5, y_label5, data_max5, 1);
        // 两分钟刷新
        setInterval(function(){
            var hs=new Date().getHours();
            var ms=new Date().getMinutes();
            if(hs=='00' && ms=='00'){
                window.location.reload();
        }
        },50000)
    </script>



    <script>
        var num=1;
        var  num2=1;
        $(function(){
            $(".btn1").click(function(){

                num++;
                $(".now_time1 ul li").eq(0).addClass('active');
                $(".now_time1 ul li").eq(0).siblings().removeClass('active');
                $(".canvas1 .canvas1_total").eq(0).animate({height:'300px',opacity:1});
                $(".canvas1 .canvas1_total").eq(0).siblings().animate({height:'0',opacity:0});
                if(num%2==0){

                    $(".btn1").html("收起");
                    $(".canvas1").show	();

                }else{
                    $(".btn1").html("展开");
                    $(".canvas1").hide();

                }
            })
            $(".btn2").click(function(){
                $(".now_time2 ul li").eq(0).addClass('active');
                $(".now_time2 ul li").eq(0).siblings().removeClass('active');
                $(".canvas2 .canvas2_total").eq(0).animate({height:'300px',opacity:1});
                $(".canvas2 .canvas2_total").eq(0).siblings().animate({height:'0',opacity:0});
                num2++;
                if(num%2==0){

                    $(".btn2").html("收起")
                    $(".canvas2").show	();

                }else{
                    $(".btn2").html("展开")
                    $(".canvas2").hide();

                }

            })

            $(".now_time1 ul li").click(function(){

                var index = $(this).index();
                if(index==2){
                    $("#show").show();
                }else{
                    $("#show").hide();
                }
                $(this).addClass('active');
                $(this).siblings().removeClass('active');
                $(".canvas1 .canvas1_total").eq(index).animate({height:'300px',opacity:1});
                $(".canvas1 .canvas1_total").eq(index).siblings().animate({height:'0',opacity:0});
            })
            $(".now_time2 ul li").click(function(){
                var index = $(this).index();
                $(this).addClass('active');
                $(this).siblings().removeClass('active');
                $(".canvas2 .canvas2_total").eq(index).animate({height:'300px',opacity:1});
                $(".canvas2 .canvas2_total").eq(index).siblings().animate({height:'0',opacity:0});
            })

        })
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
                    $(this).siblings().find('ul').slideUp();
                })
            })
        });

        function show(){
            $("#show").show();
        }
    </script>
    </body>
    </html>