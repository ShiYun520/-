# 项目部署  

## 准备
- 下载源码文件到本地
- 上传源码到宝塔或其他面板
- 创建数据库并上传源码内的数据库
- 修改源码内文件数据库信息
~~~
- delete_subscription.php
- subscriptions.php
- api.php
~~~
以下为修改内容
~~~
$host = 'localhost';
$dbname = 'subscription_db';
$username = 'qiqi520';
$password = 'qiqi520';
~~~
- 本项目均为AI全程制作，如有不足请多担待...
