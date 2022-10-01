<?php

declare(strict_types=1);

namespace App\Model;

use Light\Model\Model;

/**
 * @collection Template
 *
 * @property string $id
 *
 * @property string $slug
 * @property string $subject
 * @property string $content
 *
 * @property \App\Model\User $user
 */
class Template extends Model
{
}