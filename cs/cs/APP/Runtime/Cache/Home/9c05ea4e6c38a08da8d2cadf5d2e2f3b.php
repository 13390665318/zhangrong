<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>运营数据</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link href="/Public/yy/mobile/bootstrap.min.css" rel="stylesheet">-->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <!-- <link rel="stylesheet" href="/Public/css/ssi-uploader.css">-->
    <!--<script src="/Public/js/ssi-uploader.js"></script>-->
    <script src="/Public/js/jquery-2.1.1.min.js"></script>
   <!-- <script src="/Public/yy/mobile/html5shiv.js"></script>-->
    <!--<script src="/Public/yy/mobile/respond.min.js"></script>-->
   <!-- <script src="/Public/yy/mobile/jquery.min.js"></script>-->
    <!--<script src="/Public/yy/mobile/bootstrap.min.js"></script>-->

    <link href="/Public/yy/mobile/style.css" rel="stylesheet">
   <!-- <script src="/Public/yy/laydate/laydate.js"></script>-->
   <!-- <link rel="stylesheet" href="/Public/yy/mobile/pagination.css">-->
    <script type="text/javascript" src="/Public/yy/js/jqplot.js"></script>
<!--<script type="text/javascript" src="/Public/jedate/jedate.js"></script>-->
    <link rel="stylesheet" href="/Public/yy/select/bootstrap-3.3.4.css">
    <link rel="stylesheet" href="/Public/yy/select/dist/css/bootstrap-select.css">
<!-- <script src="/Public/yy/select/dist/js/bootstrap-select.js"></script>-->
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
            <h1 class="page-header">任务</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            任务信息 <div style="clear: both;"></div>
        </div>

        <!-- /.panel-heading -->
        <div class="panel-body" id="lists">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            <select style="margin-left: 0px; width: 15%;" onchange="choice(1)" id="clothes" class="btn btn-default">
                                <option>...选择游戏服务器...</option>
                                <?php if(is_array($clostu)): $i = 0; $__LIST__ = $clostu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["db_id"]) == $db_id): ?><option value="<?php echo ($vo["db_id"]); ?>" selected="selected"><?php echo ($vo["game_name"]); ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo ($vo["db_id"]); ?>"><?php echo ($vo["clothes"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>&nbsp&nbsp
                            <input class="form-control" id="roleid" type="text" placeholder="角色ID">
                            <button class="btn btn-default" id="search-operator-btn" type="submit"  onclick="Cuselect()"><i class="fa fa-search"></i>搜索</button>
                            <div><button class="btn btn-default" id="rid" onclick="role()" value="<?php echo ($roleid); ?>">角色首页</button></div>
                            <span id="jianli"style="display:none" >&nbsp账号建立日期&nbsp&nbsp<input class="form-control" disabled="disabled"  id=jlrq type="text"value="<?php echo ($acounts); ?>" ></span>
                        </div>
                    </div>
                </div>
                <div class="row" style="width: 100%">

                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">

                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>

                            <tr>
                                <td width="20%">进行的主线任务ID</td>
                                <td width="50%">进行的主线任务</td>
                                <td width="50%">奖励</td>
                            </tr>

                            </thead>
                            <tbody id="ad">
                            <?php if(is_array($newtasks)): $i = 0; $__LIST__ = $newtasks;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td><?php echo ($vo["ID"]); ?></td>
                                <td><?php echo ($vo["Title"]); ?></td>
                                <td>绑定金币:<?php echo ($vo["BindMoneyaward"]); ?>&nbsp&nbsp&nbsp&nbsp经验:<?php echo ($vo["Experienceaward"]); ?></td>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">请输入需要封停时间</h4><!-- 弹框名字 -->
            </div>
            <div class="container">
                <div class="lei">
                    <p>
                        <label><font>*</font>时间（天数）：</label>
                        <input type="text" name="day" id="day"  value=""  style="width: 15%" width="10%"  onkeyup="onKeyPrice(this);"/>
                    </p>
                </div>


                <input type="hidden" id="uid" value=""/>
                <button type="button"  class="btn btn-primary"  style="margin-left: 25%;margin-bottom: 6%;margin-top: 6%" onclick="cash()">确定</button>
            </div>
        </div>
    </div>
</div>

<script>
    var url="<?php echo ($urls); ?>";
    function cash(){
        var day=$("#day").val();
        if(!day){
            alert("请输入天数");
            return false
        }else if(day<=0){
            alert("请输入大于0的天数")
        }else{
            var uid=$("#uid").val();
            var type=1;
            var clothes=$("#clothes").val();
            var aj = $.ajax( {
                url:'<?php echo U("Playtable/block");?>',
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
                            location.href=url+"index.php?m=Home&c=Playtable&a=index"
                        }else{
                            alert("操作失败");
                            alert(obj.msg)
                        }
                    }




                }
            })
        }
    }
    function onKeyPrice(t) {
        var stmp = "";
        if(t.value==stmp)
        {
            return;
        }
        var ms = t.value.replace(/[^\d\.]/g,"").replace(/(\.\d{2}).+$/,"$1").replace(/^0+([1-9])/,"$1").replace(/^0+$/,"0");
        var txt = ms.split(".");
        while(/\d{4}(,|$)/.test(txt[0]))
        {
            txt[0] = txt[0].replace(/(\d)(\d{3}(,|$))/,"$1,$2");
        }
        t.value = stmp = txt[0]+(txt.length>1?"."+txt[1]:"");
    }
    // 封号
    function block(uid,type){
        var uid=uid;
        var type=type;
        var clothes=$("#clothes").val();
        if(type==1){
            $("#uid").val(uid)
            $('#myModal').modal('show');
        }else{
            var aj = $.ajax( {
                url:'<?php echo U("Playtable/block");?>',
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
                            location.href=url+"index.php?m=Home&c=Playtable&a=index"
                        }else{
                            alert("操作失败");
                            alert(obj.msg)
                        }
                    }




                }
            })
        }

    }


    //gag  禁言
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
                        location.href=url+"index.php?m=Home&c=Playtable&a=index"
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
                    console.log(obj)
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



    /**
     * 时间选择
     * @param a 1：昨天 2 今天 3 近7天 4 近30天 5 月份  6 一年  7 时间选择
     */

    var jlrq = $("#jlrq").val();

    if(jlrq!==""){

        $("#jianli").show();
    }




    function Cuselect(){
        var value="<?php echo ($value); ?>"
        var clothes=$("#clothes").val();
        var start_time=$("#start_time").val();
        console.log(start_time)
        var end_time=$("#end_time").val();
        var roleid=$("#roleid").val();
        var game_user_name=$("#game_user_name").val();
        var goods_name=$("#type").val();
        location.href="index.php?m=Home&c=Task&a=index&value="+value+"&db_id="+clothes+"&start_time="+start_time+"&end_time="+end_time+"&roleid="+roleid+"&game_user_name="+game_user_name+"&goods_name="+goods_name;
    }
    function role(){
        var roleid=$("#rid").val();
        location.href="index.php?m=Home&c=Role&a=index&roleid="+roleid;
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
</script>



</body>
</html>