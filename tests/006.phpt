--TEST--
Test set_compiler_hook when hook causes fatal error on include (no memory leak should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--INI--
allow_url_include=On
--FILE--
<?php

xray\set_compiler_hook(function($source, $filename = null) : string { return $source; });

include(__DIR__ . '/006_file.php');

?>
--EXPECTF--
Fatal error: Uncaught Error: Call to undefined function bad_function_call() in %s/006_file.php:1
Stack trace:
#0 %s/006.php(5): include()
#1 {main}
  thrown in %s/006_file.php on line 1