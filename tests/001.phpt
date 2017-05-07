--TEST--
Test set_compiler_hook upon includes, requires and eval
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--INI--
allow_url_include=On
--FILE--
<?php

// just a silly source mutator
xray\set_compiler_hook(function($source, $file = null) : string {
    var_dump(compact('source', 'file'));
    return str_replace('<-', 'return ', $source);
});

// include
include(__DIR__ . '/001_file.php');
echo include_file_test(), PHP_EOL;

include('data://text/plain;base64,' . base64_encode("<?php function include_test(){ <- 'include_test'; }"));
echo include_test(), PHP_EOL;

// require
include('data://text/plain;base64,' . base64_encode("<?php function require_test(){ <- 'require_test'; }"));
echo require_test(), PHP_EOL;

// require_once
include('data://text/plain;base64,' . base64_encode("<?php function require_once_test(){ <- 'require_once_test'; }"));
echo require_once_test(), PHP_EOL;

// include_once
include('data://text/plain;base64,' . base64_encode("<?php function include_once_test(){ <- 'include_once_test'; }"));
echo include_once_test(), PHP_EOL;

// eval
eval("function eval_test(){ <- 'eval_test'; }");
echo eval_test(), PHP_EOL;

?>
--EXPECTF--
array(2) {
  ["source"]=>
  string(61) "<?php function include_file_test(){ <- 'include_file_test'; }"
  ["file"]=>
  string(%s) "%s/001_file.php"
}
include_file_test
array(2) {
  ["source"]=>
  string(51) "<?php function include_test(){ <- 'include_test'; }"
  ["file"]=>
  string(93) "data://text/plain;base64,PD9waHAgZnVuY3Rpb24gaW5jbHVkZV90ZXN0KCl7IDwtICdpbmNsdWRlX3Rlc3QnOyB9"
}
include_test
array(2) {
  ["source"]=>
  string(51) "<?php function require_test(){ <- 'require_test'; }"
  ["file"]=>
  string(93) "data://text/plain;base64,PD9waHAgZnVuY3Rpb24gcmVxdWlyZV90ZXN0KCl7IDwtICdyZXF1aXJlX3Rlc3QnOyB9"
}
require_test
array(2) {
  ["source"]=>
  string(61) "<?php function require_once_test(){ <- 'require_once_test'; }"
  ["file"]=>
  string(109) "data://text/plain;base64,PD9waHAgZnVuY3Rpb24gcmVxdWlyZV9vbmNlX3Rlc3QoKXsgPC0gJ3JlcXVpcmVfb25jZV90ZXN0JzsgfQ=="
}
require_once_test
array(2) {
  ["source"]=>
  string(61) "<?php function include_once_test(){ <- 'include_once_test'; }"
  ["file"]=>
  string(109) "data://text/plain;base64,PD9waHAgZnVuY3Rpb24gaW5jbHVkZV9vbmNlX3Rlc3QoKXsgPC0gJ2luY2x1ZGVfb25jZV90ZXN0JzsgfQ=="
}
include_once_test
array(2) {
  ["source"]=>
  string(39) "function eval_test(){ <- 'eval_test'; }"
  ["file"]=>
  NULL
}
eval_test
