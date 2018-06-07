# @aligames/maga-client-php-open

阿里游戏 API 网关 - PHP SDK

该SDK提供了请求阿里游戏接口网关的相关封装方法，使开发者能快速接入接口网关。同时提供了网关接口协议的实现方法，让开发者能快速开发出符合协议的接口。

## 一、安装依赖

1. 仅支持 `php 5.4` 以上版本
> 下载地址：http://php.net/downloads.php

2. PHP必须安装`mcrypt扩展`
> 安装方法暂请自行百度

3. PHP配置修改
```
; 若开启可能会导致解密失败
display_errors = Off
```


## 二、使用方法

## 作为消费者，发起请求


```
可直接参考example目录下的demo.php
```

1.首先配置config目录下的mgConfig.php

```
//阿里游戏接口网关访问配置 [服务消费方  访问  阿里游戏接口网关]
$mgConfig['client']['url'] = '{接口域名}';
$mgConfig['client']['appKey'] = '{应用ID}';
$mgConfig['client']['appSecret'] = '{秘钥}';//同时用于加密解密和接口签名
```
当然您还可以配置相关日志目录和日志级别，默认是放在SDK根目录下。现SDK会出现的日志级别只有3种，生产环境建议只打印error日志，默认配置是3种都打印。

```
//阿里游戏接口网关访问日志配置（包括本地访问阿里游戏接口网关日志[maga_outapi_...]、本地服务接口被访问日志[maga_inapi_...]）
$mgConfig['logs']['level'] = array('info','warn','error');
$mgConfig['logs']['dir'] = __DIR__ . '/../logs/';
```


2.然后引入类文件，类文件路径请自行调整。

```
require_once  __DIR__ . '/../client/mgApiClient.php';
```

3.接着初始化请求类

```
$apiClient = new mgApiClient();
```
4.组织好参数后调用阿里游戏接口网关获取结果

```
$params = array(
    'key1' => 'value1',
    'key2' => array(
        'subkey1' => 'subvalue1',
        'subkey2' => 'subvalue2',
    ),
    'key3' => 'value3'
);
$params['demokey'] = '参数例子';
$result = $apiClient->callMagaApi({方法名},$params);
```
> 注意：方法名会与配置中的url直接拼接。即 **{接口域名}{方法名}** ，您可能容易漏掉中间的斜杠



## 作为服务提供方，提供接口服务

> 前言：如果我们SDK符合你们代码的规范，建议直接使用我们的API服务统一入口类(api/dispatch.php)，您只需要编写相关API实现即可。

> 注意：由于阿里游戏网关服务器请求本地API的url格式是规范化的，需做url rewrite才可将其转换为本地的接口地址。
例如请求URL为：http://{域名}/open/demo/demoApi，若要将其转换为：http://{域名}/magasdk目录/api/dispatch.php?api=open.demo.demoApi,则可以配置rewrite规则为：

```
# 该规则适用于nginx服务器配置
rewrite ^/open/(.+)/(.+)$ /magasdk目录/api/dispatch.php?api=open.$1.$2 last;
```

##### 相关API接口实现如下（有两种实现方式）：
1.首先配置config目录下的mgConfig.php：

```
//本地服务接口访问配置 [阿里游戏接口网关  访问  服务消费方]
$mgConfig['restfulapi']['apiDir'] = __DIR__ . '/../example/api/';//api文件目录，该配置在使用第二种方式编写接口时使用，第一种方式不需要
$mgConfig['restfulapi']['appKey'] = '{应用ID}';
$mgConfig['restfulapi']['appSecret'] = '{秘钥}';//同时用于加密解密和接口签名
```

2.然后编写接口代码
```
编写接口代码有两种方式：
第一种：不使用API统一入口，这种方式的缺点是：接口的公共逻辑需要在每个接口都实现一遍，使代码冗余度高，修改公共逻辑时需在每个接口都修改一遍。
第二种（推荐）：使用API统一入口，通过在统一入口管理公共逻辑，使代码结构更合理。
```
> 第一种方式接口例子：

