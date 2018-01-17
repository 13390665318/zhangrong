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
            <h1 class="page-header">玩家列表</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            玩家在线列表 <div style="clear: both;"></div>
        </div>

        <!-- /.panel-heading -->

        <div class="panel-body" id="lists">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <form action="<?php echo U('Home/Playeronline/index');?>" method="post" >
                    <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            <input class="btn btn-default" name="date" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" id="time" value="" placeholder="选择日期">
                            <select style="margin-left: 0px; width: 15%;"  name="serverid" id="clothes" class="btn btn-default">
                                        <option>...选择游戏服务器...</option>
                                        <option value="1" selected="selected">1</option>
                                        <option value="2">2</option>
                            </select>
                            <label>玩家ID:
                                <div class="form-group input-group">
                                    <input class="form-control" name="role_id" id="game_user_id" type="text" placeholder="玩家ID">
                                </div>
                            </label>
                            <!--<input class="btn btn-default" name="day" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" id="time" value="" placeholder="指定某一时刻查询">-->
                            <label>Operation:
                                <div class="form-group input-group">
                                    <input class="form-control" name="role_name" id="game_user_name" type="text" placeholder="Operation">
                                </div>
                            </label>
                            <button class="btn btn-default" id="search-operator-btn" type="submit"  onclick="choice()"><i class="fa fa-search"></i>搜索</button>





                        </div>
                    </div>
                </div>
                </form>

                <div class="row">
                    <div class="col-sm-12 table-responsive">
                       <!-- <b>目前共<font color="red"><?php echo ($online); ?></font>人在线</b>-->
               <!--         <table class="table table-striped  table-bordered table-hover dataTable "  id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;"  width="100%">
                            <thead>
                            <tr>
                                <td>时间</td>
                                <?php if(is_array($lin)): $i = 0; $__LIST__ = $lin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["LogTime"]); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>





                            </tr>
                            </thead>
                            <tbody id="ad">

                            <tr>
                                <td><?php echo ($time); ?></td>
                                <?php if(is_array($lin)): $i = 0; $__LIST__ = $lin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><td><?php echo ($vo["OnLinePlayerNum"]); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tr>

                            </tbody>
                        </table>-->
                        <table class="table table-striped  table-bordered table-hover dataTable "  id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;"  width="100%">
                        <thead>
                        <tr>
                            <td>时间</td>
                             <?php if(is_array($da)): $i = 0; $__LIST__ = $da;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><td><?php echo ($v); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>

                        </tr>
                            <tbody id="ad">
                        <?php if(is_array($linestatusday)): $i = 0; $__LIST__ = $linestatusday;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($vo["time"]); ?></td>
                            <td><?php echo ($vo["2018-01-01"]); ?></td>
                            <td><?php echo ($vo["2018-01-02"]); ?></td>
                            <td><?php echo ($vo["2018-01-03"]); ?></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                        </thead>

                       <!-- <?php if(is_array($loglinestatus)): $i = 0; $__LIST__ = $loglinestatus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td><?php echo ($vo["LogTime"]); ?></td>
                                <td><?php echo ($vo["OnLinePlayerNum"]); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
                        </tr>-->

                        </tbody>
                    </table>
                        <table>
                            <tr>
                                <td><?php echo ($show2); ?></td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="b-page"><?php echo ($page2); ?></div>
                        </div>
                        </table>

                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>
                            <tr>
                                <td>LogTime</td>
                                <td>Operation</td>
                                <td>ip</td>
                                <td>ClientApp_ver</td>
                                <td>serverID</td>
                                <td>deviceID</td>
                                <td>account_id</td>
                                <td>account_Name</td>
                                <td>role_id</td>
                                <td>role_name</td>
                                <td>role_create_time</td>
                                <td>role_ChangeLife</td>
                                <td>role_level</td>
                                <td>vip</td>
                                <td>login_time</td>
                                <td>last_logout_time</td>


                            </tr>
                            </thead>
                            <tbody id="ad">
                            <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                    <td><?php echo ($vo["LogTime"]); ?></td>
                                    <td><?php echo ($vo["Operation"]); ?></td>
                                    <td><?php echo ($vo["ip"]); ?></td>
                                    <td><?php echo ($vo["ClientApp_ver"]); ?></td>
                                    <td><?php echo ($vo["serverID"]); ?></td>
                                    <td><?php echo ($vo["deviceID"]); ?></td>
                                    <td><?php echo ($vo["account_id"]); ?></td>
                                    <td><?php echo ($vo["account_Name"]); ?></td>
                                    <td><?php echo ($vo["role_id"]); ?></td>
                                    <td><?php echo ($vo["role_name"]); ?></td>
                                    <td><?php echo ($vo["role_create_time"]); ?></td>
                                    <td><?php echo ($vo["role_ChangeLife"]); ?></td>
                                    <td><?php echo ($vo["role_level"]); ?></td>
                                    <td><?php echo ($vo["vip"]); ?></td>
                                    <td><?php echo ($vo["login_time"]); ?></td>
                                    <td><?php echo ($vo["last_logout_time"]); ?></td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                        <table>
                            <tr>
                                <td><?php echo ($show); ?></td>
                            </tr>
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
        <div class="panel-body" id="detial" style="display: none;width: 40%;float: left">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-12 table-responsive">

                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>

                            </thead>
                            <tbody id="csad">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- /.table-responsive -->

        </div>

        <div class="panel-body" id="kjSty" style="display: none;width: 40%;float: left">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-12 table-responsive">

                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead id="tiname">

                            </thead>
                            <tbody id="csadsd">
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- /.table-responsive -->

        </div>
    </div>




