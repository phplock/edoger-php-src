#edoger-php-src#

A simple and efficient PHP framework.

For Example：
```php
	require "path/to/edoger/launcher.php";
	
	edoger() -> create("application_configuration_file_name");
	
	edoger() -> app() -> run();
```
Components List:
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