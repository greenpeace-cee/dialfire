<?php

namespace Civi\Dialfire\Api;

use GuzzleHttp\Psr7\Response;

/**
 *
 * @group headless
 */
class TenantTest extends BaseTest {

  public function testGetCampaigns() {
    $responses = [
      new Response(200, [], '[{"title":"TFR 1","owner":"fooowner","hidden":false,"status":"active","features":["camp_cust","camp_script"],"id":"campid12345","permissions":{"roles":["campaignAPI"],"token":"secret12345","actions":{}}},{"title":"TFR 2","owner":"fooowner","hidden":false,"status":"active","features":["camp_cust","camp_script"],"id":"campid23456","permissions":{"roles":[],"token":"secret23456","actions":{}}},{"title":"TFR 3","owner":"fooowner","hidden":false,"status":"active","features":["camp_cust","camp_script"],"id":"campid34567","permissions":{"roles":["campaignAPI"],"token":"secret34567","actions":{}}}]'),
    ];
    $tenant = new Tenant($this->getClient($responses));
    $campaigns = $tenant->getCampaigns('foobar');
    $this->assertEquals('TFR 1', $campaigns[0]['title']);
    $this->assertCount(3, $campaigns);
  }

}
