<?php

namespace Drupal\pcx_accounting\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Organization invoice line item edit forms.
 *
 * @ingroup pcx_accounting
 */
class OrganizationInvoiceLineItemForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\pcx_accounting\Entity\OrganizationInvoiceLineItem */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Organization invoice line item.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Organization invoice line item.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.organization_invoice_line_item.canonical', ['organization_invoice_line_item' => $entity->id()]);
  }

}
