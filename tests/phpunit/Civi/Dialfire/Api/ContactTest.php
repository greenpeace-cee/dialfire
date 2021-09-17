<?php

namespace Civi\Dialfire\Api;

use GuzzleHttp\Psr7\Response;

/**
 *
 * @group headless
 */
class ContactTest extends BaseTest {

  public function testGet() {
    $responses = [
      new Response(200, [], '{"$id":"foo12345","$ref":"CIVI_DF_1","$version":"bar12345","$campaign_id":"foo12345","$task_id":"foo12345","$task":"new_contact","$status":"open","$status_detail":"$none","$phone":"+1234567890","$timezone":"Europe/Vienna","$caller_id":"","$created_date":"2021-09-02T15:34:39.960Z","$entry_date":"2021-09-02T15:34:39.960Z","$follow_up_date":"","$call_order":"","$source":"","$comment":"","$error":"","$trigger":"other","first_name":"Jane","last_name":"Doe","email1":"jdoe@example.com","email2":"j.d@example.com","phone2":"4315543213","$owner":"","ansprache":"","contact_id":"","activity_id":"","$$anrufen_task":"new_contact","$$anrufen_date":"2021-09-02T15:48:27.644Z","$$anrufen_status":"open","$$anrufen_status_detail":"$none","$recording_url":"","$recording":"","$task_log":[{"transactions":[{"type":"update","task_id":"foo12345","task":"anrufen_stufe","status":"open","status_detail":"$none","fired":"2021-09-02T15:34:39.960Z","trigger":"other","id":"foo12345","ref":"CIVI_DF_1","phone":"+1234567890","timezone":"Europe/Vienna","created_date":"2021-09-02T15:34:39.960Z","data":{"first_name":"Jane","last_name":"Doe","email1":"jdoe@example.com","email2":"j.d@example.com","phone2":"4315543213"},"sequence_nr":0,"version":"RDX7L9CT4HCQZDFQ","entry_date":"2021-09-02T15:34:39.960Z"}],"status":"open","status_detail":"$none","fired":"2021-09-02T15:48:27.644Z","campaign_id":"bar12345","task_id":"foo12345","task_name":"new_contact","task_title":"Anruf","task_type":"OutboundTask","task_hi":true}]}'),
    ];
    $contactClient = new Contact($this->getClient($responses));
    $contact = $contactClient->get('foobar', '123');
    $this->assertEquals('foo12345', $contact['$id']);
  }

}
