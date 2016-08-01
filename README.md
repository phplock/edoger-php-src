#Edoger PHP Framework (EdogerPHP)#

一个高效且简单的基于PHP7的WEB开发框架

示例：
```php
	
	//	引入框架的启动脚本文件
	require "path/to/edoger/launcher.php";
	
	//	创建你的应用程序，需要提前配置好配置文件
	edoger() -> create("application_configuration_file_name");
	
	//	运行应用程序
	edoger() -> app() -> run();
```
可用的组件:
```php
	edoger() -> request();
	edoger() -> request() -> server();

	edoger() -> respond();
	edoger() -> respond() -> view();

	edoger() -> input();

	edoger() -> config();

	edoger() -> upload();

	edoger() -> event();

	edoger() -> library();
	
	edoger() -> log();

	edoger() -> app();
	edoger() -> app() -> config();
```