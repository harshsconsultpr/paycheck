<?php

namespace Drupal\pcx_orgs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Organization type entity.
 *
 * @ConfigEntityType(
 *   id = "organization_type",
 *   label = @Translation("Organization type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\pcx_orgs\OrganizationTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\pcx_orgs\Form\OrganizationTypeForm",
 *       "edit" = "Drupal\pcx_orgs\Form\OrganizationTypeForm",
 *       "delete" = "Drupal\pcx_orgs\Form\OrganizationTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\pcx_orgs\OrganizationTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "organization_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "organization",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/organization_type/{organization_type}",
 *     "add-form" = "/admin/structure/organization_type/add",
 *     "edit-form" = "/admin/structure/organization_type/{organization_type}/edit",
 *     "delete-form" = "/admin/structure/organization_type/{organization_type}/delete",
 *     "collection" = "/admin/structure/organization_type"
 *   }
 * )
 */
class OrganizationType extends ConfigEntityBundleBase implements OrganizationTypeInterface {

  /**
   * The Organization type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Organization type label.
   *
   * @var string
   */
  protected $label;

}
