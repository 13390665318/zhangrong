<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 引入公共样式 -->
    <link rel="stylesheet" href="/Public/libs/common.css">
    <title>集团新闻</title>
</head>
<style >
 * {
        margin: 0;
        padding: 0;
    }

    .page-news{
        width: 100%;
        height: 100%;
        background: #CAE8FF;
    }
    .page-news-content{
        width: 1000px;
        margin: 0 auto;
    }
  .page-news-detail{
      width:100%;
      background:#fff;
      padding-top:20px;
  }
  .news-detail-box{
      width:100%;
      height:8px;
      background:lightblue;
  }

    .news-detail-content{
        width:100%;
        height:800px;
        margin-right:1px;
        margin-top:15px;
        border: 1px solid #ccc;
    }
    .news-detail-position{
        width:100%;
        height:30px;
        padding-top:10px;
    }
    .position-left{
        float:left;
        height:30px;
        padding-left: 30px;
        line-height: 30px;
        font-size: 14px;
        cursor: pointer;
    }
    .position-right{
        float: right;
        height:30px;
        padding-right: 30px;
        
    }
    .position-rightfont{
        line-height: 30px;
        font-size: 14px;
        height:30px;
        float: left;
        display: flex;
        justify-content: space-around;
        cursor: pointer;
    }
    .position-rightfont span{
        width:20px;
        height:20px;
        font-size:12px;
        line-height:20px;
        text-align: center;
        color:#fff;
        background:orange;
        margin-left:8px;
        margin-top:5px;
       
    }
    .pages-close{
        float: left;
        margin-left:15px;
        height:30px;
        float: left;
        cursor: pointer;
    }
    .pages-close span:nth-child(1){
        width:20px;
        height:20px;
        font-size:14px;
        line-height:20px;
        text-align: center;
        color:#fff;
        background:orange;
        margin-left:8px;
        margin-top:5px;
        float:left;
    }
    .pages-close span:nth-child(2){
        line-height: 30px;
        font-size: 14px;
        height:30px;
        float: left;
        margin-left:4px;
    }
    .news-detail-title{
        width:100%;
        height:40px;
        font-size:20px;
        text-align: center;
        color:orange;
        line-height:40px;
        font-weight: 700;
        margin-top:15px;
    }
    .news-detail-time{
        width:100%;
        height:20px;
        font-size:16px;
        text-align: center;
        color:#666;
        line-height:20px;
        margin-top:15px;
        padding-bottom:15px;
        border-bottom:1px dotted #ccc;
    }
    .news-detail-text{
        width:100%;
        height:600px;
        padding-bottom:30px;
    }
    .detail-text-inner{
        width:90%;
        height:600px;
        margin:0 auto;
        font-size:16px;
        color:#333;
        margin-top:15px;
        text-indent:32px;
       
    }

    /* 底部 */

    .home-footer {
        width: 100%;
        height: 150px;
        background: #6BC4CD;
        margin-top: 3px;
    }

    .home-footer .company-adress {
        font-size: 14px;
        margin: 0 auto;
        color: #fff;
        text-align: left;
        height: 30px;
        line-height: 30px;
        padding-left: 30px;
        padding-top: 30px;

    }
</style>
<body>
    <div class="page-news">
        <div class="page-news-content">
            <!-- 头部登陆 -->
            <div class="home-head">
                <form class="home-head-form">
                    <p>
                        <label for="name">账号: </label>
                        <input type="text" id="name" />
                    </p>
                    <p>
                        <label for="pw">密码: </label>
                        <input type="password" id="pw" />
                    </p>
                    <input type="submit" value="登录" class="subBtn" />
                </form>
            </div>
        
            <!-- banner图部分开始 -->
            <div class="home-banner"></div>
            <!-- banner图部分结束 -->
        
            <!-- tab栏开始 -->
            <div class="home-tabs">
                <div class="tabs-menu">
                    <p>
                        <a href="home.html" title="首页">首页</a>
                    </p>
                    <p>
                        <a href="company.html" title="集团简介">集团简介</a>
                    </p>
                    <p>
                        <a href="#" title="党建工作">党建工作</a>
                    </p>
                    <p>
                        <a href="#" title="项目工程">项目工程</a>
                    </p>
                    <p>
                        <a href="#" title="重点招商">重点招商</a>
                    </p>
                </div>
        
            </div>
            <!-- tab栏结束-->

            <!-- 新闻部分 -->
            <div class="page-news-detail">
                 <div class="news-detail-box"></div>
                 <div class="news-detail-content">
                     <div class="news-detail-position">
                         <div class="position-left">您当前的位置：首页 >> 集团新闻</div>
                         <div class="position-right">
                             <div class="position-rightfont">字体大小：
                                 <span class="big">大</span>
                                 <span class="nomal">中</span>
                                 <span class="small">小</span>
                             </div>
                             <div class="pages-close">
                                 <a href="home.html">
                                        <span>x</span>
                                        <span>关闭</span>
                                 </a>
                                
                             </div>
                         </div>
                     </div>
                     <div class="news-detail-title"><?php echo ($article["title"]); ?></div>
                     <div class="news-detail-time">发布日期：<?php echo ($article["time"]); ?></div>
                     <div class="news-detail-text">
                         <div class="detail-text-inner">
                             <?php echo htmlspecialchars_decode($article['content']);?>
                         </div>
                       
                     </div>
                 </div>
            </div>

            <!-- 底部 -->
            <div class="home-footer">
                <div class="company-adress">
                    公司邮箱：123@qq.com
                    <br> 联系方式：123455764
                    <br> 公司地址：江苏省南京市啦啦啦啦啦啦啦啦啦
                </div>
            </div>
        </div>
     
    </div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(".big").click(function(){
        $(".news-detail-title").css("font-size","26px")
    })
    $(".nomal").click(function(){
        $(".news-detail-title").css("font-size","18px")
    })
    $(".small").click(function(){
        $(".news-detail-title").css("font-size","16px")
    })
</script>
</html>