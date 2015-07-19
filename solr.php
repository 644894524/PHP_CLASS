
1. solr 的目录结构 

(1).dist目录

该目录包含build过程中产生的war和jar文件，以及相关的依赖文件。需要部署时，其实就是将该目录下面的apache-solr-1.4.war部署到Jetty上面去，并重命名为solr.war。

(2).example目录
这个目录实际上是Jetty的安装目录。其中包含了一些样例数据和一些Solr的配置。

* example/etc：该目录包含了Jetty的配置，在这里我们可以将Jetty的默认端口从8983改为80端口。

* example/solr：该目录是一个包含了默认配置信息的Solr的home目录。

* example/webapps：Jetty的webapps目录，该目录通常用来放置Java的Web应用程序。在Solr中，前面提到的solr.war文件就部署在这里。 

solr home  example/solr 就是一个solr home。 

2.概念core

打个比方，solr就像是个操作系统，安装在操作系统中的软件就是“core”，每个core有自身的配置文件及数据。

解压后的文件/example/solr/collection1就是一个core，这个core由/example/solr/solr.xml管理。

一个core如果想让solr管理，就需要注册到solr.xml配置文件中，solr.xml配置如见如下： 

 <?xml version="1.0" encoding="UTF-8" ?>
<solr persistent="true">
 <cores defaultCoreName="collection1" adminPath="/admin/cores" zkClientTimeout="${zkClientTimeout:15000}" hostPort="8983" hostContext="solr">
<core loadOnStartup="true" instanceDir="collection1" transient="false" name="collection1"/>
</cores>
</solr>

在实际的项目中，有时候一个solr下面不可能只有一个core，会有多个。比如企 业搜索、产品搜索等等。这时你可以复制一份或多份/example/solr/collection1到你的solr home中，并改成你想要的文件名，最后把新添加的core注册到/example/solr/solr.xml中： 

 <?xml version="1.0" encoding="UTF-8" ?>
<solr persistent="true">
 <cores defaultCoreName="collection1" adminPath="/admin/cores" zkClientTimeout="${zkClientTimeout:15000}" hostPort="8983" hostContext="solr">
 <core loadOnStartup="true" instanceDir="collection1" transient="false" name="collection1"/><core loadOnStartup="true" instanceDir="newCore" transient="false" name="newCore"/> </cores> </solr>

每个core中都有两个文件，conf和data

conf：主要用于存放core的配置文件，
(1).schema.xml用于定义索引库的字段及分词器等，这个配置文件是核心文件
(2).solrconfig.xml定义了这个core的配置信息，比如： 

 <autoCommit> 
 <maxTime>15000</maxTime> 
 <openSearcher>false</openSearcher> 
 </autoCommit>
定义了什么时候自动提交，提交后是否开启一个新的searcher等等。

data：主要用于存放core的数据，即index-索引文件和log-日志记录。



solr 的启动 

命令  ：  cd /home/cluster/solr-4.8.1/example/

java -jar start.jar

访问 ：http://localhost:8983/solr/



solr 和  php 相结合 

  
$options = array (
     'hostname' => '127.0.0.1' ,
);
  
$client = new SolrClient( $options , "4.0" ); 
// 参数4.0针对Solr4.x，其他版本时忽略
  
$doc = new SolrInputDocument();
  
$doc ->addField( 'id' , 100);
$doc ->addField( 'title' , 'Hello Wolrd' );
$doc ->addField( 'description' , 'Example Document' );
$doc ->addField( 'cat' , 'Foo' );
$doc ->addField( 'cat' , 'Bar' );
  
$response = $client ->addDocument( $doc );
  
$client ->commit();
  
/* ------------------------------- */
  
$query = new SolrQuery();
  
$query ->setQuery( 'hello' );
  
$query ->addField( 'id' )
->addField( 'title' )
->addField( 'description' )
->addField( 'cat' );
  
