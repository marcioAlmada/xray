--TEST--
Test set_compiler_hook and restore_compiler_hook multiple times
--SKIPIF--
<?php if (!extension_loaded("xray")) die("skip X-Ray not loaded"); ?>
--INI--
allow_url_include=On
--FILE--
<?php

// just a silly source mutator
xray\set_compiler_hook(function($source, $file = null) : string {
    static $i = 0;
    $i++;
    var_dump(compact('source', 'file', 'i'));
    return str_replace(['foo', 'toto'], ['bar', 'tata'], $source);
});

include('data://text/plain;base64,' . base64_encode("<?php echo 'foo', PHP_EOL;"));
eval("echo 'toto', PHP_EOL;");

$hook = xray\restore_compiler_hook();

include('data://text/plain;base64,' . base64_encode("<?php echo 'foo', PHP_EOL;"));
eval("echo 'toto', PHP_EOL;");

xray\set_compiler_hook($hook);

include('data://text/plain;base64,' . base64_encode("<?php echo 'foo', PHP_EOL;"));
eval("echo 'toto', PHP_EOL;");

$hook = xray\restore_compiler_hook();

include('data://text/plain;base64,' . base64_encode("<?php echo 'foo', PHP_EOL;"));
eval("echo 'toto', PHP_EOL;");

?>
--EXPECTF--
array(3) {
  ["source"]=>
  string(26) "<?php echo 'foo', PHP_EOL;"
  ["file"]=>
  string(61) "data://text/plain;base64,PD9waHAgZWNobyAnZm9vJywgUEhQX0VPTDs="
  ["i"]=>
  int(1)
}
bar
array(3) {
  ["source"]=>
  string(21) "echo 'toto', PHP_EOL;"
  ["file"]=>
  NULL
  ["i"]=>
  int(2)
}
tata
foo
toto
array(3) {
  ["source"]=>
  string(26) "<?php echo 'foo', PHP_EOL;"
  ["file"]=>
  string(61) "data://text/plain;base64,PD9waHAgZWNobyAnZm9vJywgUEhQX0VPTDs="
  ["i"]=>
  int(3)
}
bar
array(3) {
  ["source"]=>
  string(21) "echo 'toto', PHP_EOL;"
  ["file"]=>
  NULL
  ["i"]=>
  int(4)
}
tata
foo
toto
