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
 * - #attributes: An array with carousel element attributes.
 * - #slides: An array of carousel slides.
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
