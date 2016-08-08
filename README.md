#Edoger PHP Framework (EdogerPHP)#

一个高效且简单的基于PHP7的WEB开发框架

示例：
```php
	
	//	引入框架的启动脚本文件
	require "path/to/edoger/launcher.php";
	
	//	创建你的应用程序，需要提前配置好配置文件（参数是可选的）
	//	然后立即运行应用程序
	edoger() -> create("application_configuration_file_name") -> app() -> run();
```
可用的组件:
```php
	
	edoger() -> http()
	edoger() -> http() -> request();
	edoger() -> http() -> request() -> input();
	edoger() -> http() -> request() -> input() -> getter();
	edoger() -> http() -> request() -> input() -> poster();
	edoger() -> http() -> request() -> input() -> filter();
	edoger() -> http() -> request() -> input() -> modifier();
	edoger() -> http() -> request() -> server();
	edoger() -> http() -> request() -> route();
	edoger() -> http() -> request() -> middleware();
	edoger() -> http() -> request() -> auth();
	edoger() -> http() -> respond();
	edoger() -> http() -> respond() -> engine();
	edoger() -> http() -> respond() -> middleware();
	edoger() -> http() -> respond() -> drive();
	edoger() -> http() -> respond() -> view();
	edoger() -> http() -> upload();
	edoger() -> http() -> cookie();
	edoger() -> http() -> cookie() -> reader();
	edoger() -> http() -> cookie() -> writer();
	edoger() -> http() -> session();
	edoger() -> config();
	edoger() -> event();
	edoger() -> library();
	edoger() -> library() -> curl();
	edoger() -> library() -> ftp();
	edoger() -> library() -> captcha();
	edoger() -> library() -> arr();
	edoger() -> library() -> str();
	edoger() -> library() -> image();
	edoger() -> library() -> queue();
	edoger() -> library() -> cache();
	edoger() -> logger();
	edoger() -> logger() -> handler();
	edoger() -> db();
	edoger() -> db() -> mysql();
	edoger() -> db() -> mongodb();
	edoger() -> db() -> redis();
	edoger() -> app();
	edoger() -> app() -> config();

```