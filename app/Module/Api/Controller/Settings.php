<?php

declare(strict_types=1);

namespace App\Module\Api\Controller;

use Light\Core\Controller;
use Light\Map\Map;
use Light\Model\Exception\CallUndefinedMethod;
use Light\Model\Exception\ConfigWasNotProvided;
use Light\Model\Exception\DriverClassDoesNotExists;
use Light\Model\Exception\DriverClassDoesNotExtendsFromDriverAbstract;
use App\Module\Api\Exception;
use App\Module\Api\Service\User;

class Settings extends Controller
{
  /**
   * @return void
   * @throws \Exception
   * @throws CallUndefinedMethod
   * @throws ConfigWasNotProvided
   * @throws DriverClassDoesNotExists
   * @throws DriverClassDoesNotExtendsFromDriverAbstract
   */
  public function add(): void
  {
    $scheme = \App\Module\Api\Scheme\Settings::scheme(
      $this->getParams());

    if (!$scheme->isValid($this->getParams())) {
      throw new Exception(
        'Scheme not valid',
        $scheme->getErrors());
    }

    $settings = new \App\Model\Settings();

    $settings->populateWithoutQuerying(
      $scheme->normalize(
        $this->getParams()));

    $settings->user = User::info();
    $settings->save();
  }

  /**
   * @param string|null $type
   * @param int|null $count
   * @param int|null $offset
   *
   * @return array
   * @throws Exception
   */
  public function index(?string $type, ?int $count = 10, ?int $offset = 0): array
  {
    $cond = ['user' => User::info()];

    if ($type) {
      $typeScheme = \App\Module\Api\Scheme\Settings::type([
        'nullable' => true
      ]);
      if (!$typeScheme->isValid($type)) {
        throw new Exception(
          'Unknown type',
          $typeScheme->getErrors());
      }
      $cond['type'] = $typeScheme->normalize($type);
    }

    return [
      'count' => \App\Model\Settings::count($cond),
      'items' => Map::execute(
        \App\Model\Settings::all($cond, [], $count, $offset),
        \App\Module\Api\Map\Settings::item())];
  }

  /**
   * @param \App\Model\Settings $settings
   * @return array
   */
  public function item(\App\Model\Settings $settings): array
  {
    return Map::execute(
      $settings,
      \App\Module\Api\Map\Settings::item());
  }

  /**
   * @param \App\Model\Settings $settings
   *
   * @return void
   * @throws CallUndefinedMethod
   * @throws ConfigWasNotProvided
   * @throws DriverClassDoesNotExists
   * @throws DriverClassDoesNotExtendsFromDriverAbstract
   * @throws Exception
   * @throws \Exception
   */
  public function edit(\App\Model\Settings $settings): void
  {
    $scheme = \App\Module\Api\Scheme\Settings::scheme(
      $this->getParams());

    if (!$scheme->isValid($this->getParams())) {
      throw new Exception(
        'Scheme not valid',
        $scheme->getErrors());
    }

    $settings->populateWithoutQuerying(
      $scheme->normalize(
        $this->getParams()));

    $settings->save();
  }

  /**
   * @param \App\Model\Settings $settings
   * @return void
   */
  public function delete(\App\Model\Settings $settings): void
  {
    $settings->remove();
  }
}