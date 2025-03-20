<?php

namespace Drupal\umdlib_ds_layout_tools\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Core\Form\FormState;

class UMDLibDsLayoutConfig extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'sidebar_region' => FALSE,
      'num_rows' => 1,
      'row_one_cols' => 1,
      'row_two_cols' => 0,
      'row_three_cols' => 0,
      'row_four_cols' => 0,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    if ($form_state instanceof SubformStateInterface) {
        $form_state = $form_state->getCompleteFormState();
    }
    $configuration = $this->getConfiguration();
    $options = [
      1 => $this->t('One'),
      2 => $this->t('Two'),
      3 => $this->t('Three'),
      4 => $this->t('Four'),
    ];
    $form['sidebar_region'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add Sidebar Region'),
      '#default_value' => $configuration['sidebar_region'],
    ];
    $form['num_rows'] = [
      '#type' => 'radios',
      '#title' => $this->t('Number of Rows'),
      '#options' => $options,
      '#required' => TRUE,
      '#default_value' => $configuration['num_rows'],
    ];
    $form['row_one_cols'] = [
      '#type' => 'select',
      '#title' => $this->t('Row One Columns'),
      '#options' => $options,
      '#default_value' => $configuration['row_one_cols'],
      '#required' => TRUE,
    ];
    $form['row_two_cols'] = [
      '#type' => 'select',
      '#title' => $this->t('Row Two Columns'),
      '#options' => $options,
      '#default_value' => $configuration['row_two_cols'],
      '#states' => [
        'visible' => [
          ':input[name="num_rows"]' =>
            ['value' => 2], 'or',
            ['value' => 3], 'or',
            ['value' => 4],
        ],
      ],
    ];
    $form['row_three_cols'] = [
      '#type' => 'select',
      '#title' => $this->t('Row Three Columns'),
      '#options' => $options,
      '#default_value' => $configuration['row_three_cols'],
      '#states' => [
        'visible' => [
          ':input[name="num_rows"]' =>
            ['value' => 3], 'or',
            ['value' => 4],
        ],
      ],
    ];
    $form['row_four_cols'] = [
      '#type' => 'select',
      '#title' => $this->t('Row Four Columns'),
      '#options' => $options,
      '#default_value' => $configuration['row_four_cols'],
      '#states' => [
        'visible' => [
          ':input[name="num_rows"]' =>
            ['value' => 4],
        ],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // any additional form validation that is required
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['sidebar_region'] = $form_state->getValue('sidebar_region');
    $num_rows = $form_state->getValue('num_rows');
    $this->configuration['num_rows'] = $num_rows;

    switch ($num_rows) {
      case 1:
        $this->configuration['row_one_cols'] = $form_state->getValue('row_one_cols');
        $this->configuration['row_two_cols'] = 0;
        $this->configuration['row_three_cols'] = 0;
        $this->configuration['row_four_cols'] = 0;
        break;
      case 2:
        $this->configuration['row_one_cols'] = $form_state->getValue('row_one_cols');
        $this->configuration['row_two_cols'] = $form_state->getValue('row_two_cols');
        $this->configuration['row_three_cols'] = 0;
        $this->configuration['row_four_cols'] = 0;
        break;
      case 3:
        $this->configuration['row_one_cols'] = $form_state->getValue('row_one_cols');
        $this->configuration['row_two_cols'] = $form_state->getValue('row_two_cols');
        $this->configuration['row_three_cols'] = $form_state->getValue('row_three_cols');
        $this->configuration['row_four_cols'] = 0;
        break;
      case 4:
        $this->configuration['row_one_cols'] = $form_state->getValue('row_one_cols');
        $this->configuration['row_two_cols'] = $form_state->getValue('row_two_cols');
        $this->configuration['row_three_cols'] = $form_state->getValue('row_three_cols');
        $this->configuration['row_four_cols'] = $form_state->getValue('row_four_cols');
        break;
    } 
  }
}
