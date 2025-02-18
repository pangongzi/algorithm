# Pangongzi Algorithm | php 算法库

###  项目描述
`pangongzi/algorithm` 是一个 PHP 库，提供了多种算法的实现，包括雪花算法等。
目前仅有雪花算法

###  安装

使用 Composer 安装：

```bash
# 安装
composer require pangongzi/algorithm

# 更新
composer update pangongzi/algorithm

# 删除
composer remove pangongzi/algorithm

```

###  使用方法
雪花算法
初始化 首先，需要引入自动加载文件并实例化 SnowflakeService 类：

```php
<?php
require 'vendor/autoload.php';
use Pangongzi\Algorithm\Snowflake\SnowflakeService;
$snowflake = new SnowflakeService(1); // 传入机器ID
```

### 生成唯一ID
使用 generate 方法生成唯一ID：

```php
<?php
$id = $snowflake->generate();
echo "生成的唯一ID: " . $id . PHP_EOL;
```



###  编码为 Base62 字符串
使用 encode 方法将生成的ID编码为 Base62 字符串：

```php
<?php
$encodedId = SnowflakeService::encode($id);
echo "Base62 编码后的ID: " . $encodedId . PHP_EOL;
```


###  贡献
欢迎提交问题和贡献代码！请确保在提交之前阅读并遵循我们的贡献指南。


### 交流
加作者微信  
[![wechat.jpg](https://i.postimg.cc/hvvW2WWw/wechat.jpg)](https://postimg.cc/S2BvKPK7)