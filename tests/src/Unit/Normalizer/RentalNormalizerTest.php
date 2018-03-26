<?php

namespace Drupal\Tests\novatest\Unit\Normalizer;

use Drupal\novatest\Entity\Rental;
use Drupal\novatest\Normalizer\RentalNormalizer;
use Drupal\Tests\UnitTestCase;

/**
 * Class RentalNormalizerTest
 *
 * @package Drupal\Tests\novatest\Unit\Normalizer
 */
class RentalNormalizerTest extends UnitTestCase {

  /**
   * @var RentalNormalizer $normalizer
   */
  protected $normalizer;

  /**
   * Instantiate normalizer object.
   */
  public function setUp() {
    parent::setUp();

    $this->normalizer = new RentalNormalizer();
  }

  /**
   * Test normalizer against incorrect data.
   *
   * @param string $expected
   *   Expected result.
   * @param string $json
   *   Data to normalize.
   *
   * @dataProvider provideIncorrectData
   */
  public function testDenormalizeExceptions($expected, $json) {
    try {
      $this->normalizer->denormalize($json, '\Drupal\novatest\Entity\Rental', 'json');

      // Fail test if exception didn't occur.
      $this->assertEquals(FALSE, TRUE);
    }
    catch (\Exception $e) {
      $this->assertEquals($expected, $e->getMessage());
    }
  }

  /**
   * Data provider for testDenormalize().
   */
  public function provideIncorrectData() {
    return [
      ['Following properties are missing in the list: id', []],
      ['Extra attributes are not allowed ("prop_a", "prop_b" are unknown).', ['id' => 0, 'prop_a' => 1, 'prop_b' => 2]],
    ];
  }

  /**
   * Test normalizer against properly structured data.
   */
  public function testDenormalize() {
    $data = [
      'picture' => '//sdc.novasol.com/pic/scr/scr065_pool_01.jpg',
      'headline' => 'Portoroz',
      'beds' => 3,
      'propertyTypeName' => 'Holiday apartment',
      'countryName' => 'Slovenia',
      'pictures' => null,
      'prices' => [['totalPrice' => 929]],
      'features' => null,
      'propertySize' => 112,
      'distanceShop' => 600,
      'distanceWater' => 450,
      'propertyType' => 'APARTMENT',
      'bathrooms' => 2,
      'bedrooms' => 3,
      'score' => 1,
      'lat' => 45.5184,
      'lng' => 13.5888,
      'pets' => 0,
      'rating' => 4,
      'adults' => 5,
      'children' => 0,
      'id' => 'SCR065',
      'country' => 705,
    ];

    $expected = new Rental();

    foreach ($data as $key => $value) {
      $expected->set($key, $value);
    }

    $result = $this->normalizer->denormalize($data, '\Drupal\novatest\Entity\Rental', 'json');

    $this->assertEquals($result, $expected);
  }

}
