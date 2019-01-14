<?php

namespace Drupal\commerce_migrate;

/**
 * Class Utility
 *
 * @package Drupal\commerce_migrate
 */
class Utility {

  /**
   * Determine if a class is in a list of class names.
   *
   * @param $className
   *   Class name of plugin.
   * @param array $classes
   *   List of classes to compare.
   * @param bool (optional) $allow_string
   *   If set to FALSE, string class name as object is not allowed.
   *   This also prevents calling autoloader if the class doesn't exist.
   *
   * @return bool
   *   TRUE if it is a class in the list or else FALSE.
   */
  public static function classInArray($className, array $classes, $allowString = TRUE) {
    foreach ($classes as $class) {
      if (is_a($className, $class, $allowString)) {
        return TRUE;
      }
    }

    return FALSE;
  }

}
