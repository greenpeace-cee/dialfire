<?php

use Civi\Api4\DialfireCampaign;
use CRM_Dialfire_ExtensionUtil as E;

class CRM_Dialfire_BAO_DialfireCampaign extends CRM_Dialfire_DAO_DialfireCampaign {

  public static function syncApiEntity(int $tenantId, array $entity): void {
    $campaign = DialfireCampaign::get(FALSE)
      ->addSelect('id')
      ->addWhere('dialfire_tenant_id', '=', $tenantId)
      ->addWhere('dialfire_campaign_identifier', '=', $entity['id'])
      ->execute()
      ->first();
    $record = [[
      'id' => $campaign['id'] ?? NULL,
      'dialfire_tenant_id' => $tenantId,
      'dialfire_campaign_identifier' => $entity['id'],
      'name' => $entity['title'],
      'token' => $entity['permissions']['token']
    ]];
    DialfireCampaign::save(FALSE)
      ->setRecords($record)
      ->execute();
  }

}
