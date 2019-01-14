<?php
namespace Drupal\pcx_checkout\Plugin\Commerce\CheckoutPane;

use Drupal\commerce_checkout\Plugin\Commerce\CheckoutFlow\CheckoutFlowInterface;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneBase;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the deduction schedule pane.
 *
 * @CommerceCheckoutPane(
 *   id = "pcx_deduction",
 *   label = @Translation("PCX Deduction"),
 *   default_step = "information",
 * )
 */
class DeductionCheckoutPane extends CheckoutPaneBase implements CheckoutPaneInterface {

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

        $pane_form['schedule'] = [
          '#type' => 'radios',
          '#title' => $this->t('Choose Your Re-Payment Plan'),
          '#default_value' => $payroll_frequency,
          '#options' => [
            ($payroll_frequency * .25) => $this->t('3 Months'),
            ($payroll_frequency * .5)  => $this->t('6 Months'),
            ($payroll_frequency * .75) => $this->t('9 Months'),
            $payroll_frequency         => $this->t('12 Months'),
          ],
          '#required' => TRUE,
        ];

        $pane_form['payment'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Deductions Per Paycheck'),
          '#default_value' => "",
          '#size' => 20,
          '#maxlength' => 2,
          '#disabled' => TRUE
        ];

        $pane_form['frequency'] = [
          '#type' => 'hidden',
          '#title' => $this->t('Frequency'),
          '#default_value' => $payroll_frequency,
          '#size' => 20,
          '#maxlength' => 2,
          '#disabled' => TRUE
        ];

        $pane_form['#attached']['library'][] = 'pcx_checkout/pcx_checkout_flow';

        return $pane_form;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitPaneForm(array &$pane_form, FormStateInterface $form_state, array &$complete_form) {
    $values = $form_state->getValue($pane_form['#parents']);
    if(!empty($values['schedule']) && !empty($values['frequency'])){
      $this->order->setData('deductions', $values['schedule']);
      $this->order->setData('frequency', $values['frequency']);
    }
  }

}
