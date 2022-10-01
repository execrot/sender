<?php
require_once __DIR__ . '/vendor/autoload.php';

echo \Light\Core\Front::getInstance(require_once 'config.php')
  ->bootstrap()
  ->run();