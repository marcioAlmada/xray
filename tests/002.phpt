--TEST--
Test set_compiler_hook error when a non string is returned from hook on include
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--INI--
allow_url_include=On
--FILE--
<?php

xray\set_compiler_hook(function($source, $filename = null) { return null; });

include(__DIR__ . '/002_file.php');

?>
--EXPECTF--
Fatal error: Uncaught Exception: Return value of X-Ray compiler hook must be a string in %s/002.php:5
Stack trace:
#0 {main}
  thrown in %s/002.php on line 5