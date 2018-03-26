<?php

namespace Drupal\novatest\Entity;

use Drupal\novatest\RentalInterface;

/**
 * Class Rental
 *
 * @package Drupal\novatest\Entity
 */
class Rental implements RentalInterface {

  /**
   * @var string $picture
   */
  protected $picture;

  /**
   * @var string $headline
   */
  protected $headline;

  /**
   * @var int $beds
   */
  protected $beds;

  /**
   * @var string $propertyTypeName
   */
  protected $propertyTypeName;

  /**
   * @var string $countryName
   */
  protected $countryName;

  /**
   * @var array $pictures
   */
  protected $pictures;

  /**
   * @var array $prices
   */
  protected $prices;

  /**
   * @var array $features
   */
  protected $features;

  /**
   * @var int $propertySize
   */
  protected $propertySize;

  /**
   * @var int $distanceShop
   */
  protected $distanceShop;

  /**
   * @var int $distanceWater
   */
  protected $distanceWater;

  /**
   * @var string $propertyType
   */
  protected $propertyType;

  /**
   * @var int $bathrooms
   */
  protected $bathrooms;

  /**
   * @var int $bedrooms
   */
  protected $bedrooms;

  /**
   * @var int $score
   */
  protected $score;

  /**
   * @var float $lat
   */
  protected $lat;

  /**
   * @var float $lng
   */
  protected $lng;

  /**
   * @var bool $pets
   */
  protected $pets;

  /**
   * @var int $rating
   */
  protected $rating;

  /**
   * @var int $adults
   */
  protected $adults;

  /**
   * @var int $children
   */
  protected $children;

  /**
   * @var string $id
   */
  protected $id;

  /**
   * @var int $country
   */
  protected $country;

  /**
   * {@inheritdoc}
   */
  public function set($key, $value) {
    $this->$key = $value;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPicture() {
    return $this->picture;
  }

  /**
   * {@inheritdoc}
   */
  public function getHeadline() {
    return $this->headline;
  }

  /**
   * {@inheritdoc}
   */
  public function getNumberOfBeds() {
    return $this->beds;
  }

  /**
   * {@inheritdoc}
   */
  public function getPropTypeName() {
    return $this->propertyTypeName;
  }

  /**
   * {@inheritdoc}
   */
  public function getCountryName() {
    return $this->countryName;
  }

  /**
   * {@inheritdoc}
   */
  public function getPictures() {
    return $this->pictures;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrices() {
    return $this->prices;
  }

  /**
   * {@inheritdoc}
   */
  public function getFeatures() {
    return $this->features;
  }

  /**
   * {@inheritdoc}
   */
  public function getSize() {
    return $this->propertySize;
  }

  /**
   * {@inheritdoc}
   */
  public function getDistShop() {
    return $this->distanceShop;
  }

  /**
   * {@inheritdoc}
   */
  public function getDistWater() {
    return $this->distanceWater;
  }

  /**
   * {@inheritdoc}
   */
  public function getPropType() {
    return $this->propertyType;
  }

  /**
   * {@inheritdoc}
   */
  public function getBathrooms() {
    return $this->bathrooms;
  }

  /**
   * {@inheritdoc}
   */
  public function getBedrooms() {
    return $this->bedrooms;
  }

  /**
   * {@inheritdoc}
   */
  public function getScore() {
    return $this->score;
  }

  /**
   * {@inheritdoc}
   */
  public function getLat() {
    return $this->lat;
  }

  /**
   * {@inheritdoc}
   */
  public function getLng() {
    return $this->lng;
  }

  /**
   * {@inheritdoc}
   */
  public function getPets() {
    return $this->pets;
  }

  /**
   * {@inheritdoc}
   */
  public function getRating() {
    return $this->rating;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdults() {
    return $this->adults;
  }

  /**
   * {@inheritdoc}
   */
  public function getChildren() {
    return $this->children;
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->id;
  }

  /**
   * {@inheritdoc}
   */
  public function getCountryCode() {
    return $this->country;
  }

}
