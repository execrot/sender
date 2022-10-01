<?php

namespace App\Module\Api\Scheme;

use Exception;
use Light\Scheme\Boolean;
use Light\Scheme\Map;
use Light\Scheme\Number;
use Light\Scheme\Text;
use Light\Utilites\Filter\Lowercase;
use Light\Utilites\Filter\Trim;
use Light\Utilites\Validator\Enum;

class Settings
{
  /**
   * @param array|null $settings
   * @return Map
   * @throws Exception
   */
  static public function scheme(?array $settings): Map
  {
    $typeScheme = self::type();

    return new Map([
      'type' => $typeScheme,
      'active' => new Boolean(['nullable' => true]),
      'data' => self::data(
        $typeScheme->normalize(
          $settings['type'] ?? null))
    ]);
  }

  /**
   * @param array|null $options
   * @return Text
   */
  static function type(?array $options = []): Text
  {
    return new Text(array_merge_recursive($options, [
      'filters' => [
        new Lowercase(),
        new Trim()
      ],
      'validators' => [
        new Enum([
          'expected' => [
            \App\Model\Settings::TYPE_SMTP,
            \App\Model\Settings::TYPE_SMSC,
          ]
        ])
      ]
    ]));
  }

  /**
   * @param string $type
   * @return Map
   * @throws Exception
   */
  static function data(string $type): Map
  {
    if ($type == \App\Model\Settings::TYPE_SMTP) {
      return self::smtp();

    } else if ($type == \App\Model\Settings::TYPE_SMSC) {
      return self::smsc();
    }

    throw new Exception("Unknown type: " . $type);
  }

  /**
   * @return Map
   */
  static function smtp(): Map
  {
    return new Map([
      'protocol' => new Text([
        'filters' => [
          new Lowercase(),
          new Trim(),
        ],
        'validators' => [
          new Enum([
            'expected' => [
              \App\Model\Settings::SMTP_PROTOCOL_TLS,
              \App\Model\Settings::SMTP_PROTOCOL_SSL,
            ]
          ])
        ]
      ]),
      'server' => new Text([
        'filters' => [
          new Lowercase(),
          new Trim(),
        ],
      ]),
      'port' => new Number(),
      'username' => new Text([
        'filters' => [
          new Lowercase(),
          new Trim(),
        ],
      ]),
      'password' => new Text(),
      'fromName' => new Text(),
      'fromAddress' => new Text()
    ]);
  }

  /**
   * @return Map
   * @throws Exception
   */
  static function smsc(): Map
  {
    throw new Exception('SettingsScheme::smsc not implemented');
  }
}