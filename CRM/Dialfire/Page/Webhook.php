<?php

use Civi\Api4\DialfireResponse;

class CRM_Dialfire_Page_Webhook extends CRM_Core_Page {

  public function run() {
    try {
      $request = file_get_contents('php://input');
      \Civi::log('dialfire')->debug('Dialfire Webhook Received', [$request]);
      $decodedRequest = json_decode($request, TRUE);
      if (is_null($decodedRequest)) {
        throw new Exception('Invalid JSON payload in webhook: ' . json_last_error_msg());
      }
      $response = DialfireResponse::create(FALSE)
        ->addValue('response', $decodedRequest)
        ->execute()
        ->first();
      echo '[success dialfire-response-id=' . $response['id'] . ']';
      CRM_Utils_System::civiExit();
    } catch (Exception $e) {
      echo '[error: ' . $e->getMessage() . ']';
      \Civi::log('dialfire')->debug(
        'Dialfire Webhook Error: ' . $e->getMessage(),
        ['exception' => $e, 'request' => $_POST]
      );
    }
  }

}
