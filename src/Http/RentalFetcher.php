<?php

namespace Drupal\novatest\Http;

use Drupal\Component\Datetime\DateTimePlus;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\Serializer;

/**
 * Class RentalFetcher
 *
 * @package Drupal\novatest\Http
 */
class RentalFetcher {

  /**
   * @var Client $client.
   */
  protected $client;

  /**
   * @var Serializer $serializer.
   */
  protected $serializer;

  /**
   * @var string $url.
   */
  protected $url;

  /**
   * @var array $queryParameters.
   */
  protected $queryParameters;

  /**
   * RentalFetcher constructor.
   *
   * @param \GuzzleHttp\Client $client
   *   Guzzle client to perform HTTP requests.
   * @param \Symfony\Component\Serializer\Serializer $serializer
   *   Serializer to convert fetched data into objects.
   */
  public function __construct(Client $client, Serializer $serializer) {
    $this->client = $client;
    $this->serializer = $serializer;
  }

  /**
   * Set URL of rental endpoint.
   *
   * @param string $url
   *   URL value.
   */
  public function setEndpointUrl($url) {
    $this->url = $url;
  }

  /**
   * Get URL of the service endpoint, with all additional parameters.
   *
   * @return string
   *   Full URL value.
   */
  protected function getFullUrl() {
    $parameters = '';

    if (!empty($this->queryParameters)) {
      $parameters = '?' . http_build_query($this->queryParameters);
    }

    return $this->url . $parameters;
  }

  /**
   * Set offset and limit for service request.
   *
   * @param int $offset
   *   Number of items to skip.
   * @param int $count
   *   Number of items to request.
   */
  public function setPager($offset, $count) {
    $this->queryParameters['offset'] = $offset;
    $this->queryParameters['count'] = $count;
  }

  /**
   * Set start date for filtering.
   *
   * @param DateTimePlus $date
   *   Date object.
   */
  public function setStartDate(DateTimePlus $date) {
    $this->queryParameters['from'] = $date->format('Ymd');
  }

  /**
   * Set end date for filtering.
   *
   * @param DateTimePlus $date
   *   Date object
   */
  public function setEndDate(DateTimePlus $date) {
    $this->queryParameters['to'] = $date->format('Ymd');
  }

  /**
   * Reset any query parameters that were previously set.
   */
  public function clear() {
    $this->queryParameters = [];
  }

  /**
   * Get list of rentals.
   *
   * @return array
   *   List containing array of \Drupal\novatest\Entity\Rental objects and total
   *   number of available items.
   *
   * @throws \Exception
   *   In case of data returned from endpoint being incomplete or not matching
   *   expected pattern.
   */
  public function getRentals() {

    if (empty($this->url)) {
      throw new \Exception('Service URL is not provided.');
    }

    $rental_list = [];

    $raw = $this->client->request('GET', $this->getFullUrl());

    if (empty($raw)) {
      throw new \Exception('Response body contains no data.');
    }

    $data_decoded = $this->serializer->decode($raw->getBody(), 'json');

    if (!isset($data_decoded['results'])) {
      throw new \Exception('"results" property is missing.');
    }

    if (!isset($data_decoded['total'])) {
      throw new \Exception('Total number of results is missing.');
    }

    foreach ($data_decoded['results'] as $item) {
      $rental_list[] = $this->serializer->denormalize($item, '\Drupal\novatest\Entity\Rental', 'json');
    }

    return [$data_decoded['total'], $rental_list];
  }

}
