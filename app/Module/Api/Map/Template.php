<?php

declare(strict_types=1);

namespace App\Module\Api\Map;

class Template
{
  /**
   * @return string[]
   */
  public static function item(): array
  {
    return [
      'id',
      'slug',
      'subject',
      'content'
    ];
  }
}