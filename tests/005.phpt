--TEST--
Test set_compiler_hook when hook causes fatal error on eval (no memory leak should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--FILE--
<?php

xray\set_compiler_hook(function($source, $filename = null) { bad_function_call(); });

eval("echo 'eval';");

?>
--EXPECTF--
Fatal error: Uncaught Error: Call to undefined function bad_function_call() in %s/005.php:3
Stack trace:
#0 %s/005.php(5): {closure}('echo 'eval';', NULL)
#1 %s/005.php(5): eval()
#2 {main}

Next Exception: Return value of X-Ray compiler hook must be a string in %s/005.php:5
Stack trace:
#0 {main}
  thrown in %s/005.php on line 5
