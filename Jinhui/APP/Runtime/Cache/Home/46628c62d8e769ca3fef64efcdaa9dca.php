<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 引入公共样式 -->
    <link rel="stylesheet" href="/Public/libs/common.css">
    <title>Document</title>
</head>

<style>
    *{
        margin:0;
        padding:0;
    }
    .pages-company {
        width: 100%;
        height: 100%;
        background: #CAE8FF;
    }

    .pages-company-content {
        width: 1000px;
        margin: 0 auto;
    }

    /* 具体内容 */

    .company-detail {
        width: 100%;
        height: 600px;
        padding-top: 20px;
        background: #fff;
    }

    .detail-left {
        width: 30%;
        height: 600px;
        float: left;
        
    }
    .detail-left-title{
        font-size:14px;
        font-weight:700;
        color:#fff;
        height:30px;
        font-family:'微软雅黑';
        border-radius:40%;
        width:200px;
        line-height:30px;
        background: #59BAFF;
        text-align: center;
    }
    .detail-left-title:hover{
        color:red;
    }
    .detail-left-picList{
        width:100%;
        margin-top:15px;
        
    }
    .detail-left-picList .picList-item{
        width:95%;
        margin:0 auto;
        height:100px;
        margin-top:5px;
    }
    .detail-left-picList .picList-item img{
        width:100%;
        height:100px;
    }
    .detail-left-picList .picList-item img:hover{
        transform:scale(1.3)
    }
    .detail-right {
        width: 70%;
        float: right;
        height: 600px;
        background: #fff;
    }

    .detail-right-title {
        height: 40px;
        border: 1px solid #ccc;
        padding-left: 30px;
        line-height: 40px;
        font-size: 14px;
    }

    .detail-right-news {
        width: 100%;
        height: 536px;
        margin-top: 20px;
        border: 1px solid #ccc;
    }

    .detail-right-news .news-title {
        line-height: 40px;
        padding-left: 36px;
        font-weight: bold;
        font-size: 14px;
        color: #c00;
        border-bottom: dotted #333 2px;
    }

    .news-list {
        width: 100%;
        min-height: 360px;
        padding: 10px;
    }

    .news-list-Item {
        color: #999999;
        width: 96%;
        height: 30px;
        border-bottom: 1px solid #ccc;
        list-style: none;
    }

    .news-list-Item-text {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        line-height: 30px;
        float: left;
        background-image: url(/Public/images/news.png);
        background-repeat: no-repeat;
        background-size: 18px 18px;
        background-position: 5px 6px;
      
    }

    .news-list-Item-text a {
        color: #000000;
        text-decoration: none;
        margin-left:25px;
    }

    .news-list-Item-text a:hover {
        color: red;
    }

    .news-list-Item-date {
        font-size: 12px;
        line-height: 30px;
        float: right;
        margin-right: 10px;
        color: #000;
    }

    /* 分页设置 */
    .news-list-page{
      width: 80%;
      margin: 0 auto;
      height: 30px;
      text-align: center;
    }
    .jp-previous,.jp-next {
      font-size: 14px;
      height: 30px;
      line-height: 30px;
      color: #000;
      margin-left: 5px;
      margin-right: 5px;
    }
    a.jp-disabled{
      color:#ccc;
    }

   .news-list-page a{
        height: 30px;
        line-height: 30px;
        text-align: center;
        padding-left: 10px;
        padding-right: 10px;
        text-decoration: none;

    }
    a.jp-current{
        height: 30px;
        line-height: 30px;
        text-align: center;
        padding-left: 10px;
        padding-right: 10px;
        color:red;
        font-weight:bold;
    }
   
   /* 底部 */
   .home-footer{
      width:100%;
      height: 150px;
      background:#6BC4CD;

  }
  .home-footer  .company-adress {
      font-size:14px;
      margin:0 auto;
      color:#fff;
      text-align: left;
      height:30px;
      line-height: 30px;
      padding-left:30px;
      padding-top:30px;
      
  }
</style>

