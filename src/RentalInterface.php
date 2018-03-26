<?php

namespace Drupal\novatest;

/**
 * Interface RentalInterface.
 *
 * Defines getters and setters for properties of rental.
 *
 * @package Drupal\novatest
 */
interface RentalInterface {

  /**
   * Set an attribute of rental item.
   *
   * @param string $key
   *   Attribute key.
   * @param mixed $value
   *   Value to set.
   *
   * @return self
   */
  public function set($key, $value);

  /**
   * Returns URL of the main picture.
   *
   * @return string
   *   URL to the picture.
   */
  public function getPicture();

  /**
   * Returns headline of the rental.
   *
   * @return string
   *   Headline value.
   */
  public function getHeadline();

  /**
   * Returns number of beds available in rental.
   *
   * @return int
   *   Plain number, e.g. 2.
   */
  public function getNumberOfBeds();

  /**
   * Returns name of the property type.
   *
   * @return string
   *   Name corresponding to the property type.
   */
  public function getPropTypeName();

  /**
   * Returns name of the country property is located in.
   *
   * @return string
   *   Human-readable country name.
   */
  public function getCountryName();

  /**
   * Returns pictures associated with rental.
   *
   * @return array
   *   List of pictures. Empty if no pictures attached.
   */
  public function getPictures();

  /**
   * Returns prices for rental.
   *
   * @return array
   *   List of price objects.
   */
  public function getPrices();

  /**
   * Returns list of features of the rental.
   *
   * @return array
   *   List of features.
   */
  public function getFeatures();

  /**
   * Returns size of the property.
   *
   * @return int
   *   Size in meters square.
   */
  public function getSize();

  /**
   * Returns distance to the closest shop.
   *
   * @return int
   *   Distance in meters.
   */
  public function getDistShop();

  /**
   * Returns distance to the closest source of water.
   *
   * @return int
   *   Distance in meters.
   */
  public function getDistWater();

  /**
   * Returns identifier of property type.
   *
   * @return string
   *   String key representing property type.
   */
  public function getPropType();

  /**
   * Number of bathrooms in the property.
   *
   * @return int
   *   Plain number.
   */
  public function getBathrooms();

  /**
   * Number of bedrooms in the property.
   *
   * @return int
   *   Plain number.
   */
  public function getBedrooms();

  /**
   * Returns score of the rental.
   *
   * @return int
   *   Plain number.
   */
  public function getScore();

  /**
   * Returns geographical latitude.
   *
   * @return float
   *   Value of latitude.
   */
  public function getLat();

  /**
   * Returns geographical longitude.
   *
   * @return float
   *   Value of longitude.
   */
  public function getLng();

  /**
   * Returns flag indicating whether pets are allowed in rental.
   *
   * @return bool
   *   TRUE if allowed, FALSE otherwise.
   */
  public function getPets();

  /**
   * Returns rating of rental.
   *
   * @return int
   *   Rating value.
   */
  public function getRating();

  /**
   * Returns number of adults rental can fit.
   *
   * @return int
   *   Plain number.
   */
  public function getAdults();

  /**
   * Returns number of children rental can fit.
   *
   * @return int
   *   Plain number.
   */
  public function getChildren();

  /**
   * Returns unique ID of the rental.
   *
   * @return string
   *   First three characters are capitalized letters,
   *   numeric characters for rest.
   */
  public function getId();

  /**
   * Returns internal ID of the country property is located in.
   *
   * @return int
   *   Internal ID.
   */
  public function getCountryCode();

}
