<?php

namespace App\Module\Api\Map;

use Light\Map\Map;

class Message
{
  /**
   * @return array
   */
  public static function item(): array
  {
    return [
      'id',
      'recipients',
      'content',
      'when',
      'status',
      'args',
      'template' => function (\App\Model\Message $message) {
        return Map::execute($message->template, [
          'id',
          'slug',
          'subject',
          'content'
        ]);
      },
      'settings' => function (\App\Model\Message $message) {
        return Map::execute($message->settings, [
          'id',
          'type',
          'data',
          'active',
        ]);
      }
    ];
  }
}