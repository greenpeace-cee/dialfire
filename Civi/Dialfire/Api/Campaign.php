<?php

namespace Civi\Dialfire\Api;

class Campaign extends Entity {

  public function getTasks($campaignId) {
   return $this->client->get('campaigns/' . $campaignId . '/tasks/');
  }
}
