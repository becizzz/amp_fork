<?php

namespace Drupal\amp\Plugin\Field\FieldFormatter;

use Drupal\amp\AmpInstagramShortcode;
use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'amp_instagram' formatter.
 *
 * @FieldFormatter(
 *   id = "amp_instagram",
 *   label = @Translation("AMP Instagram"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class AmpInstagramFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\amp\AmpInstagramShortcode $ampInstagramShortcode
   */
  protected $ampInstagramShortcode;

  /**
   * AmpInstagramFormatter constructor.
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   * @param array $settings
   * @param string $label
   * @param string $view_mode
   * @param array $third_party_settings
   * @param \Drupal\amp\AmpInstagramShortcode $ampInstagramShortcode
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, AmpInstagramShortcode $ampInstagramShortcode) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->ampInstagramShortcode = $ampInstagramShortcode;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    // TODO: Implement create() method.
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('amp.instagram_shortcode')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'amp_layout' => 'responsive',
      'amp_height' => '300',
      'amp_width' => '300',
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

    $elements['amp_height'] = [
      '#title' => t('Layout Height'),
      '#type' => 'textfield',
      '#size' => 10,
      '#default_value' => $this->getSetting('amp_height'),
    ];

    // @TODO: This should not appear when 'fixed-height' is selected.
    $elements['amp_width'] = [
      '#title' => t('Layout Width'),
      '#type' => 'textfield',
      '#size' => 10,
      '#default_value' => $this->getSetting('amp_width'),
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
      $summary[] = t('Height: @height', ['@height' => $this->getSetting('amp_height')]);
      if ($layout_options[$layout_setting] !== 'fixed-height') {
        $summary[] = t('Width: @width', ['@width' => $this->getSetting('amp_width')]);
      }
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
      $matches = [];
      foreach (AmpInstagramShortcode::$validationRegexp as $pattern => $key) {
        if (preg_match($pattern, $this->ampInstagramShortcode->getSourceValue($item), $matches)) {
          break;
        }
      }

      if (!empty($matches['shortcode'])) {
        $elements[$delta] = [
          '#type' => 'amp_instagram',
          '#attributes' => [
            'data-shortcode' => $matches['shortcode'],
            'layout' => $settings['amp_layout'],
            'height' => $settings['amp_height'],
            'width' => $settings['amp_layout'] == 'fixed-height' ? 'auto' : $settings['amp_width'],
          ],
        ];
      }
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
