<?php /**
 * @file
 * Contains \Drupal\miniorange_saml\EventSubscriber\InitSubscriber.
 */

namespace Drupal\miniorange_saml\EventSubscriber;

use Drupal\miniorange_saml\Controller\miniorange_samlController;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\user\Entity\User;

class InitSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [KernelEvents::REQUEST => ['onEvent', 0]];
  }

  public function onEvent() {
    global $base_url;
	// $current_path = \Drupal::service('path.current')->getPath();
    $relay_state = '';
    $force_auth = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_force_auth');
    $enable_saml_login = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_enable_login');
    $enable_backdoor = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_enable_backdoor');
	$license_key = \Drupal::config('miniorange_saml.settings')->get('miniorange_saml_license_key');

    if ($enable_saml_login) {
		if($enable_backdoor && isset($_GET['saml_login']) && $_GET['saml_login'] == 'false') {
			
			// if(isset($_POST['form_id']) && $_POST['form_id'] == 'user_login_form') {
		
				// if(isset($_POST['name']) && isset($_POST['pass'])){
					// $name = $_POST['name'];
					// $pass = $_POST['pass'];
					// $uid = \Drupal::service('user.auth')->authenticate($name, $pass);
					// $user = User::load($uid);
				// }
			// }	
		}
		else if($force_auth && !\Drupal::currentUser()->isAuthenticated() && !isset($_POST['SAMLResponse']) && !isset($_POST['pass'])) {
			$current_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			\Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('current_link',$current_link)->save();  
			miniorange_samlController::saml_login($relay_state);
		}
		
		if($license_key == NULL) {
			\Drupal::state()->delete('miniorange_saml_enable_login');
			\Drupal::state()->delete('miniorange_saml_force_auth');
			\Drupal::state()->delete('miniorange_saml_enable_backdoor');
		}
    
	}
	
  }

}
