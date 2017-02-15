<?php

$template = @file_get_contents('./templates/template.inc.html');
$navigation = @file_get_contents('./templates/navigation.inc.html');

$output = str_replace('{{NAVIGATION}}', $navigation, $template);
$output = str_replace('{{NOW}}', time(), $output);

echo $output;
