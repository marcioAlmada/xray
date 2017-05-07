--TEST--
Test fatal error on unexistant file include within eval without any hook (no memory leak should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--FILE--
<?php

eval("include './unexistant_file.php';");

?>
--EXPECTF--
Warning: include(./unexistant_file.php): failed to open stream: No such file or directory in %s/013.php(3) : eval()'d code on line 1

Warning: include(./unexistant_file.php): failed to open stream: No such file or directory in %s/013.php(3) : eval()'d code on line 1

Warning: include(): Failed opening './unexistant_file.php' for inclusion (include_path='.:%s') in %s/013.php(3) : eval()'d code on line 1