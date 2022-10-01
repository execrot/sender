<?php

namespace App\Module\Api;

use ReflectionParameter;
use Light\Model\Model;
use Light\Core\BootstrapAbstract;
use Light\Core\Exception\InjectorParamRequired;
use Light\Core\Front;
use App\Model\Message;
use App\Model\Settings;
use App\Model\Template;
use App\Module\Api\Service\User;

class Bootstrap extends BootstrapAbstract
{
  /**
   * @return void
   */
  public function injector(): void
  {
    $modelClassNames = [
      Settings::class,
      Message::class,
      Template::class
    ];

    foreach ($modelClassNames as $class) {
      Front::getInstance()->registerInjector(
        $class,
        function (ReflectionParameter $parameter, ?string $value = null): ?Model {
          try {
            /** @var Model $modelClassName */
            $modelClassName = $parameter->getType()->getName();
            $model = $modelClassName::one([
              'id' => $value,
              'user' => User::info()
            ]);

          } catch (\Throwable $e) {
            $model = null;
          }
          if (!$model && !$parameter->allowsNull()) {
            throw new InjectorParamRequired($parameter->getName());
          }
          return $model;
        });
    }
  }
}