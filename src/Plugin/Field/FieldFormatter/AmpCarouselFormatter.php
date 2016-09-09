<?php

namespace Drupal\amp\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
//use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceEntityFormatter;

/**
 * Plugin implementation of the 'amp_carousel' formatter.
 *
 * @FieldFormatter(
 *   id = "amp_carousel",
 *   label = @Translation("AMP Carousel"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class AmpCarouselFormatter extends EntityReferenceEntityFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'amp_layout' => 'responsive',
      'amp_width' => '300',
      'amp_height' => '300',
      'amp_carousel_type' => 'slides',
      'amp_carousel_controls' => TRUE,
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

    // @TODO: This should not appear when 'fixed-height' is selected.
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

    $elements['amp_carousel_type'] = [
      '#title' => t('Carousel type'),
      '#type' =>'select',
      '#default_value' => $this->getSetting('amp_carousel_type'),
      '#options' => [
        'slides' => 'slides',
        'carousel' => 'carousel',
      ],
    ];

    $elements['amp_carousel_controls'] = [
      '#title' => t('Show carousel controls'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('amp_carousel_controls'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

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
    $summary[] = t('Carousel type: @carousel_type', ['@carousel_type' => $this->getSetting('amp_carousel_type')]);
    if ($this->getSetting('amp_carousel_controls')) {
      $summary[] = t('Show controls');
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

//    foreach ($items as $delta => $item) {
//      $elements[$delta] = ['#markup' => $this->viewValue($item)];
//    }

    $settings = $this->getSettings();
    $slides = parent::viewElements($items, $langcode);
    $elements[] = [
      '#type' => 'amp_carousel',
      '#attributes' => [
        'layout' => $settings['amp_layout'],
        'width' => $settings['amp_layout'] == 'fixed-height' ? 'auto' : $settings['amp_width'],
        'height' => $settings['amp_height'],
      ],
      '#slides' => $slides,
    ];

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
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