</div>

<!--<script>
    window.onload = function () {
        var date = new Date();
        document.getElementById("time").value = date.format("yyyy-MM-dd");
    }
    //gag  禁言
    var url="<?php echo ($urls); ?>";
    function gag(uid,type){
        var uid=uid;
        var clothes=$("#clothes").val();
        var type=type;
        var aj = $.ajax( {
            url:'<?php echo U("Playtable/gag");?>',
            data:{
                uid:uid,
                db_id:clothes,
                type:type
            },
            type:'get',
            cache:false,
            dataType:'json',
            success:function(data) {
                if(data!=null){
                    var obj=eval(data);
                    var code=obj.code;
                    if(code==0){
                        alert("操作成功");
                        location.href=url+"index.php?m=Home&c=Playeronline&a=index"
                    }else{
                        alert("操作失败");
                        alert(obj.msg)
                    }
                }




            }
        })

    }
    // 查看武将 道具
    var names;
    var userids;
    function show(uid,type) {
        var uid=uid;
        var type=type;
        var clothes=$("#clothes").val();
        if(type==16){
            // 武将
            var title=names+"("+userids+")武将列表"
            var tiname="<tr><td>武将编号</td><td>武将名称</td><td>武将星级</td><td>武将等级</td><td>武将经验</td></tr>"
        }else if(type==17){
            var title=names+"("+userids+")道具列表"
            var tiname="<tr><td>道具编号</td><td>道具名称</td><td>数量</td><td>道具类别</td><td>道具经验</td></tr>"
        }
        $("#kjSty").show();
        $("#title").html(" ");
        $("#title").html(title);
        $("#tiname").html(" ");
        $("#tiname").html(tiname);
        var aj = $.ajax( {
            url:'<?php echo U("Playtable/show");?>',
            data:{
                uid:uid,
                db_id:clothes,
                type:type
            },
            type:'get',
            cache:false,
            dataType:'json',
            success:function(data) {
                if(data!=null) {
                    var obj=eval(data);
                    var arr=new Array;
                    for(var i=0;i<obj.length;i++){
                        arr[i]="<tr><td width='5%'>"+obj[i].id+"</td><td width='15%'>"+obj[i].name+"</td><td width='5%'>"+obj[i].stars+"</td><td width='5%'>"+obj[i].levels+"</td><td width='5%'>"+obj[i].exp+"</td></tr>";

                    }
                    $("#csadsd").html("");
                    for (var i = 0; i < obj.length; i++) {
                        $("#csadsd").append(arr[i]);
                    }

                }
            }
        })




    }
    // 查看详细信息
    function propose(uid) {
        var uid =uid;
        var clothes=$("#clothes").val();
        var name=new Array('平台名','平台账户','帐号名称','玩家ID','等级','公会','创建时间','在线时间','最后登录时间','最后登录IP','vip等级','vip经验','元宝','铜钱','体力值','经验','武将数量','道具数量','当前章节');
        var aj = $.ajax( {
            url:'<?php echo U("Playtable/detial");?>',
            data:{
                uid:uid,
                db_id:clothes
            },
            type:'get',
            cache:false,
            dataType:'json',
            success:function(data) {
                if(data!=null) {
                    var obj=eval(data);

                    $("#lists").hide();
                    $("#detial").show();
                    var arr=new Array;
                    names=obj[2];
                    userids=obj[3];
                    for(var i=0;i<obj.length;i++){
                        if(i==16 || i==17){
                            arr[i]="<tr><td width='5%'>"+name[i]+"</td><td width='15%'>"+obj[i]+"</td><td width='5%'><a onclick='show("+obj[3]+","+i+")'>查看</a></td></tr>";
                        }else{
                            arr[i]="<tr><td width='5%' height='2%'>"+name[i]+"</td><td width='15%'>"+obj[i]+"</td><td width='5%'></td></tr>";
                        }
                    }

                    for (var i = 0; i < obj.length; i++) {
                        $("#csad").append(arr[i]);
                    }

                }
            }
        })

    }


    // 踢下线
    function tichu(uid) {
        var uid=uid;
        var clothes=$("#clothes").val();
        var type=-2;
        var aj = $.ajax( {
            url:'<?php echo U("Playtable/gag");?>',
            data:{
                uid:uid,
                db_id:clothes,
                type:type
            },
            type:'get',
            cache:false,
            dataType:'json',
            success:function(data) {
                if(data!=null){
                    var obj=eval(data);
                    var code=obj.code;
                    if(code==0){
                        alert("操作成功");
                        location.href=url+"index.php?m=Home&c=Playeronline&a=index"
                    }else{
                        alert("操作失败");
                        alert(obj.msg)
                    }
                }




            }
        })
    }

</script>


<script type="text/javascript">
    /**
     * 时间选择
     * @param a 1：昨天 2 今天 3 近7天 4 近30天 5 月份  6 一年  7 时间选择
     */
    function choice(a) {
        var num =a;

            var game_user_name = $("#game_user_name").val()
            var game_user_id = $("#game_user_id").val()

            var clothes = $("#clothes").val();
            location.href=url+"index.php?m=Home&c=Playeronline&a=index&db_id="+clothes+"&game_user_name="+game_user_name+"&game_user_id="+game_user_id;

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
                $(this).siblings().find('ul').slideUp();
            })
        })
    });
</script>-->



</body>
</html>