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
            <h1 class="page-header">体力日志</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            体力日志 <div style="clear: both;"></div>
        </div>
        <div class="gzsysq">
            <div class="bt_con">
                <ul class="nav nav-tabs">
                    <li onclick="money_type(-1)" data-id="-1"><a>体力消耗</a></li>
                    <li onclick="money_type(1)" data-id="1"><a>体力产出</a></li>
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
                    <div class="col-sm-6" style="width: 59%;">
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
                            <label>日志点:
                                <!--<input class="form-control input-sm" id="search-user-input"  placeholder="" aria-controls="dataTables-example" type="search">-->
                                <div class="form-group input-group">
                                    <select id="dec" class="btn btn-default">
                                        <option>所有</option>
                                        <?php if(is_array($Log)): $i = 0; $__LIST__ = $Log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["dec"]); ?>"><?php echo ($vo["dec"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    <span class="input-group-btn">
                                                <button class="btn btn-default" id="search-operator-btn" type="button"  onclick="Cuselect()"><i class="fa fa-search"></i>搜索</button>
                                            </span>
                                </div>
                            </label>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">

                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>
                            <tr>
                                <td width="10%">玩家ID</td>
                                <td width="10%">玩家名称</td>
                                <td width="10%">玩家等级</td>
                                <td width="30%">名称</td>
                                <td width="15%">数值</td>
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
        location.href=url+"index.php?m=Home&c=Manual&a=index&value="+a;
    }
    function Cuselect(){
        var value="<?php echo ($value); ?>"
        var clothes=$("#clothes").val();
        var start_time=$("#start_time").val();
        var end_time=$("#end_time").val();

        var game_user_id=$("#game_user_id").val();
        var game_user_name=$("#game_user_name").val();
        var dec=$("#dec").val();
         location.href=url+"index.php?m=Home&c=Manual&a=index&value="+value+"&db_id="+clothes+"&start_time="+start_time+ "&end_time="+end_time+"&game_user_id="+game_user_id+"&game_user_name="+game_user_name+"&dec="+dec;

    }
    function exl(){
        var value="<?php echo ($value); ?>"
        var clothes=$("#clothes").val();
        var start_time=$("#start_time").val();
        var end_time=$("#end_time").val();
        var game_user_id=$("#game_user_id").val();
        var game_user_name=$("#game_user_name").val();
        var dec=$("#dec").val();
        location.href=url+"Home/Manual&a=exl&value="+value+"&db_id="+clothes+"&start_time="+start_time+ "&end_time="+end_time+"&game_user_id="+game_user_id+"&game_user_name="+game_user_name+"&dec="+dec;

    }
</script>
<script>

    var num= parseInt("<?php echo ($value); ?>");

    $(function(){
        var arr=new Array();
        if(num==-1){
            $('.bt_con ul li').eq(0).addClass("active")

        }else if(num==1){
            $('.bt_con ul li').eq(1).addClass("active")
        }

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