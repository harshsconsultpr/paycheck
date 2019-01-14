<?php

namespace Drupal\vppr;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\taxonomy\Entity\Vocabulary;

/**
 * Class VpprPermissions.
 *
 * @package Drupal\vppr
 */
class VpprPermissions implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity.manager'));
  }

  /**
   * Defines permissions related to vppr.
   */
  public static function permissions() {
    $perms = [];
    $names = taxonomy_vocabulary_get_names();
    $vocabularies = Vocabulary::loadMultiple($names);
    foreach ($vocabularies as $vocabulary) {
      $perms['administer ' . $vocabulary->id() . ' vocabulary terms'] = array(
        'title' => t('Administer %name vocabulary terms',
          array(
            '%name' => $vocabulary->label(),
            '%vid' => $vocabulary->id(),
          )
        ),
      );
    }
    return $perms;
  }

}
