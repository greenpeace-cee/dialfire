<?php

use Civi\Api4\DialfireTask;
use CRM_Dialfire_ExtensionUtil as E;

class CRM_Dialfire_BAO_DialfireTask extends CRM_Dialfire_DAO_DialfireTask {

  public static function syncApiEntity(int $campaignId, array $entity): void {
    $task = DialfireTask::get(FALSE)
      ->addSelect('id')
      ->addWhere('dialfire_campaign_id', '=', $campaignId)
      ->addWhere('dialfire_task_identifier', '=', $entity['data']['id'])
      ->execute()
      ->first();
    $record = [[
      'id' => $task['id'] ?? NULL,
      'dialfire_campaign_id' => $campaignId,
      'dialfire_task_identifier' => $entity['data']['id'],
      'name' => $entity['data']['name'],
    ]];
    DialfireTask::save(FALSE)
      ->setRecords($record)
      ->execute();
  }

}
