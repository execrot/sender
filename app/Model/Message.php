<?php

declare(strict_types=1);

namespace App\Model;

use Light\Model\Model;

/**
 * @collection Message
 *
 * @property string $id
 *
 * @property integer $when
 * @property string $status
 *
 * @property array $recipients
 * @property array $content
 *
 * @property \App\Model\Template $template
 * @property array $args
 *
 * @property \App\Model\Settings $settings
 * @property \App\Model\User $user
 *
 * @property string $log
 */
class Message extends Model
{
  const STATUS_NEW = 'new';
  const STATUS_SENT = 'sent';
  const STATUS_FAILED = 'failed';
}