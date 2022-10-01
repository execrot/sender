<?php

declare(strict_types=1);

namespace App\Module\Api\Plugin;

use Throwable;
use Light\Core\Plugin;
use Light\Core\Request;
use Light\Core\Response;
use Light\Core\Router;
use App\Module\Api\Exception;
use App\Module\Api\Service\User;

class Auth extends Plugin
{
  /**
   * @param Request $request
   * @param Response $response
   * @param Router $router
   *
   * @return void
   * @throws Exception
   */
  public function preRun(Request $request, Response $response, Router $router): void
  {
    parent::preRun($request, $response, $router);

    try {
      User::auth($request->getHeader('token'));

    } catch (Throwable $e) {
      throw new Exception('Invalid token');
    }
  }
}