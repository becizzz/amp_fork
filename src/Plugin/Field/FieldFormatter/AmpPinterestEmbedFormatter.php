<?php

namespace Drupal\amp\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'amp_pinterest_embed' formatter.
 *
 * @todo Make this compatible with other fields types
 *
 * @FieldFormatter(
 *   id = "amp_pinterest_embed",
 *   label = @Translation("AMP Pinterest Embed"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class AmpPinterestEmbedFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'amp_layout' => 'responsive',
      'amp_width' => '300',
      'amp_height' => '300',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $elements = parent::settingsForm($form, $form_state);

    $layout_url = 'https://www.ampproject.org/docs/guides/responsive/control_layout.html#size-and-position-elements';
    // Add configuration options for layout.
    $elements['amp_layout'] = [
      '#title' => t('AMP Layout'),
      '#type' => 'select',
      '#default_value' => $this->getSetting('amp_layout'),
      '#empty_option' => t('None (no layout)'),
      '#options' => $this->getLayouts(),
      '#description' => $this->t('<a href=":url" target="_blank">Layout Information</a>', array(':url' => $layout_url)),
    ];

    // @todo: This should not appear when 'fixed-height' is selected.
    $elements['amp_width'] = [
      '#title' => t('Layout Width'),
      '#type' => 'textfield',
      '#size' => 10,
      '#default_value' => $this->getSetting('amp_width'),
    ];

    $elements['amp_height'] = [
      '#title' => t('Layout Height'),
      '#type' => 'textfield',
      '#size' => 10,
      '#default_value' => $this->getSetting('amp_height'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    // Display this setting only if an AMP layout is set.
    $layout_options = $this->getLayouts();
    $layout_setting = $this->getSetting('amp_layout');
    if (isset($layout_options[$layout_setting])) {
      $summary[] = t('Layout: @setting', ['@setting' => $layout_options[$layout_setting]]);
      if ($layout_options[$layout_setting] !== 'fixed-height') {
        $summary[] = t('Width: @width', ['@width' => $this->getSetting('amp_width')]);
      }
      $summary[] = t('Height: @height', ['@height' => $this->getSetting('amp_height')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $settings = $this->getSettings();
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'amp_pinterest_embed',
        '#attributes' => [
          'layout' => $settings['amp_layout'],
          'width' => $settings['amp_layout'] == 'fixed-height' ? 'auto' : $settings['amp_width'],
          'height' => $settings['amp_height'],
          'data-do' => 'embedPin',
          'data-url' => $item->uri,
        ],
      ];
    }

    return $elements;
  }

  /**
   * Return a list of AMP layouts.
   */
  private function getLayouts() {
    return [
      'fill' => 'fill',
      'fixed' => 'fixed',
      'fixed-height' => 'fixed-height',
      'nodisplay' => 'nodisplay',
      'responsive' => 'responsive',
    ];
  }
}
