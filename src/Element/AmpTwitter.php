<?php

namespace Drupal\amp\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element for amp-twitter.
 *
 * By default, this element sets #theme so that the 'amp_twitter' theme hook
 * is used for rendering, and attaches the js needed for the amp-twitter
 * component.
 *
 * Properties:
 * - #attributes: An array with twitter element details.
 *
 * @RenderElement("amp_twitter")
 */
class AmpTwitter extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return array(
      '#theme' => 'amp_twitter',
      '#attributes' => [],
      '#pre_render' => array(
        array($class, 'preRenderTwitter'),
      ),
    );
  }

  /**
   * Pre-render callback: Attaches the amp-twitter library.
   */
  public static function preRenderTwitter($element) {
    $element['#attached']['library'][] = 'amp/amp.twitter';
    return $element;
  }
}
