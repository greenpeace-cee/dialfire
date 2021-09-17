<?php

namespace Civi\Dialfire\Api;

use GuzzleHttp\Psr7\Response;

/**
 *
 * @group headless
 */
class ClientTest extends BaseTest {

  public function testAuthentication() {
    $responses = [
      new Response(403, [], '403 - Forbidden'),
    ];
    $tenant = new Tenant($this->getClient($responses));
    $this->expectException(AuthenticationException::class);
    $tenant->getCampaigns('foobar');
  }

  public function testConflict() {
    $responses = [
      new Response(409, [], '409 - Conflict'),
    ];
    $contact = new Contact($this->getClient($responses));
    $this->expectException(ConflictException::class);
    $contact->update('foobar', '123', []);
  }

  public function testInvalidResponse() {
    $responses = [
      new Response(200, [], 'invalid json'),
    ];
    $tenant = new Tenant($this->getClient($responses));
    $this->expectException(BadResponseException::class);
    $tenant->getCampaigns('foobar');
  }

  public function testUnhandledStatusCode() {
    $responses = [
      new Response(404, [], '404 - Not Found'),
    ];
    $tenant = new Tenant($this->getClient($responses));
    $this->expectException(Exception::class);
    $tenant->getCampaigns('foobar');
  }

}
