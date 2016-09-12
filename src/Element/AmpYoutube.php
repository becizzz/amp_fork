<?php

namespace Drupal\amp\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element for amp-youtube.
 *
 * By default, this element sets #theme so that the 'amp_youtube' theme hook
 * is used for rendering, and attaches the js needed for the amp-youtube
 * component.
 *
 * Properties:
 * - #attributes: An array with youtube element details.
 *
 * @RenderElement("amp_youtube")
 */
class AmpYoutube extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return array(
      '#theme' => 'amp_youtube',
      '#attributes' => [],
      '#pre_render' => array(
        array($class, 'preRenderYoutube'),
      ),
    );
  }

  /**
   * Pre-render callback: Attaches the amp-youtube library.
   */
  public static function preRenderYoutube($element) {
    $element['#attached']['library'][] = 'amp/amp.youtube';
    return $element;
  }
}
