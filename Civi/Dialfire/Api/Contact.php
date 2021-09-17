<?php

namespace Civi\Dialfire\Api;

class Contact extends Entity {

  public function create(string $campaignId, string $taskName, array $payload) {
    $campaignId = urlencode($campaignId);
    $taskName = urlencode($taskName);
    return $this->client->postJSON("campaigns/{$campaignId}/tasks/{$taskName}/contacts/create", $payload);
  }

  public function update(string $campaignId, string $dialfireContactIdentifier, array $payload) {
    $campaignId = urlencode($campaignId);
    $dialfireContactIdentifier = urlencode($dialfireContactIdentifier);
    return $this->client->postJSON("campaigns/{$campaignId}/contacts/{$dialfireContactIdentifier}/update", $payload);
  }

  public function get(string $campaignId, string $dialfireContactIdentifier) {
    $campaignId = urlencode($campaignId);
    $dialfireContactIdentifier = urlencode($dialfireContactIdentifier);
    return $this->client->get("campaigns/{$campaignId}/contacts/{$dialfireContactIdentifier}/flat_view");
  }
}
