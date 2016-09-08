<?php

namespace Drupal\amp;

use Drupal\Core\Field\FieldItemInterface;

/**
 * Service class for extracting Instagram shortcode.
 */
class AmpInstagramShortcode {

  /**
   * List of validation regular expressions.
   *
   * @var array
   */
  public static $validationRegexp = array(
    '@((http|https):){0,1}//(www\.){0,1}instagram\.com/p/(?<shortcode>[a-z0-9_-]+)@i' => 'shortcode',
    '@((http|https):){0,1}//(www\.){0,1}instagr\.am/p/(?<shortcode>[a-z0-9_-]+)@i' => 'shortcode',
  );

  /**
   * Extracts the raw value.
   * Based on code from media_entity module.
   *
   * @param mixed $value
   *   The input value. Can be a normal string or a value wrapped by the
   *   Typed Data API.
   *
   * @return string|null
   */
  public function getSourceValue($value) {
    if (is_string($value)) {
      return $value;
    }
    elseif ($value instanceof FieldItemInterface) {
      $class = get_class($value);
      $property = $class::mainPropertyName();
      if ($property) {
        return $value->$property;
      }
    }
  }

}