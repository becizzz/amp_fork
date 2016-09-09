<?php

/**
 * @file
 * Contains \Drupal\amp\Element\AmpCarousel.
 */

namespace Drupal\amp\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element for amp-carousel.
 *
 * By default, this element sets #theme so that the 'amp_carousel' theme hook
 * is used for rendering, and attaches the js needed for the amp-carousel
 * component.
 *
 * Properties:
 * - #account: An array with iframe details. See template_preprocess_amp_carousel()
 *   for documentation of the properties in this array.
 *
 * @RenderElement("amp_carousel")
 */
class AmpCarousel extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return array(
      '#theme' => 'amp_carousel',
      '#attributes' => [],
      '#slides' => [],
      '#pre_render' => array(
        array($class, 'preRenderCarousel'),
      ),
    );
  }

  /**
   * Pre-render callback: Attaches the amp-carousel library.
   */
  public static function preRenderCarousel($element) {
    $element['#attached']['library'][] = 'amp/amp.carousel';
    return $element;
  }
}
