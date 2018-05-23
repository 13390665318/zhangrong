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
            <h1 class="page-header">玩家货币日志</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            玩家货币日志 <div style="clear: both;"></div>
        </div>
        <div class="gzsysq">
            <div class="bt_con">
                <ul class="nav nav-tabs">
                    <li onclick="money_type(91000001)" data-id="91000001"><a>金币日志</a></li>
                    <li onclick="money_type(91000002)" data-id="91000002"><a>钻石日志</a></li>
                    <li onclick="money_type(91000018)" data-id="91000018"><a>荣誉点日志</a></li>
                    <li onclick="money_type(91000020)" data-id="91000020"><a>行动力日志</a></li>
                    <li onclick="money_type(91000007)" data-id="91000007"><a>比武币日志</a></li>
                    <li onclick="money_type(91000021)" data-id="91000021"><a>军团贡献点日志</a></li>
                    <li onclick="money_type(50000001)" data-id="50000001"><a>步兵训练点日志</a></li>
                    <li onclick="money_type(50000003)" data-id="50000003"><a>骑兵训练点日志</a></li>
                    <li onclick="money_type(50000002)" data-id="50000002"><a>弓兵训练点日志</a></li>
                </ul>
            </div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            <select style="margin-left: 0px; width: 15%;" onchange="choice(1)" id="clothes" class="btn btn-default">
                                <option>...选择游戏服务器...</option>
                                <?php if(is_array($clostu)): $i = 0; $__LIST__ = $clostu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["db_id"]) == $db_id): ?><option value="<?php echo ($vo["db_id"]); ?>" selected="selected"><?php echo ($vo["clothes"]); ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo ($vo["db_id"]); ?>"><?php echo ($vo["clothes"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>从<input class="btn btn-default" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" id="start_time" value="<?php echo ($Stime); ?>">
                            到<input class="btn btn-default" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" id="end_time" value="<?php echo ($Etime); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6" style="width: 100%;">
                        <div class="dataTables_length" id="dataTables-example_length">
                            <label>玩家ID:
                                <div class="form-group input-group">
                                    <input class="form-control" id="game_user_id" type="text" placeholder="玩家ID">
                                </div>
                            </label>
                            <label>角色名称:
                                <div class="form-group input-group">
                                    <input class="form-control" id="game_user_name" type="text" placeholder="玩家ID">
                                </div>
                            </label>
                            <label>产销方式:
                                <!--<input class="form-control input-sm" id="search-user-input"  placeholder="" aria-controls="dataTables-example" type="search">-->
                                <div class="form-group input-group">
                                    <select name="value" id="value" class="btn btn-default"><option>所有</option><option value="1">产出</option><option value="-1">消耗</option></select>
                                </div>
                            </label>
                            <label>日志点:
                                <!--<input class="form-control input-sm" id="search-user-input"  placeholder="" aria-controls="dataTables-example" type="search">-->
                                <div class="form-group input-group">
                                    <select id="dec" class="btn btn-default">
                                        <option>所有</option>
                                        <?php if(is_array($Log)): $i = 0; $__LIST__ = $Log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["dec"]); ?>"><?php echo ($vo["dec"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>

                                </div>
                            </label>
                            <label>日志点数据大于:
                                <!--<input class="form-control input-sm" id="search-user-input"  placeholder="" aria-controls="dataTables-example" type="search">-->
                                <div class="form-group input-group">
                                    <input type="text" name="num" id="num" class="form-control"/>
                                </div>

                            </label>
                            <button class="btn btn-default" id="search-operator-btn" type="button"  onclick="Cuselect()"><i class="fa fa-search"></i>搜索</button>
                            <button class="btn btn-default" id="search-operator-btn" type="button"  onclick="exl()">导出</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                    <span><b id="sumname1">产出金币总数:</b><b style="color:red" id="sum1"><?php echo ($maxsum); ?></b>
                    <b id="sumname2">消耗金币总数:</b><b style="color:red" id="sum2"><?php echo ($munsum); ?></b>
                    </span>
                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>
                            <tr>
                                <td width="10%">玩家ID</td>
                                <td width="10%">玩家名称</td>
                                <td width="10%">玩家等级</td>
                                <td width="30%">名称</td>
                                <td width="15%">数值</td>
                                <td width="10%">最后数值</td>
                                <td width="10%">时间</td>
                            </tr>
                            </thead>
                            <tbody id="ad">
                            <?php if(is_array($arr)): $i = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ko): $mod = ($i % 2 );++$i;?><tr>
                                    <td><?php echo ($ko["uid"]); ?></td>
                                    <td><?php echo ($ko["uname"]); ?></td>
                                    <td><?php echo ($ko["level"]); ?></td>
                                    <td><?php echo ($ko["dec"]); ?></td>
                                    <td><?php echo ($ko["value"]); ?></td>
                                    <td><?php echo ($ko["cur"]); ?></td>
                                    <td><?php echo ($ko["time"]); ?></td>
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
    function money_type(a){
        location.href=url+"index.php?m=Home&c=Currency&a=index&money_type="+a;
    }
    function Cuselect(){
        var money_type="<?php echo ($money_type); ?>"
        var clothes=$("#clothes").val();
        var start_time=$("#start_time").val();
        var end_time=$("#end_time").val();
        var game_user_id=$("#game_user_id").val();
        console.log(game_user_id);
        var game_user_name=$("#game_user_name").val();
        var value=$("#value").val();
        var dec=$("#dec").val();
        var num=$("#num").val();
       location.href=url+"index.php?m=Home&c=Currency&a=index&money_type="+money_type+"&db_id="+clothes+"&start_time="+start_time+ "&end_time="+end_time+"&game_user_id="+game_user_id+"&game_user_name="+game_user_name+"&value="+value+"&dec="+dec+"&num="+num;

    }
    function exl(){
        var money_type="<?php echo ($money_type); ?>"
        var clothes=$("#clothes").val();
        var start_time=$("#start_time").val();
        var end_time=$("#end_time").val();
        var game_user_id=$("#game_user_id").val();
        console.log(game_user_id);
        var game_user_name=$("#game_user_name").val();
        var value=$("#value").val();
        var dec=$("#dec").val();
        var num=$("#num").val();
        location.href=url+"index.php?m=Home&c=Currency&a=exl&money_type="+money_type+"&db_id="+clothes+"&start_time="+start_time+ "&end_time="+end_time+"&game_user_id="+game_user_id+"&game_user_name="+game_user_name+"&value="+value+"&dec="+dec+"&num="+num;

    }
