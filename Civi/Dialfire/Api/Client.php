<?php

namespace Civi\Dialfire\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;

class Client {
  const DEFAULT_ENDPOINT = 'https://api.dialfire.com/api/';

  static $userAgent = NULL;

  /**
   * @var \GuzzleHttp\Client
   */
  private $client = NULL;

  public function __construct($token, $endpoint = self::DEFAULT_ENDPOINT, $handler = NULL) {
    $stack = HandlerStack::create($handler);
    $this->client = new GuzzleClient([
      'base_uri' => $endpoint,
      'timeout'  => 60,
      'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => "Bearer {$token}",
        'User-Agent'    => self::getUserAgent(),
      ],
      'handler' => $stack,
    ]);
  }

  /**
   * Get the user agent for HTTP requests
   *
   * @return string|null
   * @throws \CiviCRM_API3_Exception
   */
  public static function getUserAgent() {
    if (is_null(self::$userAgent)) {
      $version = civicrm_api3('Extension', 'getvalue', [
        'return' => 'version',
        'key' => \CRM_Dialfire_ExtensionUtil::LONG_NAME,
      ]);
      self::$userAgent = 'CiviCRM/' . \CRM_Utils_System::version() . ' ' . \CRM_Dialfire_ExtensionUtil::LONG_NAME . '/' . $version;
    }
    return self::$userAgent;
  }


  /**
   * Sanitize an API endpoint by removing a leading and adding a trailing slash
   *
   * @param $endpoint
   *
   * @return bool|string
   */
  public static function sanitizeEndpoint($endpoint) {
    if (substr($endpoint, 0, 1) == '/') {
      $endpoint = substr($endpoint, 1);
    }
    if (substr($endpoint, -1) != '/') {
      $endpoint = $endpoint . '/';
    }
    return $endpoint;
  }

  /**
   * Build an API request URI based on the endpoint and an array of GET params
   *
   * @param $endpoint
   * @param array|NULL $params
   *
   * @return bool|string
   */
  public static function buildUri($endpoint, array $params = NULL) {
    $uri = self::sanitizeEndpoint($endpoint);
    if (!is_null($params)) {
      $uri .= '?' . http_build_query($params);
    }
    return $uri;
  }

  /**
   * Send an HTTP request of method $method to $uri with body $body
   *
   * @param $method
   * @param $uri
   * @param null $body
   *
   * @return array
   */
  public function sendJSONRequest($method, $uri, $body = NULL) {
    $request = new Request(
      $method,
      $uri,
      [],
      $body
    );
    $response = self::sendRawRequest($request);
    $body = (string) $response->getBody();
    \Civi::log('dialfire')->debug("Dialfire API Response: HTTP {$response->getStatusCode()} - '{$body}'");
    $response = [];
    if (!empty($body)) {
      $response = json_decode($body, TRUE);
      if (is_null($response)) {
        throw new BadResponseException('Invalid JSON Response: "' . $body . '" - ' . json_last_error_msg());
      }
    }
    return $response;
  }

  public function sendRawRequest(Request $request) {
    \Civi::log('dialfire')->debug('Dialfire API Request: ' . $request->getMethod() . ' ' . $request->getUri());
    try {
      return $this->client->send($request);
    }
    catch (ClientException $e) {
      if ($e->getCode() == 403) {
        throw new AuthenticationException($e->getMessage());
      }
      if ($e->getCode() == 409) {
        throw new ConflictException($e->getMessage());
      }
      throw new Exception($e->getMessage());
    }
    catch (\GuzzleHttp\Exception\BadResponseException $e) {
      throw new Exception($e->getMessage());
    }
  }

  /**
   * Send a GET HTTP request to $uri
   *
   * @param $uri
   *
   * @return mixed
   */
  public function get($uri) {
    return self::sendJSONRequest('GET', $uri);
  }

  public function getRaw($uri) {
    $request = new Request(
      'GET',
      $uri
    );
    return self::sendRawRequest($request)->getBody();
  }

  /**
   * Post the JSON-encoded body $data to $uri
   *
   * @param $uri
   * @param array $data
   *
   * @return mixed
   */
  public function postJSON($uri, array $data) {
    return self::sendJSONRequest('POST', $uri, json_encode($data));
  }

}
