<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
   

    <title>管理员登陆</title>

   
    <script src="/Public/yy/mobile/jquery.min.js"></script>
    <link href="/Public/yy/mobile/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/Public/yy/signin.css" rel="stylesheet">
	<script src="/Public/yy/ie-emulation-modes-warning.js"></script>

    
  </head>

  <body>

    <div class="container">

      <form class="form-signin" role="form"  action="<?php echo U('Login/login');?>" method="post" id="myform">
        <h2 class="form-signin-heading">管理员登陆</h2>
          <select class="form-control" onchange="change()" id="type">

              <option value="<?php echo U('Login/login');?>">上线服-Alirefor</option>
              
        </select>
        <input type="text" class="form-control" placeholder="账号"   name="username">
        <input type="password" class="form-control" placeholder="密码" name="password">
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> 记住我
          </label>
                  </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">登 陆</button>
      </form>

    </div> <!-- /container -->

<script>
     // var newUrl="http://106.15.137.174/Test/index.php?m=Home&c=Login&a=index"
    function change(){
        var newUrl=$("#type").val();
        $("#myform").attr('action',newUrl);
    }
</script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/Public/ie10-viewport-bug-workaround.js"></script>
  

</body></html>