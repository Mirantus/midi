<?
require('../lib/php/Less.php');
$less = new lessc;
$less->setFormatter('compressed');
$css = $less->compileFile('site.less');
file_put_contents('site.css', $css);
echo $css;