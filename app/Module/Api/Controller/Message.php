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

class Message extends Controller
{
  /**
   * @param \App\Model\Settings $settings
   * @param \App\Model\Template|null $template
   *
   * @return void
   * @throws CallUndefinedMethod
   * @throws ConfigWasNotProvided
   * @throws DriverClassDoesNotExists
   * @throws DriverClassDoesNotExtendsFromDriverAbstract
   * @throws Exception
   */
  public function add(\App\Model\Settings $settings, \App\Model\Template $template = null): void
  {
    $scheme = \App\Module\Api\Scheme\Message::scheme(
      $settings,
      $template);

    if (!$scheme->isValid($this->getParams())) {
      throw new Exception(
        'Scheme not valid',
        $scheme->getErrors());
    }

    $message = new \App\Model\Message();

    $message->populateWithoutQuerying(
      $scheme->normalize(
        $this->getParams()));

    $message->user = User::info();
    $message->save();
  }

  /**
   * @param \App\Model\Settings|null $settings
   * @param string|null $status
   * @param int $count
   * @param int $offset
   *
   * @return array
   * @throws Exception
   */
  public function index(
    \App\Model\Settings $settings = null,
    string              $status = null,
    int                 $count = 10,
    int                 $offset = 0
  ): array
  {
    $cond = ['user' => User::info()];

    if ($settings) {
      $cond['settings'] = $settings;
    }

    if ($status) {
      $statusScheme = \App\Module\Api\Scheme\Message::status();
      if (!$statusScheme->isValid($status)) {
        throw new Exception(
          'Unknown status',
          $statusScheme->getErrors());
      }
      $cond['status'] = $statusScheme->normalize($status);
    }

    return [
      'count' => \App\Model\Message::count($cond),
      'items' => Map::execute(
        \App\Model\Message::all($cond, [], $count, $offset),
        \App\Module\Api\Map\Message::item())];
  }

  /**
   * @param \App\Model\Message $message
   * @return array
   */
  public function item(\App\Model\Message $message): array
  {
    return Map::execute(
      $message,
      \App\Module\Api\Map\Message::item());
  }

  /**
   * @param \App\Model\Message $message
   * @param \App\Model\Settings $settings
   * @param \App\Model\Template|null $template
   *
   * @return void
   * @throws CallUndefinedMethod
   * @throws ConfigWasNotProvided
   * @throws DriverClassDoesNotExists
   * @throws DriverClassDoesNotExtendsFromDriverAbstract
   * @throws Exception
   */
  public function edit(
    \App\Model\Message  $message,
    \App\Model\Settings $settings,
    \App\Model\Template $template = null
  ): void
  {
    $scheme = \App\Module\Api\Scheme\Message::scheme(
      $settings,
      $template);

    if (!$scheme->isValid($this->getParams())) {
      throw new Exception(
        'Scheme not valid',
        $scheme->getErrors());
    }

    $message->populateWithoutQuerying(
      $scheme->normalize(
        $this->getParams()));

    $message->save();
  }

  /**
   * @param \App\Model\Message $message
   * @return void
   */
  public function delete(\App\Model\Message $message): void
  {
    $message->remove();
  }
}