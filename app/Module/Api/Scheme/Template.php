<?php

declare(strict_types=1);

namespace App\Module\Api\Scheme;

use App\Model\User;
use Light\Scheme\Map;
use Light\Scheme\Text;
use Light\Utilites\Filter\Trim;
use MongoDB\BSON\ObjectId;

class Template
{
  /**
   * @param User $user
   * @param \App\Model\Template|null $template
   * @return Map
   */
  static public function scheme(User $user, ?\App\Model\Template $template = null): Map
  {
    return new Map([
      'slug' => new Text([
        'filter' => [
          new Trim()
        ],
        'validators' => [
          function (string $value) use ($user, $template): bool {
            $cond = ['user' => $user, 'slug' => $value];
            if ($template) {
              $cond['_id'] = ['$ne' => new ObjectId($template->id)];
            }
            return !\App\Model\Template::count($cond);
          }
        ]
      ]),
      'subject' => new Text(),
      'content' => new Text()
    ]);
  }
}