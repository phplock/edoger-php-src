#edoger-php-src#

A simple and efficient PHP framework.

> For Example：

	require "path/to/edoger/launcher.php";
	
	edoger() -> create("path/to/config.file.php");
	
	edoger() -> app() -> run();

> Components List:

	edoger() -> request();
	edoger() -> respond();
	edoger() -> input();
	edoger() -> config();
	edoger() -> upload();
	edoger() -> event();
	edoger() -> app();
	edoger() -> library();
	edoger() -> log();
	edoger() -> hooks();
	edoger() -> debug();