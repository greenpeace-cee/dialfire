<?php

namespace Civi\Dialfire\Api;

use GuzzleHttp\Psr7\Response;

/**
 *
 * @group headless
 */
class CampaignTest extends BaseTest {

  public function testGetTasks() {
    $responses = [
      new Response(200, [], '[{"data":{"created":"2021-07-01T16:39:07.481Z","position":1,"check_duplicate":true,"inbound":true,"script":"","overflow_assignments":{},"overflow_assignments_1":{},"overflow_assignments_2":{},"joined_tenants":{},"line":{},"outbound_line":{"id":"foo54321"},"dialergroup":"","workflowStateFilters":{"open":{"$assigned":false,"$revoked":false},"success":{"Success":true},"declined":{"Declined":true},"failed":{"$duplicate":false,"$wrong_number":false,"$locked_number":false,"$do_not_call":false,"$invalid_contact":false,"$unavailable":false,"$canceled":false,"$error":false,"$skipped":false,"Failed":true}},"type":"CreateTask","type_title":"create","title":"New Contact","name":"new_contact","id":"task12345","auto_record":{"action":""},"type_icon_class":"fa fa-plus-circle","_views_":[{"name__i":"new_contact"}]},"version":12345},{"data":{"created":"2021-07-01T16:39:07.481Z","position":1,"check_duplicate":true,"inbound":true,"script":"","overflow_assignments":{},"overflow_assignments_1":{},"overflow_assignments_2":{},"joined_tenants":{},"line":{},"outbound_line":{"id":"foo54321"},"dialergroup":"","workflowStateFilters":{"open":{"$assigned":false,"$revoked":false},"success":{"Success":true},"declined":{"Declined":true},"failed":{"$duplicate":false,"$wrong_number":false,"$locked_number":false,"$do_not_call":false,"$invalid_contact":false,"$unavailable":false,"$canceled":false,"$error":false,"$skipped":false,"Failed":true}},"type":"CreateTask","type_title":"create","title":"New Contact 2","name":"new_contact_2","id":"task23456","auto_record":{"action":""},"type_icon_class":"fa fa-plus-circle","_views_":[{"name__i":"new_contact_2"}]},"version":23456}]'),
    ];
    $campaign = new Campaign($this->getClient($responses));
    $tasks = $campaign->getTasks('foobar');
    $this->assertEquals('new_contact', $tasks[0]['data']['name']);
    $this->assertEquals('new_contact_2', $tasks[1]['data']['name']);
    $this->assertCount(2, $tasks);
  }

}
