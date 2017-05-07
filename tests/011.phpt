--TEST--
Test fatal error on file with syntax error without any hook (no segfault should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--INI--
allow_url_include=On
--FILE--
<?php

include(__DIR__ . '/011_file.php');

?>
--EXPECTF--
Parse error: syntax error, unexpected 'error' (T_STRING) in %s/011_file.php on line 1
