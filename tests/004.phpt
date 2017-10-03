--TEST--
Test set_compiler_hook when hook causes fatal error on include (no memory leak should occur)
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--INI--
allow_url_include=On
--FILE--
<?php

xray\set_compiler_hook(function($source, $filename = null) { bad_function_call(); });

include('data://text/plain;base64,' . base64_encode("<?php echo 'include';"));

?>
--EXPECTF--
Fatal error: Uncaught Error: Call to undefined function bad_function_call() in %s/004.php:3
Stack trace:
#0 %s/004.php(5): {closure}('<?php echo 'inc...', 'data://text/pla...')
#1 %s/004.php(5): include()
#2 {main}
  thrown in /%s/004.php on line 3