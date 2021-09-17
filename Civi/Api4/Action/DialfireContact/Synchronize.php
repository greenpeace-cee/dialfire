<?php

namespace Civi\Api4\Action\DialfireContact;

use Civi\Api4\DialfireContact;
use Civi\Api4\Generic\BasicBatchAction;
use Civi\Dialfire\Api\Client;
use Civi\Dialfire\Api\ConflictException;
use Civi\Dialfire\Api\Contact;

class Synchronize extends BasicBatchAction {

  /**
   * Criteria for selecting $ENTITIES to process.
   *
   * @var array
   */
  protected $where = [];

  public function __construct($entityName, $actionName) {
    parent::__construct($entityName, $actionName, [
      'id',
      'custom_data',
      'error_count',
      'synchronization_status_id:name',
      'dialfire_contact_identifier'
    ]);
  }

  /**
   * @param array $item
   * @return array
   */
  protected function doTask($item) {
    $synchronized = FALSE;
    $result = NULL;
    if ($item['error_count'] >= \Civi::settings()->get('dialfire_max_retries')) {
      DialfireContact::update(FALSE)
        ->addWhere('id', '=', $item['id'])
        ->addValue('synchronization_status_id:name', 'failed')
        ->execute();
      $result = 'Too many retry attempts, marking as failed';
      \Civi::log('dialfire')->error("Too many retry attempts for DialfireContact {$item['id']}, flagging as failed.");
    }
    elseif ($item['synchronization_status_id:name'] == 'pending') {
      try {
        $syncResult = $this->synchronize($item);
        DialfireContact::update(FALSE)
          ->addWhere('id', '=', $item['id'])
          ->addValue('synchronization_status_id:name', 'completed')
          ->addValue('dialfire_contact_identifier', $syncResult['api']['data']['$id'])
          ->addValue('dialfire_record_version', $syncResult['api']['data']['$version'])
          ->addValue('last_payload', $syncResult['payload'])
          ->addValue('error_count', 0)
          ->addValue('synchronization_date', 'now')
          ->execute();

        $synchronized = TRUE;
        $result = [
          'dialfire_contact_identifier' => $syncResult['api']['data']['$id'],
          'dialfire_record_version' => $syncResult['api']['data']['$version'],
        ];
      } catch (\Exception $e) {
        \Civi::log('dialfire')->error("Error while synchronizing DialfireContact {$item['id']}: " . $e->getMessage());
        DialfireContact::update(FALSE)
          ->addWhere('id', '=', $item['id'])
          ->addValue('synchronization_status_id:name', 'pending')
          ->addValue('error_count', $item['error_count'] + 1)
          ->addValue('last_error', [
            'exception' => (array) $e,
            'item' => $item,
          ])
          ->execute();
      }
    }
    else {
      $result = 'Cannot process DialfireContact with synchronization_status_id != "pending"';
    }

    return [
      'id' => $item['id'],
      'result' => $result,
      'synchronized' => $synchronized,
    ];
  }

  protected function synchronize(array $item) {
    $dialfireContact = DialfireContact::get(FALSE)
      ->addSelect('*', 'dialfire_task.name', 'dialfire_campaign.token', 'dialfire_campaign.contact_query', 'dialfire_campaign.field_map', 'dialfire_campaign.dialfire_campaign_identifier')
      ->setJoin([
        ['DialfireTask AS dialfire_task', 'INNER', NULL, ['dialfire_task.id', '=', 'dialfire_task_id']],
        ['DialfireCampaign AS dialfire_campaign', 'INNER', NULL, ['dialfire_campaign.id', '=', 'dialfire_task.dialfire_campaign_id']],
      ])
      ->addWhere('id', '=', $item['id'])
      ->execute()
      ->first();

    DialfireContact::update(FALSE)
      ->addWhere('id', '=', $item['id'])
      ->addValue('synchronization_status_id:name', 'in_progress')
      ->execute();

    $client = new Client($dialfireContact['dialfire_campaign.token']);
    $contact = new Contact($client);
    $payload = \CRM_Dialfire_BAO_DialfireContact::getContactPayload(
      $item['id'],
      $dialfireContact['dialfire_campaign.contact_query'] ?? \Civi::settings()->get('dialfire_default_contact_query'),
      $dialfireContact['dialfire_campaign.field_map'] ?? \Civi::settings()->get('dialfire_default_field_map'),
      $item['custom_data'] ?? []
    );

    $syncResult = ['payload' => $payload];

    $isUpdate = TRUE;
    if (empty($item['dialfire_contact_identifier'])) {
      try {
        $syncResult['api'] = $contact->create(
          $dialfireContact['dialfire_campaign.dialfire_campaign_identifier'],
          $dialfireContact['dialfire_task.name'],
          $payload
        );
        $isUpdate = FALSE;
      }
      catch (ConflictException $e) {
        \Civi::log('dialfire')->warning("Detected conflict while creating contact with \$ref {$payload['$ref']}. Falling back to update.");
      }
    }

    if ($isUpdate) {
      $contact->update(
        $dialfireContact['dialfire_campaign.dialfire_campaign_identifier'],
        $payload['$ref'],
        $payload
      );
      // update does not return the updated contact; fetch in a separate request
      $syncResult['api']['data'] = $contact->get(
        $dialfireContact['dialfire_campaign.dialfire_campaign_identifier'],
        $payload['$ref']
      );
    }

    return $syncResult;
  }

}
