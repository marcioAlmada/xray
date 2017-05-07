--TEST--
Test set_compiler_hook error when a non string is returned from hook on eval
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--FILE--
<?php

xray\set_compiler_hook(function($source, $filename = null) { return null; });

// eval
eval("echo 'eval';");

?>
--EXPECTF--
Fatal error: Uncaught Exception: Return value of X-Ray compiler hook must be a string in %s/003.php:6
Stack trace:
#0 {main}
  thrown in %s/003.php on line 6