<?php

namespace Drupal\amp\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element for amp-pinterest-embed.
 *
 * By default, this element sets #theme so that the 'amp_pinterest_embed' theme hook
 * is used for rendering, and attaches the js needed for the amp-pinterest-embed
 * component.
 *
 * Properties:
 * - #attributes: An array with pinterest embed element details.
 *
 * @RenderElement("amp_pinterest_embed")
 */
class AmpPinterestEmbed extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return array(
      '#theme' => 'amp_pinterest_embed',
      '#attributes' => [],
      '#pre_render' => array(
        array($class, 'preRenderPinterestEmbed'),
      ),
    );
  }

  /**
   * Pre-render callback: Attaches the amp-pinterest library.
   */
  public static function preRenderPinterestEmbed($element) {
    $element['#attached']['library'][] = 'amp/amp.pinterest';
    return $element;
  }
}
