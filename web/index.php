<?php

ob_start();
require('./templates/template.inc.html');
$output = ob_get_clean();

echo $output;
