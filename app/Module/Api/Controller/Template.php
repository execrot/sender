<?php

declare(strict_types=1);

namespace App\Module\Api\Controller;

use MongoDB\BSON\Regex;
use Light\Core\Controller;
use Light\Map\Map;
use Light\Model\Exception\CallUndefinedMethod;
use Light\Model\Exception\ConfigWasNotProvided;
use Light\Model\Exception\DriverClassDoesNotExists;
use Light\Model\Exception\DriverClassDoesNotExtendsFromDriverAbstract;
use App\Module\Api\Exception;
use App\Module\Api\Service\User;

class Template extends Controller
{
  /**
   * @return void
   * @throws CallUndefinedMethod
   * @throws ConfigWasNotProvided
   * @throws DriverClassDoesNotExists
   * @throws DriverClassDoesNotExtendsFromDriverAbstract
   * @throws Exception
   */
  public function add(): void
  {
    $scheme = \App\Module\Api\Scheme\Template::scheme(
      User::info());

    if (!$scheme->isValid($this->getParams())) {
      throw new Exception(
        'Scheme not valid',
        $scheme->getErrors());
    }

    $template = new \App\Model\Template();

    $template->populateWithoutQuerying(
      $scheme->normalize(
        $this->getParams()));

    $template->user = User::info();
    $template->save();
  }

  /**
   * @param string|null $search
   * @param int $count
   * @param int $offset
   * @return array
   */
  public function index(
    string $search = null,
    int    $count = 10,
    int    $offset = 0
  ): array
  {
    $cond = ['user' => User::info()];

    if ($search) {
      $cond['$or'] = [
        ['slug' => new Regex($search, 'i')],
        ['content' => new Regex($search, 'i')],
      ];
    }

    return [
      'count' => \App\Model\Template::count($cond),
      'items' => Map::execute(
        \App\Model\Template::all($cond, [], $count, $offset),
        \App\Module\Api\Map\Template::item())];
  }

  /**
   * @param \App\Model\Template $template
   * @return array
   */
  public function item(\App\Model\Template $template): array
  {
    return Map::execute(
      $template,
      \App\Module\Api\Map\Template::item());
  }

  /**
   * @param \App\Model\Template $template
   * @return void
   * @throws CallUndefinedMethod
   * @throws ConfigWasNotProvided
   * @throws DriverClassDoesNotExists
   * @throws DriverClassDoesNotExtendsFromDriverAbstract
   * @throws Exception
   */
  public function edit(\App\Model\Template $template): void
  {
    $scheme = \App\Module\Api\Scheme\Template::scheme(
      User::info(),
      $template);

    if (!$scheme->isValid($this->getParams())) {
      throw new Exception(
        'Scheme not valid',
        $scheme->getErrors());
    }

    $template->populateWithoutQuerying(
      $scheme->normalize(
        $this->getParams()));

    $template->save();
  }

  /**
   * @param \App\Model\Template $template
   * @return void
   */
  public function delete(\App\Model\Template $template): void
  {
    $template->remove();
  }
}