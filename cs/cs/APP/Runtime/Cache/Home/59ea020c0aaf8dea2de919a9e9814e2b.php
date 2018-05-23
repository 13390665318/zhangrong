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
            <h1 class="page-header">玩家货币产出日志</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            玩家货币产出日志 <div style="clear: both;"></div>
        </div>
        <div class="gzsysq">
            <div class="bt_con">
                <ul class="nav nav-tabs">
                    <li class="active" onclick="money_type(91000001)"><a>金币产出</a></li>
                    <li onclick="money_type(91000002)"><a>钻石产出</a></li>
                    <li onclick="money_type(91000018)"><a>荣誉点产出</a></li>
                    <li onclick="money_type(91000020)"><a>行动力产出</a></li>
                    <li onclick="money_type(91000007)"><a>比武币产出</a></li>
                    <li onclick="money_type(91000021)"><a>军团贡献点产出</a></li>
                    <li onclick="money_type(50000001)"><a>步兵训练点产出</a></li>
                    <li onclick="money_type(50000003)"><a>骑兵训练点产出</a></li>
                    <li onclick="money_type(50000002)"><a>弓兵训练点产出</a></li>
                </ul>
            </div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row" style="width: 100%">
                    <div class="col-sm-6" style="width: 100%">
                        <div class="dataTables_length" id="dataTables-example_length" style="width: 100%">
                            <select style="margin-left: 0px; width: 15%;" onchange="choice(1)" id="clothes" class="btn btn-default">
                                <option>...选择游戏服务器...</option>
                                <?php if(is_array($clostu)): $i = 0; $__LIST__ = $clostu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["db_id"]) == $db_id): ?><option value="<?php echo ($vo["db_id"]); ?>" selected="selected"><?php echo ($vo["clothes"]); ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo ($vo["db_id"]); ?>"><?php echo ($vo["clothes"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            从<input class="btn btn-default" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})" id="start_time" value="<?php echo ($Stime); ?>">
                            到<input class="btn btn-default" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})" id="end_time" value="<?php echo ($Etime); ?>">
                            <label>玩家ID:
                                <div class="form-group input-group">
                                    <input class="form-control" id="game_user_id" type="text" placeholder="玩家ID">
                                </div>
                            </label>
                            <label>
                                <div class="form-group input-group">
                                    <input  type="radio"  name="type" value="1" checked="checked" /> 总计
                                    <input  type="radio"  name="type" value="2" />时间
                                </div>
                            </label>
                            <button class="btn btn-default" id="search-operator-btn" type="button"  onclick="money_type(1)"><i class="fa fa-search"></i>搜索</button>


                        </div>
                    </div>
                </div>


                <div class="row">
                    <span><b id="sumname">产出金币总数:</b><b style="color:red" id="sum"><?php echo ($sum); ?></b></span>
                    </span>
                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>
                            <tr>
                                <td width="15%">产出项目</td>
                                <td width="10%">产出名称</td>
                                <td width="10%">产出数据</td>
                                <td width="10%">产出比例</td>
                            </tr>
                            </thead>
                            <tbody id="ad">
                            <?php if(is_array($arr)): $i = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ko): $mod = ($i % 2 );++$i;?><tr>
                                    <td><?php echo ($key+1); ?></td>
                                    <td><?php echo ($ko["name"]); ?></td>
                                    <td><?php echo ($ko["count"]); ?></td>
                                    <td><?php echo ($ko["num"]); ?>%</td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
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
<script type="text/javascript">
    var url="<?php echo ($urls); ?>";
    var mtype=91000001;
    function money_type(a){
        if(a==1){
            var num=mtype;
        }else{
            num=a;
            mtype=a;
        }
        if(num==91000001){
            var sumname="产出金币总数";
        }else if(num==91000002){
            var sumname="产出钻石总数";
        }else if(num==91000018){
            var sumname="产出荣誉点总数";
        }else if(num==91000020){
            var sumname="产出行动力总数";
        }else if(num==91000007){
            var sumname="产出比武币总数";
        }else if(num==91000021){
            var sumname="产出军团贡献点总数";
        }else if(num==50000001){
            var sumname="产出步兵训练点总数";
        }else if(num==50000003){
            var sumname="产出骑兵训练点总数";
        }else if(num==50000002){
            var sumname="产出工兵训练点总数";
        }
        $("#sumname").html();
        $("#sumname").html(sumname);
        var clothes=$("#clothes").val();
        var game_user_id=$("#game_user_id").val();
        var start_time=$("#start_time").val();
        var end_time=$("#demo").val();
        var type=$('input:radio:checked').val();
        var aj = $.ajax( {
            url:'<?php echo U("Produce/money_type");?>',
            data:{
                num:num,
                db_id:clothes,
                game_user_id:game_user_id,
                start_time:start_time,
                end_time:end_time,
                type:type,
            },
            type:'get',
            cache:false,
            dataType:'text',
            success:function(data) {
                if(data!=null) {

                    if(type==1){
                        var obj=eval(data);
                        var arr=new Array();
                        var sum=0;
                        var ah='<tr id="ah"><td width="15%">产出项目</td><td width="10%">产出名称</td><td width="10%">产出数据</td><td width="10%">产出比例</td></tr>';
                        $('#ad').html("");
                        for(var i=0;i<obj.length;i++){
                            arr[i]="<tr><td>"+(i+1)+"</td><td>"+obj[i].name+"</td><td>"+obj[i].count+"</td><td>"+obj[i].num+"%</td></tr>"
                            sum=sum+parseInt(obj[i].count);
                        }
                        for (var i = 0; i < obj.length; i++) {
                            $("#ad").append(arr[i]);
                        }
                        $("#sum").html("");
                        $("#sum").html(sum);
                        $("#ah").html("");
                        $("#ah").html(ah);
                    }else{
                        var obj=eval(data);
                        var arr=new Array();
                        var sum=0;
                        var ah='<tr id="ah"><td width="15%">日期</td><td width="15%">产出项目</td><td width="10%">产出名称</td><td width="10%">产出数据</td><td width="10%">产出比例</td></tr>';
                        $('#ad').html("");
                        for(var i=0;i<obj.length;i++){
                            arr[i]="<tr><td>"+obj[i].time+"</td><td>"+(i+1)+"</td><td>"+obj[i].name+"</td><td>"+obj[i].count+"</td><td>"+obj[i].num+"%</td></tr>"
                            sum=sum+parseInt(obj[i].count);
                        }
                        for (var i = 0; i < obj.length; i++) {
                            $("#ad").append(arr[i]);
                        }
                        $("#sum").html("");
                        $("#sum").html(sum);
                        $("#ah").html("");
                        $("#ah").html(ah);
                    }
                }
            }
        })
    }
</script>
<script>

    $('.bt_con ul li').click(function(){
        $(this).addClass('active');
        $(this).siblings().removeClass('active');
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
</script>



</body>
</html>