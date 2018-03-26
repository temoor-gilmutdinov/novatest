<?php

namespace Drupal\Tests\novatest\Unit\Http;

/**
 * Class MockSerializer
 *
 * Original decode method of Serializer class can not be mocked, therefore stub
 * is required.
 * Provides mock methods for testing of RentalFetcher class.
 *
 * @package Drupal\Tests\novatest\Unit\Http
 */
class MockSerializer {

  public function decode() {}

  public function denormalize() {}

}
