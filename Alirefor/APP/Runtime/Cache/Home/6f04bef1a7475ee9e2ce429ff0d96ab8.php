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
<style>
.bar {
height: 18px;
background: green;
}
</style>
<div id="page-wrapper">


    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">装备操作</h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            装备操作 <div style="clear: both;"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            <select style="margin-left: 0px; width: 15%;" onchange="choice(1)" id="clothes"
                                    class="btn btn-default">
                                <option>..选服务器..</option>
                                <option value="0">全服</option>
                                <?php if(is_array($clostu)): $i = 0; $__LIST__ = $clostu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["db_id"]) == $db_id): ?><option value="<?php echo ($vo["db_id"]); ?>" selected="selected"><?php echo ($vo["clothes"]); ?></option>
                                        <?php else: ?>
                                        <option value="<?php echo ($vo["db_id"]); ?>"><?php echo ($vo["clothes"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </select>&nbsp&nbsp&nbsp<label>选择日志时间</label>&nbsp&nbsp&nbsp<input class="btn btn-default"
                                                                                               onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"
                                                                                               id="start_time" value="<?php echo ($Stime); ?>">
                            &nbsp&nbsp至&nbsp&nbsp&nbsp<input class="btn btn-default"
                                                             onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"
                                                             id="end_time" value="<?php echo ($Etime); ?>">&nbsp&nbsp&nbsp
                            <button class="btn btn-default" id="search-operator-btn" type="button" onclick="Cuselect()">
                                <i class="fa fa-search"></i>搜索
                            </button>
                            <button class="btn btn-default" id="search-operator-btn" type="button"  onclick="exl()">导出</button><font color="red">（导出最大数量不得超过2万条）</font>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6" style="width: 70%;">
                        <div class="dataTables_length" id="dataTables-example_length">
                            <label>玩家ID:
                                <div class="form-group input-group">
                                    <input class="form-control" id="game_user_id" type="text" value="<?php echo ($role_id); ?>" placeholder="玩家ID">
                                </div>
                            </label>
                            <label>角色名称:
                                <div class="form-group input-group">
                                    <input class="form-control" id="game_user_name" type="text" value="<?php echo ($role_name); ?>" placeholder="角色名称">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">

                        <table class="table table-striped  table-bordered table-hover dataTable " id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;" width="100%">
                            <thead>
                            <tr>
                                <td width="10%">时间</td>
                                <td >角色ID</td>
                                <td >装备ID</td>
                                <td >装备类型</td>
                                <td >操作类型</td>
                                <td >操作前属性</td>
                                <td >操作后属性</td>
                                <td >物品消耗信息</td>
                                <td >物品剩余</td>
                            </tr>
                            </thead>
                            <tbody id="ad">
                            <?php if(is_array($equiplog)): $i = 0; $__LIST__ = $equiplog;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ko): $mod = ($i % 2 );++$i;?><tr>
                                    <td><?php echo ($ko["LogTime"]); ?></td>
                                    <td><?php echo ($ko["role_id"]); ?></td>
                                    <td><?php echo ($ko["equip_id"]); ?></td>
                                    <td><?php echo ($ko["equip_type"]); ?></td>
                                    <td><?php echo ($ko["equip_operation"]); ?></td>
                                    <!--操作前属性&操作后属性-->
                                    <?php if(($ko["equip_operation"]) == "装备强化"): ?><td>level:<?php echo ($ko["equipInfo"]["Forge_level_before"]); ?></td>
                                        <td>level:<?php echo ($ko["equipInfo"]["Forge_level_after"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备进阶"): ?><td><?php echo ($ko["equipInfo"]["EquipA"]["goodDBId"]); ?>
                                            &nbsp<?php echo ($ko["equipInfo"]["EquipB"]["goodDBId"]); ?>
                                            &nbsp<?php echo ($ko["equipInfo"]["EquipC"]["goodDBId"]); ?>
                                        </td>
                                        <td><?php echo ($ko["equipInfo"]["newEquip"]["goodDBId"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备附加"): ?><td><?php echo ($ko["equipInfo"]["AppendPropLev_before"]); ?>
                                        </td>
                                        <td><?php echo ($ko["equipInfo"]["AppendPropLev_after"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备炼化"): ?><td><?php if(is_array($ko["equipInfo"]["BeforeAttribute"])): $k = 0; $__LIST__ = $ko["equipInfo"]["BeforeAttribute"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>ID:<?php echo ($v["AttributeId"]); ?>value:<?php echo ($v["AttributeValue"]); ?><br><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </td>
                                        <td><?php if(is_array($ko["equipInfo"]["AfterAttribute"])): $k = 0; $__LIST__ = $ko["equipInfo"]["AfterAttribute"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>ID:<?php echo ($v["AttributeId"]); ?>value:<?php echo ($v["AttributeValue"]); ?><br><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备自动全部传承"): ?><td>ID:<?php echo ($ko["equipInfo"]["leftEquip"]["goodDBId"]); ?>&nbsp
                                            level_before:<?php echo ($ko["equipInfo"]["leftEquip"]["Forge_level_before"]); ?>
                                           PropLev_before:<?php echo ($ko["equipInfo"]["leftEquip"]["AppendPropLev_before"]); ?><br>
                                            ID:<?php echo ($ko["equipInfo"]["rightEquip"]["goodDBId"]); ?>&nbsp
                                            level_before:<?php echo ($ko["equipInfo"]["rightEquip"]["Forge_level_before"]); ?>
                                           PropLev_before:<?php echo ($ko["equipInfo"]["rightEquip"]["AppendPropLev_before"]); ?>
                                        </td>
                                        <td>ID:<?php echo ($ko["equipInfo"]["leftEquip"]["goodDBId"]); ?>&nbsp
                                            level_after:<?php echo ($ko["equipInfo"]["leftEquip"]["Forge_level_after"]); ?>
                                            PropLev_after:<?php echo ($ko["equipInfo"]["leftEquip"]["AppendPropLev_after"]); ?><br>
                                            ID:<?php echo ($ko["equipInfo"]["rightEquip"]["goodDBId"]); ?>&nbsp
                                            level_after:<?php echo ($ko["equipInfo"]["rightEquip"]["Forge_level_after"]); ?>
                                            PropLev_before:<?php echo ($ko["equipInfo"]["rightEquip"]["AppendPropLev_after"]); ?>
                                        </td><?php endif; ?>
                                   <!--物品消耗-->
                                    <?php if(($ko["equip_operation"]) == "装备强化"): ?><td>物品ID:<?php echo ($ko["consumeInfo"]["0"]["goodId"]); ?>金钱类型:<?php echo ($ko["consumeInfo"]["1"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["1"]["count"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备进阶"): ?><td><?php if(is_array($ko["consumeInfo"])): $i = 0; $__LIST__ = $ko["consumeInfo"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>ID:<?php echo ($v["goodDBId"]); ?> num:<?php echo ($v["count"]); ?><br><?php endforeach; endif; else: echo "" ;endif; ?>金钱类型:<?php echo ($ko["consumeInfo"]["3"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["3"]["count"]); ?> </td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备自动全部传承"): ?><td>
                                          金钱类型:<?php echo ($ko["consumeInfo"]["0"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["0"]["count"]); ?> </td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备炼化"): ?><td>
                                           ID:<?php echo ($ko["consumeInfo"]["0"]["goodDBId"]); ?> num:<?php echo ($ko["consumeInfo"]["0"]["count"]); ?> 金钱类型:<?php echo ($ko["consumeInfo"]["1"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["1"]["count"]); ?> </td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备附加"): ?><td>ID:<?php echo ($ko["consumeInfo"]["0"]["goodDBId"]); ?> num:<?php echo ($ko["consumeInfo"]["0"]["count"]); ?> 金钱类型:<?php echo ($ko["consumeInfo"]["1"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["1"]["count"]); ?></td><?php endif; ?>
                                    <!--物品剩余-->
                                    <?php if(($ko["equip_operation"]) == "装备强化"): ?><td>ID:<?php echo ($ko["goodDBId"]); ?>金钱类型:<?php echo ($ko["leftInfo"]["1"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["1"]["count"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备进阶"): ?><td><?php if(is_array($ko["leftInfo"])): $i = 0; $__LIST__ = $ko["leftInfo"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>ID<?php echo ($v["goodDBId"]); ?>count:<?php echo ($v["count"]); ?><br><?php endforeach; endif; else: echo "" ;endif; ?>
                                           金钱类型:<?php echo ($ko["leftInfo"]["4"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["4"]["count"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备自动全部传承"): ?><td>金钱类型:<?php echo ($ko["leftInfo"]["0"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["0"]["count"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备炼化"): ?><td>ID:<?php echo ($ko["leftInfo"]["0"]["goodDBId"]); ?> count:<?php echo ($ko["leftInfo"]["0"]["count"]); ?>金钱类型:<?php echo ($ko["leftInfo"]["1"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["1"]["count"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备附加"): ?><td>ID:<?php echo ($ko["consumeInfo"]["0"]["goodDBId"]); ?> num:<?php echo ($ko["consumeInfo"]["0"]["count"]); ?> 金钱类型:<?php echo ($ko["consumeInfo"]["1"]["MoneyTypes"]); ?> 数量:<?php echo ($ko["consumeInfo"]["1"]["count"]); ?></td><?php endif; ?>
                                    <!--<?php if(($ko["equip_operation"]) == "装备强化"): ?><td><?php echo ($ko["leftInfo"]["1"]["count"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备进阶"): ?><td><?php echo ($ko["leftInfo"]["4"]["count"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备自动全部传承"): ?><td><?php echo ($ko["leftInfo"]["0"]["count"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备炼化"): ?><td><?php echo ($ko["leftInfo"]["1"]["count"]); ?></td><?php endif; ?>
                                    <?php if(($ko["equip_operation"]) == "装备附加"): ?><td><?php echo ($ko["leftInfo"]["1"]["count"]); ?></td><?php endif; ?>-->
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


</body>
</html>

<script>
    function exl() {
        var clothes=$("#clothes").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var game_user_id = $("#game_user_id").val();
        var game_user_name = $("#game_user_name").val();
        var goods_name = $("#type").val();
            location.href = "index.php?m=Home&c=Equiplog&a=exl&start_time="+start_time+ "&end_time="+end_time+"&game_user_id="+game_user_id+"&game_user_name="+game_user_name+"&db_id="+clothes;
    }
</script>





</body>
</html>
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
<script>
    function Cuselect() {
        var clothes=$("#clothes").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var game_user_id = $("#game_user_id").val();
        var game_user_name = $("#game_user_name").val();
        var goods_name = $("#type").val();
        if(clothes=="...选择游戏服务器..."){
            alert("请选择游戏服务器");
            return
        }else{
            location.href = "index.php?m=Home&c=Equiplog&a=index&start_time="+start_time+ "&end_time="+end_time+"&game_user_id="+game_user_id+"&game_user_name="+game_user_name+"&db_id="+clothes;
        }
    }


</script>