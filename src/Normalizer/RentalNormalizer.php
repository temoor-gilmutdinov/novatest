<?php

namespace Drupal\novatest\Normalizer;

use Drupal\novatest\RentalInterface;
use Drupal\serialization\Normalizer\NormalizerBase;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class RentalNormalizer
 *
 * Converts Rental object structure into JSON.
 *
 * @package Drupal\novatest\Normalizer
 */
class RentalNormalizer extends NormalizerBase implements DenormalizerInterface {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string $supportedInterfaceOrClass
   */
  protected $supportedInterfaceOrClass = RentalInterface::class;

  /**
   * List of formats which supports (de-)normalization.
   *
   * @var string|string[]
   */
  protected $format = 'json';

  /**
   * List of properties vital for rental entity.
   *
   * @var array $required_props
   */
  protected $required_props = [
    'id',
  ];

  /**
   * Full list of properties that can come into rental entity.
   *
   * @var array $expected_props
   */
  protected $expected_props = [
    'picture',
    'headline',
    'beds',
    'propertyTypeName',
    'countryName',
    'pictures',
    'prices',
    'features',
    'propertySize',
    'distanceShop',
    'distanceWater',
    'propertyType',
    'bathrooms',
    'bedrooms',
    'score',
    'lat',
    'lng',
    'pets',
    'rating',
    'adults',
    'children',
    'id',
    'country',
  ];

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    // @todo: Implement normalize() method once it gets needed.
  }

  /**
   * {@inheritdoc}
   */
  public function denormalize($data, $class, $format = null, array $context = array()) {
    $optional_props = array_diff($this->expected_props, $this->required_props);
    $in_props = array_keys($data);

    if (count(array_intersect($this->required_props, $in_props)) != count($this->required_props)) {
      $missing = array_diff($this->required_props, array_diff($in_props, $optional_props));

      throw new UnexpectedValueException(sprintf(
        'Following properties are missing in the list: %s',
        implode(', ', $missing)
      ));
    }

    if (count(array_diff($in_props, $this->expected_props)) > 0) {
      throw new ExtraAttributesException(array_diff($in_props, $this->expected_props));
    }

    /** @var \Drupal\novatest\RentalInterface $item */
    $item = new $class();

    foreach ($this->expected_props as $key) {
      if (isset($data[$key])) {
        $item->set($key, $data[$key]);
      }
    }

    return $item;
  }

}
