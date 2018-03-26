<?php

namespace Drupal\Tests\novatest\Unit\Controller;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Messenger\Messenger;
use Drupal\novatest\Http\RentalFetcher;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\Form\FormBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RentalControllerTest
 *
 * @package Drupal\Tests\novatest\Unit\Controller
 */
class RentalControllerTest extends UnitTestCase {

  /**
   * @var \PHPUnit_Framework_MockObject_MockObject $request
   */
  protected $request;

  /**
   * @var \PHPUnit_Framework_MockObject_MockObject $fetcher
   */
  protected $fetcher;

  /**
   * @var \PHPUnit_Framework_MockObject_MockObject $form_builder
   */
  protected $form_builder;

  /**
   * @var \PHPUnit_Framework_MockObject_MockObject $messenger
   */
  protected $messenger;

  /**
   * @var \Drupal\Tests\novatest\Unit\Controller\TestRentalController $controller
   */
  protected $controller;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Generate mocks necessary for controller to function.
    $this->request = $this->createMock(Request::class);
    $this->fetcher = $this->createMock(RentalFetcher::class);
    $this->form_builder = $this->createMock(FormBuilder::class);
    $this->messenger = $this->createMock(Messenger::class);
    $query = $this->createMock(ParameterBag::class);

    $this->request->query = $query;

    // Instantiate controller object with messenger mock.
    $this->controller = new TestRentalController($this->fetcher, $this->form_builder, $this->messenger);

    // Replace mock messenger with a stub to keep record on messages.
    $messenger = new \ReflectionProperty($this->controller, 'messenger');
    $messenger->setAccessible(TRUE);
    $messenger->setValue($this->controller, $this->messenger);
  }

  /**
   * Test results in normal flow.
   */
  public function testListPage() {
    $this->request->query->method('get')->willReturn(
      // Items per page.
      25,
      // Start date.
      '2018-01-01',
      // End date.
      '2018-01-02'
    );

    $this->form_builder->method('buildForm')->willReturn('form_renderable');

    $this->fetcher
      ->expects($this->once())
      ->method('setPager')
      ->with(250, 25);

    // Date assertions will fail on slow machines/during debug due to DateTime
    // object substituting remaining part(hours, minutes and seconds) for
    // internal time value without consent.
    $this->fetcher
      ->expects($this->once())
      ->method('setStartDate')
      ->with($this->equalTo(DateTimePlus::createFromFormat('Y-m-d', '2018-01-01')));

    $this->fetcher
      ->expects($this->once())
      ->method('setEndDate')
      ->with($this->equalTo(DateTimePlus::createFromFormat('Y-m-d', '2018-01-02')));

    $this->fetcher
      ->expects($this->once())
      ->method('getRentals')
      ->willReturn([1, ['rental_object']]);

    $this->messenger
      ->expects($this->never())
      ->method('addMessage');

    $result = $this->controller->listPage($this->request);

    $this->assertArrayEquals([
      '#theme' => 'rental_page',
      '#filter_form' => 'form_renderable',
      '#list' => [
        '#theme' => 'rental_list',
        '#rentals' => [
          [
            '#theme' => 'rental_item',
            '#item' => 'rental_object',
          ]
        ],
      ],
      '#pager' => [
        '#type' => 'pager',
      ],
      '#title' => 'Rental List',
    ], $result);
  }

  /**
   * Test handling of incorrectly formatted start date.
   */
  public function testListPageStartDate() {
    $this->request->query->method('get')->willReturn(
    // Items per page.
      25,
      // Start date.
      '2018/01/01',
      // End date.
      '2018-01-02'
    );

    $this->fetcher
      ->expects($this->never())
      ->method('setStartDate');

    $this->fetcher
      ->expects($this->once())
      ->method('setEndDate');

    $this->fetcher
      ->expects($this->once())
      ->method('getRentals')
      ->willReturn([0, []]);

    $this->messenger
      ->expects($this->once())
      ->method('addMessage')
      ->with('Unable to identify start date.', 'warning');

    $result = $this->controller->listPage($this->request);

    $this->assertArrayEquals([
      '#theme' => 'rental_page',
      '#filter_form' => NULL,
      '#list' => '',
      '#pager' => '',
      '#title' => 'Rental List',
    ], $result);
  }

  /**
   * Test handling of incorrectly formatted end date.
   */
  public function testListPageEndDate() {
    $this->request->query->method('get')->willReturn(
    // Items per page.
      25,
      // Start date.
      '2018-01-01',
      // End date.
      '20180102'
    );

    $this->fetcher
      ->expects($this->once())
      ->method('setStartDate');

    $this->fetcher
      ->expects($this->never())
      ->method('setEndDate');

    $this->fetcher
      ->expects($this->once())
      ->method('getRentals')
      ->willReturn([0, []]);

    $this->messenger
      ->expects($this->once())
      ->method('addMessage')
      ->with('Unable to identify end date.', 'warning');
    //        ['Service is temporary unavailable.', 'warning']);

    $result = $this->controller->listPage($this->request);

    $this->assertArrayEquals([
      '#theme' => 'rental_page',
      '#filter_form' => NULL,
      '#list' => '',
      '#pager' => '',
      '#title' => 'Rental List',
    ], $result);
  }

  /**
   * Test handling of incorrectly formatted end date.
   */
  public function testListPageResponseFailure() {
    $this->request->query->method('get')->willReturn(
      // Items per page.
      25,
      // Start date.
      '2018-01-01',
      // End date.
      '2018-01-02'
    );

    $this->fetcher
      ->expects($this->once())
      ->method('setStartDate');

    $this->fetcher
      ->expects($this->once())
      ->method('setEndDate');

    $this->fetcher
      ->expects($this->once())
      ->method('getRentals')
      ->willThrowException(new \Exception('fetching data went wrong.'));

    $this->messenger
      ->expects($this->once())
      ->method('addMessage')
      ->with('Service is temporary unavailable.', 'error');

    $result = $this->controller->listPage($this->request);

    $this->assertArrayEquals([
      '#theme' => 'rental_page',
      '#filter_form' => NULL,
      '#list' => '',
      '#pager' => '',
      '#title' => 'Rental List',
    ], $result);
  }

}
