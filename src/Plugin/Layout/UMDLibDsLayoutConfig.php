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
      'section_width' => FALSE,
      'section_vertical_spacing' => 'default',
      'num_rows' => 1,
      'row_1_cols' => 1,
      'row_2_cols' => 0,
      'row_3_cols' => 0,
      'row_4_cols' => 0,
      'row_5_cols' => 0,
      'row_6_cols' => 0,
      'row_7_cols' => 0,
      'row_8_cols' => 0,
      'row_9_cols' => 0,
      'row_10_cols' => 0,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $configuration = $this->getConfiguration();
    $rows = [
      1 => $this->t('One'),
      2 => $this->t('Two'),
      3 => $this->t('Three'),
      4 => $this->t('Four'),
      5 => $this->t('Five'),
      6 => $this->t('Six'),
      7 => $this->t('Seven'),
      8 => $this->t('Eight'),
      9 => $this->t('Nine'),
      10 => $this->t('Ten'),
    ];
    $options = [
      1 => $this->t('One'),
      2 => $this->t('Two'),
      3 => $this->t('Three'),
      4 => $this->t('Four'),
    ];
    $sizes = [
      'default' => $this->t('Default'),
      'large' => $this->t('Large'),
      'small' => $this->t('Small')
    ];
    $spacing = [
      'default' => $this->t('Default'),
      'none' => $this->t('None'),
    ];

    $form['#attached']['library'][] = 'umdlib_ds_layout_tools/webform.forked';

    $form['sidebar_region'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add Sidebar Region'),
      '#default_value' => !empty($configuration['sidebar_region']) ? $configuration['sidebar_region'] : FALSE,
    ];
    $form['section_width'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Full Page Width'),
      '#states' => [
        'disabled' => [
          ':input[name="layout_settings[sidebar_region]"]' => ['checked' => TRUE],
        ]
      ],
      '#default_value' => !empty($configuration['section_width']) ? $configuration['section_width'] : FALSE,
    ];
    $form['section_vertical_spacing'] = [
      '#type' => 'select',
      '#title' => $this->t('Section Vertical Spacing'),
      '#options' => $sizes,
      '#required' => TRUE,
      '#default_value' => !empty($configuration['section_vertical_spacing']) ? $configuration['section_vertical_spacing'] : 'default',
    ];
    $form['num_rows'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of Rows'),
      '#options' => $rows,
      '#required' => TRUE,
      '#default_value' => !empty($configuration['num_rows']) ? $configuration['num_rows'] : 1,
    ];
    $is_open_required = TRUE;
    foreach ($rows as $key => $value) {
      $machine_name = 'row_' . $key;
      $friendly_name = $this->t('Row') . ' ' .  $value;
      $form[$machine_name] = [
        '#type' => 'details',
        '#open' => $is_open_required,
        '#title' => $friendly_name,
        '#states' => [
          'visible' => [
            ':input[name="layout_settings[num_rows]"]' => ['value' => ['greater_equal' => $key ]],
          ],
        ],
      ];
      $form[$machine_name]['card_group'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Card Group'),
        '#default_value' => !empty($configuration['column_config'][$machine_name]['card_group']) ? 
                 $configuration['column_config'][$machine_name]['card_group'] : FALSE,
      ];
      $form[$machine_name][$machine_name . '_cols'] = [
        '#type' => 'select',
        '#title' => $friendly_name . ' ' . $this->t('Columns'),
        '#options' => $options,
        '#default_value' => !empty($configuration['column_config'][$machine_name]['cols']) ? 
                             $configuration['column_config'][$machine_name]['cols'] : 1,
        '#required' => $is_open_required,
      ];
      $form[$machine_name][$machine_name . '_horizontal'] = [
        '#type' => 'select',
        '#title' => $friendly_name . ' ' . $this->t('Column Spacing'),
        '#options' => $spacing,
        '#default_value' => !empty($configuration['column_config'][$machine_name]['horizontal']) ? 
                             $configuration['column_config'][$machine_name]['horizontal'] : 'default',
        '#required' => $is_open_required,
      ];
      $form[$machine_name][$machine_name . '_vertical'] = [
        '#type' => 'select',
        '#title' => $friendly_name . ' ' . $this->t('Vertical Spacing'),
        '#options' => $sizes,
        '#default_value' => !empty($configuration['column_config'][$machine_name]['vertical']) ? 
                             $configuration['column_config'][$machine_name]['vertical'] : 'default',
        '#required' => $is_open_required,
      ];
      $is_open_required = FALSE;
    }

    return parent::buildConfigurationForm($form, $form_state);
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
    parent::submitConfigurationForm($form, $form_state);
    $rows = [
      1 => $this->t('One'),
      2 => $this->t('Two'),
      3 => $this->t('Three'),
      4 => $this->t('Four'),
      5 => $this->t('Five'),
      6 => $this->t('Six'),
      7 => $this->t('Seven'),
      8 => $this->t('Eight'),
      9 => $this->t('Nine'),
      10 => $this->t('Ten'),
    ];
    $vals = $form_state->getValues();

    $section_vertical_spacing = $form_state->getValue('section_vertical_spacing');
    $this->configuration['section_vertical_spacing'] = $section_vertical_spacing;

    $sidebar_region = $form_state->getValue('sidebar_region'); 

    $this->configuration['sidebar_region'] = $sidebar_region;
    if (!empty($sidebar_region)) {
      $this->configuration['section_width'] = FALSE;
    } else {
      $this->configuration['section_width'] = $form_state->getValue('section_width');
    }
    $num_rows = $form_state->getValue('num_rows');
    $this->configuration['num_rows'] = $num_rows;

    $column_info = [];
    $i = 1;
    foreach ($rows as $key => $value) {
      $machine_name = 'row_' . $key;
      $row_cols = 0;
      if ($i <= $num_rows) {
        $row_cols = $vals[$machine_name][$machine_name . '_cols'];
      }
      // $this->configuration[$machine_name . '_cols'] = $row_cols;
      $column_info[$machine_name]['card_group'] = !empty($vals[$machine_name]['card_group']) ? 
        (bool) $vals[$machine_name]['card_group'] : FALSE;
      $column_info[$machine_name]['cols'] = $row_cols;
      $column_info[$machine_name]['horizontal'] = $vals[$machine_name][$machine_name . '_horizontal'];
      $column_info[$machine_name]['vertical'] = $vals[$machine_name][$machine_name . '_vertical'];
      $i++;
    }
    $this->configuration['column_config'] = $column_info;
    \Drupal::logger('layouts')->info(json_encode($vals));
  }
}