</script>
<script>

    var num= parseInt("<?php echo ($money_type); ?>");

    $(function(){
        var arr=new Array();
        if(num==91000001){
            $('.bt_con ul li').eq(0).addClass("active")
            arr[0]="产出金币总数:";
            arr[1]="消耗金币总数:";
        }else if(num==91000002){
            $('.bt_con ul li').eq(1).addClass("active")
            arr[0]="产出钻石总数:";
            arr[1]="消耗钻石总数:";
        }else if(num==91000018){
            $('.bt_con ul li').eq(2).addClass("active")
            arr[0]="产出荣誉点总数:";
            arr[1]="消耗荣誉点总数:";
        }else if(num==91000020){
            $('.bt_con ul li').eq(3).addClass("active")
            arr[0]="产出行动力总数:";
            arr[1]="消耗行动力总数:";
        }else if(num==91000007){
            $('.bt_con ul li').eq(4).addClass("active")
            arr[0]="产出比武币总数:";
            arr[1]="消耗比武币总数:";
        }else if(num==91000021){
            $('.bt_con ul li').eq(5).addClass("active")
            arr[0]="产出军团贡献点总数:";
            arr[1]="消耗军团贡献点总数:";
        }else if(num==50000001){
            $('.bt_con ul li').eq(6).addClass("active")
            arr[0]="产出步兵训练点总数";
            arr[1]="消耗步兵训练点总数";
        }else if(num==50000003){
            $('.bt_con ul li').eq(6).addClass("active")
            arr[0]="产出骑兵训练点总数:";
            arr[1]="消耗骑兵训练点总数:";
        }else if(num==50000002){
            $('.bt_con ul li').eq(7).addClass("active")
            arr[0]="产出工兵训练点总数:";
            arr[1]="消耗弓兵训练点总数:";
        }

        $("#sumname1").html(arr[0])
        $("#sumname2").html(arr[1])

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