<?php

declare(strict_types=1);

namespace App\Model;

use Light\Model\Model;

/**
 * @collection Settings
 *
 * @property string $id
 *
 * @property string $type
 * @property array $data
 * @property boolean $active
 *
 * @property \App\Model\User $user
 */
class Settings extends Model
{
  const TYPE_SMTP = 'smtp';
  const TYPE_SMSC = 'smsc';

  const SMTP_PROTOCOL_TLS = 'tls';
  const SMTP_PROTOCOL_SSL = 'ssl';
}