--TEST--
Test set_compiler_hook when hook causes fatal error on unexistant file include (no memory leak should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--INI--
allow_url_include=On
--FILE--
<?php

xray\set_compiler_hook(function($source, $filename = null) : string { return $source; });

include(__DIR__ . '/unexistant_file.php');

?>
--EXPECTF--
Warning: include(%s/unexistant_file.php): failed to open stream: No such file or directory in %s/009.php on line 5

Warning: include(%s/unexistant_file.php): failed to open stream: No such file or directory in %s/009.php on line 5

Warning: include(): Failed opening '%s/unexistant_file.php' for inclusion (include_path='.:%s') in %s/009.php on line 5