<body>
    <div class="pages-company">
        <div class="pages-company-content">
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
                        <a href="<?php echo U('Index/Index');?>" title="首页">首页</a>
                    </p>
                    <p>
                        <a href="<?php echo U('Company/Index');?>" title="城投动态">城投动态</a>
                    </p>
                    <p>
                        <a href="" title="党建工作">党建工作</a>
                    </p>
                    <p>
                        <a href="" title="项目工程">项目工程</a>
                    </p>
                    <p>
                        <a href="" title="重点招商">重点招商</a>
                    </p>
                </div>
            </div>
            <!-- tab栏结束-->
           
            <!-- 具体内容 -->
            <div class="company-detail">
                <div class="detail-left">
                    <div class="detail-left-title">带你看不一样的风景</div>
                    <div class="detail-left-picList">
                        <div class="picList-item">
                            <img src="/Public/images/pic1.jpg" alt="">
                        </div>
                        <div class="picList-item">
                            <img src="/Public/images/pic2.jpg" alt="">
                        </div>
                        <div class="picList-item">
                            <img src="/Public/images/pic3.jpg" alt="">
                        </div>
                        <div class="picList-item">
                            <img src="/Public/images/pic4.jpg" alt="">
                        </div>
                        <div class="picList-item">
                            <img src="/Public/images/pic5.jpg" alt="">
                         </div>
                    </div>
                </div>
                <div class="detail-right">
                    <div class="detail-right-title">
                        您的位置： 首页 >>  城投动态
                    </div>
                    <div class="detail-right-news">
                        <div class="news-title">城投动态</div>
                        <ul class="news-list" id="news-list">
                            <li class="news-list-Item">
                                <div class="news-list-Item-text">
                                    <a href=""> 创历史新高 2017年长江水务售水量首超2亿吨</a>
                                </div>
                                <div class="news-list-Item-date">2017-03-21</div>
                            </li>
                            <li class="news-list-Item">
                                <div class="news-list-Item-text">
                                    <a href=""> 创历史新高 2017年长江水务售水量首超2亿吨</a>
                                </div>
                                <div class="news-list-Item-date">2017-03-21</div>
                            </li>
                            <li class="news-list-Item">
                                <div class="news-list-Item-text">
                                    <a href=""> 创历史新高 2017年长江水务售水量首超2亿吨</a>
                                </div>
                                <div class="news-list-Item-date">2017-03-21</div>
                            </li>
                            <li class="news-list-Item">
                                <div class="news-list-Item-text">
                                    <a href=""> 创历史新高 2017年长江水务售水量首超2亿吨</a>
                                </div>
                                <div class="news-list-Item-date">2017-03-21</div>
                            </li>
                            <li class="news-list-Item">
                                <div class="news-list-Item-text">
                                    <a href=""> 创历史新高 2017年长江水务售水量首超2亿吨</a>
                                </div>
                                <div class="news-list-Item-date">2017-03-21</div>
                            </li>
                            <li class="news-list-Item">
                                <div class="news-list-Item-text">
                                    <a href=""> 创历史新高 2017年长江水务售水量首超2亿吨</a>
                                </div>
                                <div class="news-list-Item-date">2017-03-21</div>
                            </li>
                        </ul>
                        <div class="news-list-page"> </div>
                    </div>
                </div>
            </div>
             <!-- 具体内容 结束-->

             <!-- 底部 -->
             <div class="home-footer">
                    <div class="company-adress">
                          公司邮箱：123@qq.com<br>
                          联系方式：123455764<br>
                         公司地址：江苏省南京市啦啦啦啦啦啦啦啦啦
                        
                    </div>
              </div>
        </div>
    </div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<!-- 分页插件 -->
<script src="/Public/libs/jPages.js"></script>
<script src="/Public/libs/jquery.pagination.js"></script>
<script>
    // 分页设置
    $(function(){
     $("div.news-list-page").jPages({
       containerID : "news-list",
       first:false,
       previous:'前一页',
       next:'下一页',
       last:false,
       perPage:2,//每页显示条数
  });
});
</script>
</html>