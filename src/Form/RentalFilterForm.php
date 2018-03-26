<?php

namespace Drupal\novatest\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RentalFilterForm
 *
 * @package Drupal\novatest\Form
 */
class RentalFilterForm extends FormBase implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'novatest_rental_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $request = \Drupal::request();

    $form['from'] = [
      '#type' => 'date',
      '#title' => $this->t('From'),
      '#description' => $this->t('Show rentals available from specified date'),
      '#default_value' => Html::escape($request->query->get('from')),
    ];
    $form['to'] = [
      '#type' => 'date',
      '#title' => $this->t('To'),
      '#description' => $this->t('Show rentals available until specified date'),
      '#default_value' => Html::escape($request->query->get('to')),
    ];

    $form['count'] = [
      '#type' => 'select',
      '#options' => [
        10 => '10',
        25 => '25',
        50 => '50',
      ],
      '#default_value' => Html::escape($request->query->get('count')),
      '#title' => $this->t('Items per page'),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Apply'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = [];

    foreach (['from', 'to', 'count'] as $key) {
      if ($value = $form_state->getValue($key)) {
        $query[$key] = $value;
      }
    }

    $form_state->setRedirect('novatest.pages.rental_list', [], [
      'query' => $query,
    ]);
  }

}
