<?php

namespace Drupal\commerce_migrate_commerce\Plugin\migrate\process\d7;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateException;

/**
 * Abstract class to define the order state migration.
 */
abstract class CommerceOrderStateProcessBase extends ProcessPluginBase {

  /**
   * Provides the mapping to be used in the migration process.
   */
  abstract public function getMapping();

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $mapping = $this->getMapping();

    foreach ($mapping as $local_state => $remote_states) {
      if (in_array($value, $remote_states)) {
        return $local_state;
      }
    }

    throw new MigrateException("We were unable to match the $value state.");
  }

}
