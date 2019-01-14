<?php
namespace Drupal\pcx_checkout\Plugin\Commerce\CheckoutPane;

use Drupal\commerce_checkout\Plugin\Commerce\CheckoutFlow\CheckoutFlowInterface;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneBase;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the review pane.
 *
 * @CommerceCheckoutPane(
 *   id = "pcx_review",
 *   label = @Translation("PCX Review"),
 *   default_step = "confirm",
 * )
 */
class ReviewCheckoutPane extends CheckoutPaneBase implements CheckoutPaneInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CheckoutFlowInterface $checkout_flow, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $checkout_flow, $entity_type_manager);
  }

  /**
   * {@inheritdoc}
   */
  public function buildPaneForm(array $pane_form, FormStateInterface $form_state, array &$complete_form) {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    if (in_array("employee", $user->getRoles())) {
      if ($profile = \Drupal::entityManager()->getStorage('profile')->loadByUser($user, 'employee')) {
        $reference_item = $profile->get('field_emp_organization_ref')->first();
        $entity_reference = $reference_item->get('entity');
        $entity_adapter = $entity_reference->getTarget();
        $referenced_entity = $entity_adapter->getValue();

        $payroll_frequency = $referenced_entity->field_org_payroll_frequency->getString();

        $schedule = $this->order->getData('deductions');
        $total = $this->order->getTotalPrice();
        $payment = "$".number_format(($total->getNumber() / $schedule), 2, '.', ',');

        $pane_form['schedule'] = [
          '#type' => 'radios',
          '#title' => $this->t('Choose Your Re-Payment Plan'),
          '#default_value' => $schedule,
          '#options' => [
            ($payroll_frequency * .25) => $this->t('3 Months'),
            ($payroll_frequency * .5)  => $this->t('6 Months'),
            ($payroll_frequency * .75) => $this->t('9 Months'),
            $payroll_frequency         => $this->t('12 Months'),
          ],
          '#disabled' => TRUE
        ];

        $pane_form['payment'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Deductions Per Paycheck'),
          '#default_value' => $payment,
          '#size' => 20,
          '#maxlength' => 2,
          '#disabled' => TRUE
        ];

        return $pane_form;
      }
    }
  }

}
