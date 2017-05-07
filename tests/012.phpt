--TEST--
Test set_compiler_hook when hook causes fatal error on unexistant file include inside eval (no memory leak should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--FILE--
<?php

xray\set_compiler_hook(function($source, $filename = null) : string { return $source; });

eval("include './unexistant_file.php';");

?>
--EXPECTF--
Warning: include(./unexistant_file.php): failed to open stream: No such file or directory in %s/012.php(5) : eval()'d code on line 1

Warning: include(./unexistant_file.php): failed to open stream: No such file or directory in %s/012.php(5) : eval()'d code on line 1

Warning: include(): Failed opening './unexistant_file.php' for inclusion (include_path='.:%s') in %s/012.php(5) : eval()'d code on line 1
