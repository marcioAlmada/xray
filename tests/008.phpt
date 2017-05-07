--TEST--
Test set_compiler_hook when hook causes fatal error on eval (no memory leak should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--FILE--
<?php

xray\set_compiler_hook(function($source, $filename = null) : string { return $source; });

eval("bad_function_call();");

?>
--EXPECTF--
Fatal error: Uncaught Error: Call to undefined function bad_function_call() in %s/008.php(5) : eval()'d code:1
Stack trace:
#0 %s/008.php(5): eval()
#1 {main}
  thrown in %s/008.php(5) : eval()'d code on line 1