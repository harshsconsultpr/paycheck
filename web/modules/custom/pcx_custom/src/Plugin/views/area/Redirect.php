<?php

/**
 * @file
 * Definition of Drupal\pcx_custom\Plugin\views\area\Redirect
 */

namespace Drupal\pcx_custom\Plugin\views\area;

use Drupal\views\Plugin\views\area\AreaPluginBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
/**
 * Defines an order total area handler.
 *
 *
 * @ingroup views_area_handlers
 *
 * @ViewsArea("redirect")
 */
class Redirect extends AreaPluginBase {

    /**
     * {@inheritdoc}
     */
    public function render($empty = FALSE) {
        // Get current term id from URL

        $current_path = \Drupal::service('path.current')->getPath();
        $path_args = explode('/', $current_path);
      //\Drupal::logger('$path_args')->notice('<pre>' . print_r($path_args,1) . '</pre>');
        if (isset($path_args[3]) && is_numeric($path_args[3])){
            $tid = $path_args[3];
            //$response = new RedirectResponse('/taxonomy/term/' . $tid . '/products');
            $response = new RedirectResponse('/catalog/products/' . $tid);
            $response->send();
        }
    }
}