$queryResponse = $client ->query( $query );
  
$response = $queryResponse ->getResponse();
  
print_r( $response ->response->docs );


eg : 添加solr

<?php

$options = array
(
    'hostname' => SOLR_SERVER_HOSTNAME,
    'login'    => SOLR_SERVER_USERNAME,
    'password' => SOLR_SERVER_PASSWORD,
    'port'     => SOLR_SERVER_PORT,
);

$client = new SolrClient($options);

$doc = new SolrInputDocument();

$doc->addField('id', 334455);
$doc->addField('cat', 'Software');
$doc->addField('cat', 'Lucene');

$updateResponse = $client->addDocument($doc);

$client->commit();

print_r($updateResponse->getResponse());

?>

执行结果：

SolrObject Object
(
    [responseHeader] => SolrObject Object
        (
            [status] => 0
            [QTime] => 1
        )

)

eg : 删除

<?php
  
  
  
  
  

   
   
   
   
   
 
    
$options = array
(
    'hostname' => SOLR_SERVER_HOSTNAME,
    'login'    => SOLR_SERVER_USERNAME,
    'password' => SOLR_SERVER_PASSWORD,
    'port'     => SOLR_SERVER_PORT,
);

$client = new SolrClient($options);

//字段 ： 值
$client->deleteByQuery("*:*");
$client->commit();

?> 
   

   
   
   
   
   
 
    
 
   

   
   
   
   
   
 
     
   

   
   
   
   
   
 
    eg : 查询 
   

   
   
   
   
   
 
    
 
   

   
   
   
   
   
 
    <?php

$options = array
(
    'hostname' => 'localhost',
    'login'    => 'username',
    'password' => 'password',
    'port'     => '8983',
);

$client = new SolrClient($options);

$query = new SolrQuery();

$query->setQuery('lucene');

$query->setStart(0);

$query->setRows(50);

$query->addField('cat')->addField('features')->addField('id')->addField('timestamp');

$query_response = $client->query($query);

$response = $query_response->getResponse();

print_r($response);

?> 
   

  
  
  
  
  

1. PHP中文手册在线版[包含最新翻译]: http://cn2.php.net/manual/zh/

2. Apache2.2.x在线文档: http://httpd.apache.org/docs/2.2/zh-cn/

3. Windows下以Apache handler方式安装PHP说明: http://cn2.php.net/manual/zh/install.windows.apache2.php

4. Windows下安装PHP扩展库: http://cn2.php.net/manual/zh/install.windows.extensions.php

5. Apache for Windows下载地址: http://www.apachehaus.com/cgi-bin/download.plx#APACHE22VC09
注意: 官方现在已经不提供二进制版本了, 这里用的是官方推荐的地址, 这里有第三方已经编译好的二进制包
注意: 下载Apache时注意位是64位还是32位的, 主要是与PHP的位数相匹配, PHP5.5和5.6有x86(32位)和x64(64位)两种, PHP5.4及以下都是x86的
1) 将 path/to/apache2/bin 目录加入环境变量
2) path/to/apache2/bin/ApacheMonitor.exe 可以打开控制台

6. PHP for Windows下载地址: http://windows.php.net/download/#php-5.4-ts-VC9-x86 
注意: 如果是以Apache模块方式安装PHP, 选择TS版的, Nginx + PHP安装是, 选择NTS版的
官方已经不再支持PHP5.3的版本了

7. 安装配置请参考3, 安装过程注意事项:
1) 修改扩展库的地址(php.ini 中 extension_dir = "X:\path\to\php\ext", 详情见4)
2) 将 path/to/php 和 path/to/php/ext 目录加入环境变量(以避免在命令行执行php时提示找不到PHP或者找不到扩展)

Solr扩展windows版下载地址:
http://pecl.php.net/package/solr/2.1.0/windows

注意: 扩展选择TS或NTS取决于PHP是TS还是NTS.





