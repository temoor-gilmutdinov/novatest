<?php

namespace Drupal\Tests\novatest\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Class RentalListPageTest
 *
 * @package Drupal\Tests\novatest\Functional
 */
class RentalListPageTest extends BrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['serialization', 'novatest'];

  /**
   * Test page contents and functionality.
   */
  public function testRentalList() {
    $assert = $this->assertSession();

    $this->drupalGet('rental-list');

    $assert->statusCodeEquals(200);

    // Find the title of the node itself.
    $page = $this->getSession()
      ->getPage()
      ->find('css', 'div.rental-page');

    // Verify that page elements are in place.
    $this->assertNotNull($page);
    $list = $page->find('css', 'div.rental-list-wrapper');

    $this->assertNotNull($list);
    $this->assertNotNull($page->find('css', 'div.filters'));
    $this->assertNotNull($page->find('css', 'div.pager'));

    // Check the structure of rental listing.
    $this->assertNotNull($list->find('css', 'ul.rental-list'));
    $this->assertNotNull($list->find('css', 'li.rental-item'));

    // Check properties of list items.
    $this->assertNotNull($list->find('css', 'div.type-name'));

    // Ensure that posting values affects destination URL.
    $post = [
      'from' => '2018-01-01',
      'to' => '2018-02-01',
      'count' => '25',
    ];

    $this->drupalPostForm(NULL, $post, 'op');

    $assert->statusCodeEquals(200);

    $parsed_url = parse_url($this->getUrl());

    $this->assertEquals('from=2018-01-01&to=2018-02-01&count=25', $parsed_url['query']);
  }

}
