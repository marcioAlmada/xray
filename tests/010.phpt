--TEST--
Test fatal error on unexistant file include without any hook (no memory leak should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--INI--
allow_url_include=On
--FILE--
<?php

include(__DIR__ . '/unexistant_file.php');

?>
--EXPECTF--
Warning: include(%s/unexistant_file.php): failed to open stream: No such file or directory in %s/010.php on line 3

Warning: include(%s/unexistant_file.php): failed to open stream: No such file or directory in %s/010.php on line 3

Warning: include(): Failed opening '%s/unexistant_file.php' for inclusion (include_path='.:%s') in %s/010.php on line 3
