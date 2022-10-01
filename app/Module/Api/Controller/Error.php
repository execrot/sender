<?php

declare(strict_types=1);

namespace App\Module\Api\Controller;

use App\Module\Api\Exception;
use Light\Core\ErrorController;

class Error extends ErrorController
{
  /**
   * @return array|string[]
   */
  public function index(): array
  {
    $error = ['error' => 'Unknown error'];

    $exception = $this->getException();

    if ($exception instanceof Exception) {
      $error = [
        'message' => $exception->getMessage(),
        'errors' => $exception->getErrors()
      ];
    }

    if ($this->isExceptionEnabled()) {
      $error['debug'] = [
        'status' => $exception->getCode(),
        'exception' => get_class($exception),
        'message' => $exception->getMessage(),
        'trace' => $exception->getTrace(),
        'request' => [
          'method' => $this->getRequest()->getMethod(),
          'params' => [
            'query' => $this->getRequest()->getGetAll(),
            'body' => $this->getRequest()->getBody()]]];
    }

    return $error;
  }
}