```
//1.引入api接口助手类
require_once __DIR__ . '/../../../api/mgApiHelper.php';

//2.初始化接口
$api = 'open.demo.demo';//接口名称，用于日志记录
mgApiHelper::init($api);

//3.执行接口逻辑前的相关拦截，例如签名校验、参数获取等
mgApiHelper::beforeAction();

//4.获取接口参数
$value1 = mgApiHelper::getParam('key1');
$value2 = mgApiHelper::getParam('key2');
$value3 = mgApiHelper::getParam('key3');

//5.错误返回
if(empty($value3)){
    //该方法只是错误结果返回封装例子，具体封装请参考方法内部逻辑，然后自行实现。
    //mgApiHelper::demo_error('500001','param error');
}

//6.接口具体处理逻辑

//7.成功返回
$return = array(
    'list' => array(
        'key1' => 'value1',
        'key2' => 'value2'
    ),
    'totalCount' => 2
);
//同理，该方法只是成功结果返回封装例子，具体封装请参考方法内部逻辑，然后自行实现。
mgApiHelper::demo_success($return);
```

> 第二种方式接口例子

1) 编写统一API入口类

> SDK提供的默认统一入口的代码文件位于:api/dispatch.php

```
//这两个文件必须引入。
require_once 'mgApiHelper.php';
require_once 'state.php';

//获取接口名

$api = empty($_GET["api"]) ? "" : $_GET["api"];

//1.初始化**[!必须!]**
mgApiHelper::init($api);

//2.检查API名称格式，这里格式为：{API目录}.{API文件名}
list ($module, $action) = explode('.', $api);
if (empty($module) || empty ($action)) {
    mgApiHelper::system_error(State::API_NOT_FOUND,'api not found');
}

//3.检查API文件是否存在
$config = include __DIR__ . '/../config/mgConfig.php';
$apiDir = $config['restfulapi']['apiDir'];
$file = "{$apiDir}{$module}/{$action}.php";
if (!is_file($file)) {
    mgApiHelper::system_error(State::API_NOT_FOUND,'api not found');
}

//4.签名检查等操作**[!必须!]**
try {
    mgApiHelper::beforeAction();
} catch (Exception $e) {
    mgApiHelper::system_error(State::SYSTEM_ERROR,'exception before action :'.json_encode($e));
}

//5.执行接口
try {
    require $file;
} catch (Exception $e) {
    mgApiHelper::system_error(State::SYSTEM_ERROR,'exception on exec api file:'.json_encode($e));
}

```

2) 编写具体接口

> 如果您使用我们默认的API统一入口，我们的api名称格式默认为：open.{目录名}.{接口名}，具体请参考example/api/demo/demo.php,其接口名为[open.demo.demo],请求的地址是: {您的域名}/{MAGA-SDK目录}/api/dispatch.php?api=open.demo.demo

```
//1.引入api接口助手类
require_once __DIR__ . '/../../../api/mgApiHelper.php';

//2.获取接口参数
$value1 = mgApiHelper::getParam('key1');
$value2 = mgApiHelper::getParam('key2');
$value3 = mgApiHelper::getParam('key3');

//3.错误返回
if(empty($value3)){
    //该方法只是错误结果返回封装例子，具体封装请参考方法内部逻辑，然后自行实现。
    //mgApiHelper::demo_error('500001','param error');
}

//4.接口具体处理逻辑

//5.成功返回
$return = array(
    'list' => array(
        'key1' => 'value1',
        'key2' => 'value2'
    ),
    'totalCount' => 2
);
//同理，该方法只是成功结果返回封装例子，具体封装请参考方法内部逻辑，然后自行实现。
mgApiHelper::demo_success($return);
```


## 反馈

- 联系接口同学
- 或提交 [Issue](待定)
 