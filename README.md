## 环境依赖
composer 依赖
```shell
php 5.x
```

## 部署步骤

在本地模块进行安装 确保Install目录存在

## 目录结构描述
```shell
D:.
│  Config.inc.php 模块配置配置简介
│  README.md
│
├─Base
│      Cron.class.php 
│
├─Conf
│      config.php
│
├─Controller
│      AuthCronController.class.php
│      CronController.class.php
│      IndexController.class.php
│      MigragteController.class.php
│
├─CronScript
│      DeleteCronLog.class.php
│      DeleteOperationlog.class.php
│      Demo.class.php
│      Refresh_category.class.php
│      Refresh_custompage.class.php
│      Refresh_index.class.php
│
├─Install
│      Cron.sql 
│      Install.class.php
│      Menu.php 
│
├─Model
│      CronConfigModel.class.php
│      CronLogModel.class.php
│      CronModel.class.php
│      OperationlogModel.class.php
│      SchedulingLogModel.class.php
│
├─Service
│      CronService.class.php
│
├─Uninstall
│      Cron.sql
│      Uninstall.class.php
│
└─View
    └─Cron
            add.php
            dashboard.php
            edit.php
            index.php
            logs.php
            scheduling_logs.php
```

##版本内容更新


##### 版本号 ： 2.2.1.1 （2020年8月6日）

功能  | 介绍  
 ---- | ----- 
 初始化项目  | 完善项目的文档说明，添加基本的目录结构介绍 
 
<br> 
<br> 

##### 版本号 ： 2.2.1.2 （2020年8月6日）

功能  | 介绍  
 ---- | ----- 
 异常文本测试  |  添加测试异常demo 
 
<h5>详细说明</h4> 
 
<p>1.测试异常接收的位置</p>
 <img src="https://karuike.oss-cn-shenzhen.aliyuncs.com/d/file/module_upload_images/2020/08/5f2bd869131bc.jpg" alt="PHP: The Right Way"/>
<br>
<br>
<br>
<p>2.查看异常的位置</p>
 <img src="https://karuike.oss-cn-shenzhen.aliyuncs.com/d/file/module_upload_images/2020/08/5f2bd7cbdf2fb.jpg" alt="PHP: The Right Way"/>
 

