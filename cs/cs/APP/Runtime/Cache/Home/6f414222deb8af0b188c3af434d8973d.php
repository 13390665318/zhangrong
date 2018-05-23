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
<script>
    var url="<?php echo ($www_url); ?>";
    function idselect() {
        var agent_id=$("#search-operator-input").val();
        location.href=url+"index.php?m=Admin&c=Agent&a=index&account="+agent_id;

    }
</script>

<div id="page-wrapper">


    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">客服管理</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            管理员发送邮件 <div style="clear: both;"></div>
        </div>
        <a href="<?php echo U('Email/add');?>">  <button type="button" class="btn btn-primary btn_css">添加</button></a>
        <button type="button" class="btn btn-default btn_css" onclick="edit()">修改</button>
        <button type="button" class="btn btn-default btn_css" onclick="del()">隐藏</button>
	<a href="<?php echo U('Email/index',array("status"=>0));?>"> <button type="button" class="btn btn-default btn_css" onclick="dels()">全部显示</button></a>
<a href="<?php echo U('Email/index');?>"> <button type="button" class="btn btn-default btn_css" onclick="dels()">过滤显示</button></a>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
 <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            <select style="margin-left: 0px; width: 15%;"  id="clothes" class="btn btn-default">
                                <option>...选择游戏服务器...</option>
                                <?php if(is_array($clostu)): $i = 0; $__LIST__ = $clostu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["db_id"]) == $db_id): ?><option value="<?php echo ($vo["db_id"]); ?>" selected="selected"><?php echo ($vo["clothes"]); ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo ($vo["db_id"]); ?>"><?php echo ($vo["clothes"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>从<input class="btn btn-default" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" id="start_time" value="<?php echo ($Stime); ?>">
                            到<input class="btn btn-default" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" id="end_time" value="<?php echo ($Etime); ?>">
                            <button class="btn btn-default" id="search-operator-btn" type="button"  onclick="Cuselect()"><i class="fa fa-search"></i>搜索</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">

                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>

                            <tr>
                                <td><p style="font-size:9px">全选</p><input type="checkbox" name="" value=""  onclick="selectAll()" style="width: 20px; height: 55px;padding: 0 5px 0 0;"/></td>
                                <td>编号</td>
                                <td>收信人</td>
                                <td>邮件类型</td>
                                <td>区服</td>
                                <td>金币</td>
                                <td>钻石</td>
                                <td>物品</td>
                                <td>发送时间</td>
                                <td>操作</td>
                            </tr>
                            </thead>
                            <tbody id="ad">
                            <?php if(is_array($arr)): $i = 0; $__LIST__ = $arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                    <td style="width:5%" ><input type="checkbox" name="num"  value="<?php echo ($vo["email_id"]); ?>" style="width: 20px; height: 55px;padding: 0 5px 0 0;" /></td>
                                    <td><?php echo ($vo["email_id"]); ?></td>
                                    <td><?php echo ($vo["uname"]); ?></td>
                                    <td><?php echo ($vo["title"]); ?></td>
                                   <td><?php echo ($vo["db_name"]); ?></td>
                                    <td><?php echo ($vo["money"]); ?></td>
                                    <td><?php echo ($vo["acers"]); ?></td>
                                    <td><?php echo ($vo["goods_name"]); ?></td>
                                    <td><?php echo ($vo["send_time"]); ?></td>
                                    <td><?php if($vo["status"] == 0): ?><a><input type="button" value="未发送，点击发送" onclick="send('<?php echo ($vo["email_id"]); ?>')"></a>
				  
					
                                        <?php else: ?>已发送<?php endif; ?></td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
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

    function Cuselect(){

        var clothes=$("#clothes").val();
        var start_time=$("#start_time").val();
        var end_time=$("#end_time").val();
        location.href=url+"index.php?m=Home&c=Email&a=index&db_id="+clothes+"&start_time="+start_time+ "&end_time="+end_time;
    }
</script>

<script>
    var url="<?php echo ($urls); ?>";
    // 发送
    function send(id) {
        var email_id=id;
        var aj = $.ajax( {
            url:'<?php echo U("Email/send");?>',
            data:{
                email_id:email_id,
            },
            type:'get',
            cache:false,
            dataType:'json',
            success:function(data) {

                if(data==-1){
                    alert("存在中文'：'")

                }else if(data==-2){
                    alert("存在中文'；'")

                }else if(data==-3){
                    alert("数据格式错误，请排查")

                }else if(data==-4){
                    alert("数据格式错误，请排查")

                }else if(data==0){
                    alert("发送成功");
                    location.href=url+"index.php?m=Home&c=Email&a=index"
                }else{
                    alert("id为:"+data+"物品不存在")

                }





            }
        })

    }

    function selectAll(){
        var a = document.getElementsByTagName("input");
        if(a[1].checked){
            for(var i = 1;i<a.length;i++){
                if(a[i].type == "checkbox")
                {
                    a[i].checked = false;
                }
            }
        }else{
            for(var i = 1;i<a.length;i++){
                if(a[i].type == "checkbox")
                {
                    a[i].checked = true;
                }
            }
        }
    }

    /**
     * 编辑
     */
    function edit(){
        var obj=document.getElementsByName('num');
        var ids='';
        for(var i=0; i<obj.length; i++){
            if(obj[i].checked)
                ids=obj[i].value; //如果选中，将value添加到变量s中
        }
        if(ids==""){
            alert("你还没有选择任何内容！")
        }else{
            location.href=url+"index.php?m=Home&c=Email&a=edit&id="+ids;

        }
    }

    function del(){

        var obj=document.getElementsByName('num');
        var ids='';
        for(var i=0; i<obj.length; i++){
            if(obj[i].checked)
                ids+=obj[i].value+','; //如果选中，将value添加到变量s中
        }
        if(ids==''){
            alert("你还没有选择任何内容！")
        }else{

            var aj = $.ajax( {
                url:'<?php echo U("Email/del");?>',
                data:{
                    ids:ids,
                },
                type:'get',
                cache:false,
                dataType:'text',
                success:function(data) {
                    if(data){
                        if(data==1){
                            alert("隐藏成功!")
                            location.href=url+"index.php?m=Home&c=Email&a=index";
                        }else if(data==-2){
                            alert("无操作权限!")
                            location.href=url+"index.php?m=Home&c=Email&a=index";
                        }else{
                            alert("隐藏失败");
                        }
                    }
                }
            })
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