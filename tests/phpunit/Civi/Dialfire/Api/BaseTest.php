<?php

namespace Civi\Dialfire\Api;

use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;
use GuzzleHttp\Handler\MockHandler;

/**
 *
 * @group headless
 */
abstract class BaseTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {

  public function setUpHeadless() {
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  public function setUp() {
    parent::setUp();
  }

  public function tearDown() {
    parent::tearDown();
  }

  protected function getClient(array $mockResponses) {
    $mock = new MockHandler($mockResponses);
    return new Client('secret', 'https://api.dialfire.test/api/', $mock);
  }

}
