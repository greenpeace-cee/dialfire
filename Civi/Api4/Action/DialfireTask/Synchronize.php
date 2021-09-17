<?php

namespace Civi\Api4\Action\DialfireTask;

use Civi\Api4\DialfireContact;
use Civi\Api4\Generic\BasicBatchAction;
use Civi\Dialfire\Api\Contact;

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
    $contactSync = DialfireContact::synchronize(FALSE)
      ->addWhere('dialfire_task_id', '=', $item['id'])
      ->addWhere('synchronization_status_id:name', '=', 'pending')
      ->execute();
    return [
      'id' => $item['id'],
      'contacts' => $contactSync,
      'synchronized' => TRUE,
    ];
  }

}
