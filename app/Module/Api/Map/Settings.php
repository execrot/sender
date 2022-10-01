<?php

declare(strict_types=1);

namespace App\Module\Api\Map;

class Settings
{
  /**
   * @return string[]
   */
  public static function item(): array
  {
    return [
      'id',
      'type',
      'active',
      'data'
    ];
  }
}