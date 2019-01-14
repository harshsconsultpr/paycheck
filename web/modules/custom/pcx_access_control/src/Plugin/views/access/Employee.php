<?php

/**
 * @file
 * Definition of Drupal\pcx_access_control\Plugin\views\access\Employee
 */

namespace Drupal\pcx_access_control\Plugin\views\access;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\RoleStorageInterface;
use Drupal\views\Plugin\views\access\AccessPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;
use Drupal\Core\Session\AccountInterface;


/**
 * Access plugin that provides role-based access control.
 *
 * @ingroup views_access_plugins
 *
 * @ViewsAccess(
 *   id = "Employee",
 *   title = @Translation("Role Custom"),
 *   help = @Translation("Access will be granted to users with any of the specified roles.")
 * )
 */
class Employee extends AccessPluginBase {
  /**
   * {@inheritdoc}
   */
  protected $usesOptions = TRUE;

  /**
   * The role storage.
   *
   * @var \Drupal\user\RoleStorageInterface
   */
  protected $roleStorage;

  /**
   * Constructs a Role object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\user\RoleStorageInterface $role_storage
   *   The role storage.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RoleStorageInterface $role_storage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->roleStorage = $role_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('entity.manager')->getStorage('user_role')
    );
  }

  /**
   * Determine if the current user has access or not.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user who wants to access this view.
   *
   * @return bool
   *   Returns whether the user has access to the view.
   */
  public function access(AccountInterface $account){
    $profile = \Drupal::entityManager()->getStorage('profile')->loadByUser(user_load(\Drupal::currentUser()->id()), 'employee');
    //\Drupal::logger('$profile')->notice('<pre>' . print_r($profile,1) . '</pre>');
    if (in_array('employee', $account->getRoles())){
      // Load user profile
      $profile = \Drupal::entityManager()->getStorage('profile')->loadByUser(user_load($account->id()), 'employee');
      // Check if the employee is in GOOD STANDING
      $emp_status = $profile->field_emp_status->getString();

      if ($emp_status != 48){
        return FALSE;
      }else{
        return array_intersect(array_filter($this->options['role']), $account->getRoles());
      }
    }else{
      return array_intersect(array_filter($this->options['role']), $account->getRoles());
    }

  }

  /**
   * {@inheritdoc}
   */
  public function alterRouteDefinition(Route $route) {
    if ($this->options['role']) {
      $route->setRequirement('_role', (string) implode('+', $this->options['role']));
    }
  }

  public function summaryTitle() {
    $count = count($this->options['role']);
    if ($count < 1) {
      return $this->t('No role(s) selected');
    }
    elseif ($count > 1) {
      return $this->t('Multiple roles');
    }
    else {
      $rids = user_role_names();
      $rid = reset($this->options['role']);
      return $rids[$rid];
    }
  }


  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['role'] = ['default' => []];

    return $options;
  }

  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['role'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Role'),
        '#default_value' => $this->options['role'],
        '#options' => array_map('\Drupal\Component\Utility\Html::escape', user_role_names()),
        '#description' => $this->t('Only the checked roles will be able to access this display.'),
    ];
  }

  public function validateOptionsForm(&$form, FormStateInterface $form_state) {
    $role = $form_state->getValue(['access_options', 'role']);
    $role = array_filter($role);

    if (!$role) {
      $form_state->setError($form['role'], $this->t('You must select at least one role if type is "by role"'));
    }

    $form_state->setValue(['access_options', 'role'], $role);
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    $dependencies = parent::calculateDependencies();

    foreach (array_keys($this->options['role']) as $rid) {
      if ($role = $this->roleStorage->load($rid)) {
        $dependencies[$role->getConfigDependencyKey()][] = $role->getConfigDependencyName();
      }
    }

    return $dependencies;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return Cache::PERMANENT;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return ['user'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $profile = \Drupal::entityManager()->getStorage('profile')->loadByUser(user_load(\Drupal::currentUser()->id()), 'employee');
    //return $profile->getCacheTags();
    return ['user:' . $profile->id()];
  }
}

