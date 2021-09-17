<?php

namespace Civi\Dialfire\Api;

class Tenant extends Entity {

  public function getCampaigns($tenantId) {
    return $this->client->get('tenants/' . $tenantId . '/campaigns/');
  }
}
