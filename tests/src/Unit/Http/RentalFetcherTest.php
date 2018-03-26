<?php

namespace Drupal\Tests\novatest\Unit\Http;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\novatest\Http\RentalFetcher;
use Drupal\Tests\UnitTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Serializer\Serializer;

/**
 * Class RentalControllerTest
 *
 * @package Drupal\Tests\novatest\Unit\Controller
 */
class RentalFetcherTest extends UnitTestCase {

  /**
   * Mock of Guzzle Client service.
   *
   * @var \PHPUnit_Framework_MockObject_MockObject $client
   */
  protected $client;

  /**
   * Mock of stub for serializer service.
   *
   * @var \PHPUnit_Framework_MockObject_MockObject $serializer
   */
  protected $serializer;

  /**
   * Instance of tested class.
   *
   * @var RentalFetcher $fetcher;
   */
  protected $fetcher;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Mock Guzzle Client.
    $this->client = $this->createMock(Client::class);

    // Get instance of tested class.
    $this->fetcher = new RentalFetcher($this->client, $this->createMock(Serializer::class));

    // Get mock of Serializer stub class to gain advantages of PHPUnit mock.
    $this->serializer = $this->createMock(MockSerializer::class);

    // Replace mock of original serializer with mock of our stub.
    $serializer = new \ReflectionProperty($this->fetcher, 'serializer');
    $serializer->setAccessible(TRUE);
    $serializer->setValue($this->fetcher, $this->serializer);

    $this->fetcher->setEndpointUrl('https://example.com');
  }

  /**
   * Test that getFullUrl method generates URL properly.
   */
  public function testGetFullUrl() {
    $ref_url = new \ReflectionMethod($this->fetcher, 'getFullUrl');
    $ref_url->setAccessible(TRUE);

    $date_from = DateTimePlus::createFromFormat('Y-m-d', '2018-03-23');
    $date_to = DateTimePlus::createFromFormat('Y-m-d', '2018-03-30');

    $this->fetcher->setPager(1, 2);
    $this->fetcher->setStartDate($date_from);
    $this->fetcher->setEndDate($date_to);

    $this->assertEquals('https://example.com?offset=1&count=2&from=20180323&to=20180330', $ref_url->invoke($this->fetcher));
  }

  /**
   * Run with no service URL provided.
   */
  public function testGetRentalsNoURL() {
    try {
      $this->fetcher->setEndpointUrl('');

      $this->fetcher->getRentals();

      // Fail test if exception didn't occur.
      $this->assertEquals(FALSE, TRUE);
    }
    catch (\Exception $e) {
      $this->assertEquals('Service URL is not provided.', $e->getMessage());
    }
  }

  /**
   * Simulate empty response from Guzzle service.
   */
  public function testGetRentalsNoResponse() {
    try {
      $this->client
        ->method('request')
        ->willReturn('');

      $this->serializer
        ->expects($this->never())
        ->method('decode');

      $this->fetcher->getRentals();

      // Fail test if exception didn't occur.
      $this->assertEquals(FALSE, TRUE);
    }
    catch (\Exception $e) {
      $this->assertEquals('Response body contains no data.', $e->getMessage());
    }
  }

  /**
   * Simulate partially incomplete response, with results not set.
   */
  public function testGetRentalsNoRentals() {
    try {
      $response = $this->createMock(Response::class);
      $response
        ->method('getBody')
        ->willReturn('stream');

      $this->client
        ->method('request')
        ->willReturn($response);

      $this->serializer
        ->method('decode')
        ->willReturn(['total' => 1]);

      $this->serializer
        ->expects($this->never())
        ->method('denormalize');

      $this->fetcher->getRentals();

      // Fail test if exception didn't occur.
      $this->assertEquals(FALSE, TRUE);
    }
    catch (\Exception $e) {
      $this->assertEquals('"results" property is missing.', $e->getMessage());
    }
  }

  /**
   * Simulate partially incomplete response, with total count not set.
   */
  public function testGetRentalsNoTotal() {
    try {
      $response = $this->createMock(Response::class);
      $response
        ->method('getBody')
        ->willReturn('stream');

      $this->client
        ->method('request')
        ->willReturn($response);

      $this->serializer
        ->method('decode')
        ->willReturn(['results' => []]);

      $this->serializer
        ->expects($this->never())
        ->method('denormalize');

      $this->fetcher->getRentals();

      // Fail test if exception didn't occur.
      $this->assertEquals(FALSE, TRUE);
    }
    catch (\Exception $e) {
      $this->assertEquals('Total number of results is missing.', $e->getMessage());
    }
  }

  /**
   * Test correct getRentals behavior.
   */
  public function testGetRentals() {
    $response = $this->createMock(Response::class);
    $response
      ->method('getBody')
      ->willReturn('stream');

    $this->client
      ->method('request')
      ->willReturn($response);

    // Rental items can be anything, as only thing that matters in test
    // is count of them.
    $this->serializer
      ->method('decode')
      ->willReturn([
        'total' => 10,
        'results' => [
          'rentalItem1',
          'rentalItem2',
        ],
    ]);
    $this->serializer
      ->method('denormalize')
      ->willReturn('denormalizerResult');

    $result = $this->fetcher->getRentals();

    // Expectation is for getRentals to pass total number of results from
    // decoded data and array of denormalized objects from denormalizer as
    // result.
    $expected = [
      10,
      [
        'denormalizerResult',
        'denormalizerResult',
      ],
    ];

    $this->assertArrayEquals($expected, $result);
  }

}
