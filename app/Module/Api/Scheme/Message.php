<?php

namespace App\Module\Api\Scheme;

use Light\Scheme\Any;
use Light\Scheme\Collection;
use Light\Scheme\Map;
use Light\Scheme\Number;
use Light\Scheme\Text;
use Light\Utilites\Filter\Lowercase;
use Light\Utilites\Filter\Trim;
use Light\Utilites\Validator\Email;
use Light\Utilites\Validator\Enum;
use App\Module\Api\Exception;

class Message
{
  /**
   * @param \App\Model\Settings $settings
   * @param \App\Model\Template|null $template
   *
   * @return Map
   * @throws Exception
   */
  static public function scheme(
    \App\Model\Settings $settings,
    \App\Model\Template $template = null
  ): Map
  {
    if ($settings->type == \App\Model\Settings::TYPE_SMTP) {
      $recipients = self::smtpRecipients();
      $content = !$template ? self::smtpContent() : new Any();

    } else {
      throw new Exception('!SMTP not implemented');
    }

    return new Map([
      'when' => new Number(),
      'status' => self::status(),
      'settings' => new Text([
        'filters' => [function () use ($settings): string {
          return $settings->id;
        }]
      ]),
      'content' => $content,
      'recipients' => $recipients,
      'template' => new Text([
        'filters' => [function () use ($template): string|null {
          return $template?->id;
        }]
      ]),
      'args' => new Any()
    ]);
  }

  /**
   * @return Text
   */
  static public function status(): Text
  {
    return new Text([
      'filters' => [
        new Trim(),
        new Lowercase()
      ],
      'validators' => [
        new Enum([
          'expected' => [
            \App\Model\Message::STATUS_NEW,
            \App\Model\Message::STATUS_SENT,
            \App\Model\Message::STATUS_FAILED,
          ]
        ])
      ]
    ]);
  }

  /**
   * @return Map
   */
  static public function smtpContent(): Map
  {
    return new Map([
      'subject' => new Text(),
      'body' => new Text(),
      'attachments' => new Collection(new Map([
        'name' => new Text([
          'nullable' => true
        ]),
        'content' => new Text([
          'nullable' => true
        ])
      ]))
    ]);
  }

  /**
   * @return Collection
   */
  static public function smtpRecipients(): Collection
  {
    return new Collection(
      new Map([
        'name' => new Text([
          'filters' => [
            new Trim(),
            new Lowercase()
          ]
        ]),
        'address' => new Text([
          'validators' => [
            new Email()
          ],
          'filters' => [
            new Trim(),
            new Lowercase()
          ]
        ])
      ])
    );
  }
}