<?php
/**
 * @file
 * Contains \Drupal\pcx_commission\Plugin\QueueWorker\CommissionQueueWorker.php
 */
namespace Drupal\pcx_commission\Plugin\QueueWorker;

use \Drupal\Core\Config\Entity\Query\Query;
use \Drupal\Core\Entity\Query\QueryFactory;
use \Drupal\Core\Entity\Query\QueryInterface;

use \Drupal\pcx_commission\Entity\Commission;
use \Drupal\pcx_commission\Entity\CommissionInterface;

use \Drupal\Core\Queue\QueueWorkerBase;
/**
 * Processes Tasks for My Module.
 *
 * @QueueWorker(
 *   id = "commission",
 *   title = @Translation("PCX Commission: Commission Queue"),
 *   cron = {"time" = 60}
 * )
 */
class CommissionQueueWorker extends QueueWorkerBase {
  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $order = \Drupal\commerce_order\Entity\Order::load($data['order']);

    switch ($data['action']) {
      case 'create':
        $this->createCommissions($order);
        break;

      case 'cancel':
        $this->cancelCommissions($order);
        break;

      default:
        # code...
        break;
    }
  }

  public function createCommissions($order) {
    $user = \Drupal\user\Entity\User::load($order->getCustomerId());

    if ($profile = \Drupal::entityManager()->getStorage('profile')->loadByUser($user, 'employee')) {

      $reference_item = $profile->get('field_emp_organization_ref')->first();
      $entity_reference = $reference_item->get('entity');
      $entity_adapter = $entity_reference->getTarget();
      $referenced_entity = $entity_adapter->getValue();

      $query = \Drupal::entityQuery('commission_rate');
      $query->condition('status', 1);
      $query->condition('field_organization', $referenced_entity->id());
      $entity_ids = $query->execute();
      $entities = entity_load_multiple('commission_rate', $entity_ids);

      foreach ($entities as $entity) {
        if ($rate = $entity->get('field_commission_rate')->getValue()) {
          $price = $order->getTotalPrice();
          $amount = $price->getNumber() * ($rate[0]['value'] / 100);

          $org_id = $entity->get('field_organization')->getValue();
          $organization = \Drupal::entityManager()
            ->getStorage('organization')
            ->load($org_id[0]['target_id']);

          $rp_id = $entity->get('field_referral_partner')->getValue();
          $referral_partner = \Drupal\user\Entity\User::load($rp_id[0]['target_id']);

          // get broker commission rates
          $subquery = \Drupal::entityQuery('commission_rate');
          $subquery->condition('status', 1);
          $subquery->condition('field_referred_partner', $referral_partner->id());
          $subquery_entity_ids = $subquery->execute();
          $subquery_entities = entity_load_multiple('commission_rate', $subquery_entity_ids);

          $deduction_count = $order->getData('deductions');
          $payroll_frequency = $order->getData('frequency');
          $deductions_per_month = $payroll_frequency / 12;
          $total_months = ceil($deduction_count / $deductions_per_month);
          $commission_per_month = round($amount / $total_months, 2);

          for ($i=$total_months; $i > 0; $i--) {
            $commission = \Drupal\pcx_commission\Entity\Commission::create([
              'order_id' => $order->id(),
              'rate_id' => $entity->id(),
              'amount' => $commission_per_month,
              'user_id' => $user->id(),
              'name' => $order->id()."_".$entity->id()."_".time(), // "Order #".$order->id().": ".$organization->getName()." - ".$referral_partner->getUsername(),
              'status' => 0
            ]);
            $commission->save();

            foreach ($subquery_entities as $subquery_entity) {
              if ($subquery_rate = $subquery_entity->get('field_commission_rate')->getValue()) {
                $subquery_amount = $commission_per_month * ($subquery_rate[0]['value'] / 100);
                $this->log("{$subquery_amount} = {$commission_per_month} * ({$subquery_rate[0]['value']} / 100)");

                $subquery_commission_data = [
                  'order_id' => $order->id(),
                  'rate_id' => $subquery_entity->id(),
                  'amount' => $subquery_amount,
                  'user_id' => $user->id(),
                  'name' => $order->id()."_".$subquery_entity->id()."_".time(), // "Order #".$order->id().": ".$organization->getName()." - ".$referral_partner->getUsername(),
                  'status' => 0
                ];

                $subquery_commission = \Drupal\pcx_commission\Entity\Commission::create($subquery_commission_data);

                $this->log("Data\n".print_r($subquery_commission_data,true));
                $subquery_commission->save();
              }
            }
          }
        }
      }

      return true;
    } else {
      $this->error("Unable to load Employee profile");
    }
  }

  public function cancelCommissions($order) {
    $query = \Drupal::entityQuery('commission');
    $query->condition('order_id', $order->id());
    $entity_ids = $query->execute();
    $entities = entity_load_multiple('commission', $entity_ids);

    foreach ($entities as $entity) {
      $entity->set("status", 3);
      $entity->save();
    }

    return true;
  }

  public function error($notice="") {
    throw new \Exception($notice, 1);
  }

  public function log($notice="") {
    \Drupal::logger('pcx_commission')->notice("CommissionQueueWorker: @notice", [
      '@notice' => $notice
    ]);
  }
}
