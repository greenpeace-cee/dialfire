<?php

namespace Civi\Api4\Action\DialfireContact;

use Civi\Api4\Generic\BasicBatchAction;
use Civi\Dialfire\Api\Client;
use Civi\Dialfire\Api\Campaign;

class Synchronize extends BasicBatchAction {

  /**
   * Criteria for selecting $ENTITIES to process.
   *
   * @var array
   */
  protected $where = [];

  public function __construct($entityName, $actionName) {
    parent::__construct($entityName, $actionName, ['id', 'dialfire_task_identifier', 'name', 'token']);
  }

  /**
   * @param array $item
   * @return array
   */
  protected function doTask($item) {
    $client = new Client($item['token']);
    $task = new Task($client);
    $tasks = $campaign->getTasks($item['dialfire_campaign_identifier']);
    foreach ($tasks as $task) {
      \CRM_Dialfire_BAO_DialfireTask::syncApiEntity($item['id'], $task);
    }
    return [
      'id' => $item['id'],
      'synchronized' => TRUE,
    ];
  }

}
