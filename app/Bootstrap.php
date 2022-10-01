<?php

declare(strict_types=1);

namespace App;

use Light\Core\BootstrapAbstract;
use Light\Model\Config;

class Bootstrap extends BootstrapAbstract
{
  public function db()
  {
    Config::setConfig(
      $this->getConfig()['db']);
  }
}