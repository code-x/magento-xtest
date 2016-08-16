<?php

require __DIR__ . '/lib.php';

header("Content-type: image/png");

$file = $_GET['file'];
$realfile = realpath(get_behat_result_dir() . '/' . $file);
readfile($realfile);