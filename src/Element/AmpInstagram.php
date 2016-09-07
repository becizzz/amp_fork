<?php

/**
 * @file
 * Contains \Drupal\amp\Element\AmpInstagram.
 */

namespace Drupal\amp\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element for amp-instagram.
 *
 * By default, this element sets #theme so that the 'amp_instagram' theme hook
 * is used for rendering, and attaches the js needed for the amp-instagram
 * component.
 *
 * Properties:
 * - #account: An array with iframe details. See template_preprocess_amp_iframe()
 *   for documentation of the properties in this array.
 *
 * @RenderElement("amp_instagram")
 */
class AmpInstagram extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return array(
      '#theme' => 'amp_instagram',
      '#attributes' => [],
      '#pre_render' => array(
        array($class, 'preRenderInstagram'),
      ),
    );
  }

  /**
   * Pre-render callback: Attaches the amp-instagram library.
   */
  public static function preRenderInstagram($element) {
    $element['#attached']['library'][] = 'amp/amp.instagram';
    return $element;
  }
}
