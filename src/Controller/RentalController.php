<?php

namespace Drupal\novatest\Controller;

use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Form\FormState;
use Drupal\Core\Messenger\Messenger;
use Drupal\novatest\Http\RentalFetcher;
use Drupal\novatest\Form\RentalFilterForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RentalListController
 *
 * @package Drupal\novatest\Controllers
 */
class RentalController extends ControllerBase implements ContainerInjectionInterface {
  /**
   * @var RentalFetcher $fetcher
   */
  protected $fetcher;

  /**
   * @var FormBuilder $formBuilder
   */
  protected $formBuilder;

  /**
   * @var Messenger $messenger
   */
  protected $messenger;

  /**
   * RentalController constructor.
   *
   * @param \Drupal\novatest\Http\RentalFetcher $fetcher
   *   Fetcher service to retrieve rental list.
   * @param \Drupal\Core\Form\FormBuilder $form_builder
   *   Form builder to construct filters.
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   Messenger service to provide user with possible error details.
   */
  public function __construct(RentalFetcher $fetcher, FormBuilder $form_builder, Messenger $messenger) {
    $this->fetcher = $fetcher;
    $this->formBuilder = $form_builder;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('novatest.rental_fetcher'),
      $container->get('form_builder'),
      $container->get('messenger')
    );
  }

  /**
   * Wrapper around pager_find_page function.
   *
   * @return int
   *   Current page.
   */
  protected function getCurrentPage() {
    return pager_find_page();
  }

  /**
   * Wrapper around pager_default_initialize function.
   *
   * @param int $total
   *   Total number of results.
   * @param int $count
   *   Number of items to show per page.
   */
  protected function initializePager($total, $count) {
    pager_default_initialize($total, $count);
  }

  public function listPage(Request $request) {
    $rental_list = $pager = '';

    $form = new RentalFilterForm();
    $form_state = new FormState();
    $form_renderable = $this->formBuilder->buildForm($form, $form_state);

    $page = $this->getCurrentPage();

    // Get items per page settings from GET parameters.
    $count = $request->query->get('count');

    $count = $count ? Html::escape($count) : 10;

    $this->fetcher->setPager($count * $page, $count);

    // Try to extract and transform start date.
    if ($from = $request->query->get('from')) {
      try {
        $date_from = DateTimePlus::createFromFormat('Y-m-d', Html::escape($from));

        // This statement can be reached only if supplied date is
        // of proper format.
        $this->fetcher->setStartDate($date_from);
      }
      catch (\Exception $e) {
        $this->messenger->addMessage($this->t('Unable to identify start date.'), Messenger::TYPE_WARNING);
      }
    }

    // Try to extract and transform end date.
    if ($to = $request->query->get('to')) {
      try {
        $date_to = DateTimePlus::createFromFormat('Y-m-d', Html::escape($to));

        // This statement can be reached only if supplied date is
        // of proper format.
        $this->fetcher->setEndDate($date_to);
      }
      catch (\Exception $e) {
        $this->messenger->addMessage($this->t('Unable to identify end date.'), Messenger::TYPE_WARNING);
      }
    }

    try {
      list($total, $rentals) = $this->fetcher->getRentals();

      if (!empty($rentals)) {
        $rental_list = [
          '#theme' => 'rental_list',
          '#rentals' => [],
        ];

        foreach ($rentals as $rental) {
          $rental_list['#rentals'][] = [
            '#theme' => 'rental_item',
            '#item' => $rental,
          ];
        }
      }

      if (!empty($total)) {
        $this->initializePager($total, $count);

        $pager = [
          '#type' => 'pager',
        ];
      }
    }
    catch (\Exception $e) {
      $this->messenger->addMessage($this->t('Service is temporary unavailable.'), Messenger::TYPE_ERROR);
    }

    return [
      '#theme' => 'rental_page',
      '#filter_form' => $form_renderable,
      '#list' => $rental_list,
      '#pager' => $pager,
      '#title' => $this->t('Rental List'),
    ];
  }
}
