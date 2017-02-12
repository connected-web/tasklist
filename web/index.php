<?php

ob_start();
require('./templates/template.inc.html');
$output = ob_get_clean();

$output = str_replace('{{NOW}}', time(), $output);

echo $output;
