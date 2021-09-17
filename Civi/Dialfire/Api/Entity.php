<?php

namespace Civi\Dialfire\Api;

class Entity {

  /**
   * @var \Civi\Dialfire\Api\Client
   */
  protected $client;

  public function __construct(Client $client) {
    $this->client = $client;
  }

}
