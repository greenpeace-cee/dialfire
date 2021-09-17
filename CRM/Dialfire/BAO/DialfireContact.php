<?php
use CRM_Dialfire_ExtensionUtil as E;

class CRM_Dialfire_BAO_DialfireContact extends CRM_Dialfire_DAO_DialfireContact {

  public static function getContactPayload(int $dialfireContactId, array $query, array $fieldMap, array $customData) {
    $query['where'] = array_merge(
      $query['where'] ?? [],
      [['id', '=', $dialfireContactId]]
    );
    $query['checkPermissions'] = FALSE;
    $result = civicrm_api4('DialfireContact', 'get', $query, 0);
    $payload = [
      '$ref' => 'CIVI_DF_' . $dialfireContactId,
    ];
    foreach ($fieldMap as $field => $expression) {
      $payload[$field] = JmesPath\Env::search($expression, $result);
    }
    $payload = array_merge($payload, $customData);
    return $payload;
  }

}
