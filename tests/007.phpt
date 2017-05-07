--TEST--
Test set_compiler_hook when hook causes fatal error on eval (no memory leak should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--FILE--
<?php

include(__DIR__ . '/007_file.php');

?>
--EXPECTF--
Fatal error: Uncaught Error: Call to undefined function bad_function_call() in %s/007_file.php:1
Stack trace:
#0 %s/007.php(3): include()
#1 {main}
  thrown in %s/007_file.php on line 1