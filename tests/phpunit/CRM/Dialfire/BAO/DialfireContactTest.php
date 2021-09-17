<?php

use Civi\Api4\Address;
use Civi\Api4\Contact;
use Civi\Api4\DialfireCampaign;
use Civi\Api4\DialfireContact;
use Civi\Api4\DialfireTask;
use Civi\Api4\DialfireTenant;
use Civi\Api4\Email;
use Civi\Api4\Phone;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 *
 * @group headless
 */
class CRM_Dialfire_BAO_DialfireContactTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {

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

  public function testGetContactPayload() {
    $contact = Contact::create(FALSE)
      ->addValue('contact_type', 'Individual')
      ->addValue('first_name', 'Jane')
      ->addValue('last_name', 'Doe')
      ->addChain('phone', Phone::create()
        ->addValue('contact_id', '$id')
        ->addValue('phone', '436801234567')
      )
      ->addChain('email', Email::create()
        ->addValue('contact_id', '$id')
        ->addValue('email', 'jdoe@example.com')
      )
      ->addChain('address', Address::create()
        ->addValue('contact_id', '$id')
        ->addValue('street_address', 'Foostreet')
      )
      ->execute()
      ->first();

    $tenant = DialfireTenant::create(FALSE)
      ->addValue('dialfire_tenant_identifier', 'test')
      ->addValue('name', 'test')
      ->addValue('token', 'test')
      ->addChain('dialfirecampaign', DialfireCampaign::create(FALSE)
        ->addValue('dialfire_tenant_id', '$id')
        ->addValue('dialfire_campaign_identifier', 'test')
        ->addValue('name', 'test')
        ->addValue('token', 'test')
        ->addChain('dialfiretask', DialfireTask::create(FALSE)
          ->addValue('dialfire_campaign_id', '$id')
          ->addValue('dialfire_task_identifier', 'test')
          ->addChain('dialfirecontact', DialfireContact::create(FALSE)
            ->addValue('dialfire_task_id', '$id')
            ->addValue('contact_id', $contact['id'])
          )
        )
      )
      ->execute()
      ->first();
    $dialfireContactId = $tenant['dialfirecampaign'][0]['dialfiretask'][0]['dialfirecontact'][0]['id'];
    $payload = \CRM_Dialfire_BAO_DialfireContact::getContactPayload(
      $dialfireContactId,
      \Civi::settings()->get('dialfire_default_contact_query'),
      \Civi::settings()->get('dialfire_default_field_map'),
      ['foo' => 'bar']
    );
    $this->assertEquals('CIVI_DF_' . $dialfireContactId, $payload['$ref']);
    $this->assertEquals('Jane', $payload['first_name']);
    $this->assertEquals('jdoe@example.com', $payload['email1']);
    $this->assertEquals(NULL, $payload['email2']);
    $this->assertEquals('436801234567', $payload['$phone']);
    $this->assertEquals('bar', $payload['foo']);
  }

}
