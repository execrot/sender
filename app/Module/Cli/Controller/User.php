<?php

declare(strict_types=1);

namespace App\Module\Cli\Controller;

use Light\Core\Controller;
use Light\Model\Exception\CallUndefinedMethod;
use Light\Model\Exception\ConfigWasNotProvided;
use Light\Model\Exception\DriverClassDoesNotExists;
use Light\Model\Exception\DriverClassDoesNotExtendsFromDriverAbstract;

class User extends Controller
{
  /**
   * @return void
   * @throws CallUndefinedMethod
   * @throws ConfigWasNotProvided
   * @throws DriverClassDoesNotExists
   * @throws DriverClassDoesNotExtendsFromDriverAbstract
   */
  public function add(): void
  {
    $token = md5(microtime());

    $user = new \App\Model\User();
    $user->tokens = [$token];
    $user->save();

    echo $token . "\n\n";
  }
}