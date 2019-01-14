<?php

namespace Drupal\pcx_rates\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Commission rate type entity.
 *
 * @ConfigEntityType(
 *   id = "commission_rate_type",
 *   label = @Translation("Commission rate type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\pcx_rates\CommissionRateTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\pcx_rates\Form\CommissionRateTypeForm",
 *       "edit" = "Drupal\pcx_rates\Form\CommissionRateTypeForm",
 *       "delete" = "Drupal\pcx_rates\Form\CommissionRateTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\pcx_rates\CommissionRateTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "commission_rate_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "commission_rate",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/commission_rate_type/{commission_rate_type}",
 *     "add-form" = "/admin/structure/commission_rate_type/add",
 *     "edit-form" = "/admin/structure/commission_rate_type/{commission_rate_type}/edit",
 *     "delete-form" = "/admin/structure/commission_rate_type/{commission_rate_type}/delete",
 *     "collection" = "/admin/structure/commission_rate_type"
 *   }
 * )
 */
class CommissionRateType extends ConfigEntityBundleBase implements CommissionRateTypeInterface {

  /**
   * The Commission rate type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Commission rate type label.
   *
   * @var string
   */
  protected $label;

}
