<?php

namespace App\Module\Api;

class Exception extends \Exception
{
  /**
   * @var array
   */
  public array $errors = [];

  /**
   * @return array
   */
  public function getErrors(): array
  {
    return $this->errors;
  }

  /**
   * @param string $message
   * @param array $errors
   * @param int $code
   */
  public function __construct(string $message = "", array $errors = [], int $code = 400)
  {
    parent::__construct($message, $code);
    $this->errors = $errors;
  }
}