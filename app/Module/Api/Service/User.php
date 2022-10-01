<?php

declare(strict_types=1);

namespace App\Module\Api\Service;

class User
{
  /**
   * @var \App\Model\User
   */
  static private \App\Model\User $user;

  /**
   * @param string $token
   * @return void
   */
  static public function auth(string $token): void
  {
    self::$user = \App\Model\User::one([
      'tokens' => $token
    ]);
  }

  /**
   * @return \App\Model\User
   */
  public static function info(): \App\Model\User
  {
    return self::$user;
  }
}