<?php

namespace Drupal\Tests\novatest\Unit\Controller;

use Drupal\novatest\Controller\RentalController;

class TestRentalController extends RentalController {

  /**
   * Dummy getter for current page number.
   *
   * @return int
   *   Page number.
   */
  protected function getCurrentPage() {
    return 10;
  }

  /**
   * Dummy method for pager initialization.
   *
   * {@inheritdoc}
   */
  protected function initializePager($total = NULL, $count = NULL) {}

  /**
   * Dummy translation method.
   *
   * {@inheritdoc}
   *
   * @return string
   *  Original method returns instance of
   *  \Drupal\Core\StringTranslation\TranslatableMarkup
   *  but it's excessive in case of tests.
   */
  protected function t($string, array $args = NULL, array $options = NULL) {
    return $string;
  }
}
