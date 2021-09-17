<?php

namespace Civi\Api4\Action\DialfireTenant;

use Civi\Api4\DialfireCampaign;
use Civi\Api4\Generic\BasicBatchAction;
use Civi\Dialfire\Api\Client;
use Civi\Dialfire\Api\Tenant;

class Synchronize extends BasicBatchAction {

  /**
   * Criteria for selecting $ENTITIES to process.
   *
   * @var array
   */
  protected $where = [];

  public function __construct($entityName, $actionName) {
    parent::__construct($entityName, $actionName, ['id', 'dialfire_tenant_identifier', 'name', 'token']);
  }

  /**
   * @param array $item
   * @return array
   */
  protected function doTask($item) {
    $client = new Client($item['token']);
    $tenant = new Tenant($client);
    $campaigns = $tenant->getCampaigns($item['dialfire_tenant_identifier']);
    foreach ($campaigns as $campaign) {
      // only campaigns with the campaignAPI permission are accessible via API
      if (in_array('campaignAPI', $campaign['permissions']['roles'])) {
        \CRM_Dialfire_BAO_DialfireCampaign::syncApiEntity($item['id'], $campaign);
      }
      else {
        \Civi::log('dialfire')->warning("Ignoring dialfire campaign {$campaign['id']} due to missing campaignAPI permission");
      }
    }
    $campaignSync = DialfireCampaign::synchronize(FALSE)
      ->addWhere('dialfire_tenant_id', '=', $item['id'])
      ->execute();
    return [
      'id' => $item['id'],
      'campaigns' => $campaignSync,
      'synchronized' => TRUE,
    ];
  }

}
