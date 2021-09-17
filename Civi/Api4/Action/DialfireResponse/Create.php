<?php

/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */


namespace Civi\Api4\Action\DialfireResponse;

use Civi\Api4\DialfireContact;
use Civi\Api4\DialfireTask;
use Civi\Api4\Generic\Result;

/**
 * @inheritDoc
 */
class Create extends \Civi\Api4\Generic\DAOCreateAction {

  /**
   * @inheritDoc
   */
  public function _run(Result $result) {
    $response = $this->getValue('response');
    if (!empty($response) && is_array($response)) {
      $responseMap = [
        'dialfire_contact_identifier' => '$id',
        'dialfire_record_version' => '$version',
        'dialfire_entry_date' => '$entry_date',
      ];
      foreach ($responseMap as $apiField => $responseField) {
        if (empty($this->getValue($apiField)) && !empty($response[$responseField])) {
          $this->addValue($apiField, $response[$responseField]);
        }
      }
      if (empty($this->getValue('dialfire_task_id'))) {
        $dialfireTask = DialfireTask::get(FALSE)
          ->addSelect('id')
          ->addWhere('dialfire_task_identifier', '=', $response['$task_id'])
          ->execute()
          ->first();
        if (empty($dialfireTask['id'])) {
          throw new \API_Exception('Unknown DialfireTask with identifier "' . $response['$task_id'] . '"');
        }
        $this->addValue('dialfire_task_id', $dialfireTask['id']);
      }
      if (empty($this->getValue('dialfire_contact_id'))) {
        $dialfireContact = DialfireContact::get(FALSE)
          ->addSelect('id')
          ->addWhere('dialfire_contact_identifier', '=', $response['$id'])
          ->execute()
          ->first();
        if (!empty($dialfireContact['id'])) {
          $this->addValue('dialfire_contact_id', $dialfireContact['id']);
        }
      }
    }
    parent::_run($result);
  }
}
