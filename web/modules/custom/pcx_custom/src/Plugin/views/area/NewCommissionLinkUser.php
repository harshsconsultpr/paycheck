<?php

/**
 * @file
 * Definition of Drupal\pcx_custom\Plugin\views\area\NewCommissionLink
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
 * @ViewsArea("new_commission_link_user")
 */
class NewCommissionLinkUser extends AreaPluginBase {

    /**
     * {@inheritdoc}
     */
    public function render($empty = FALSE) {
        // Get current term id from URL
        $current_path = \Drupal::service('path.current')->getPath();
        $path_args = explode('/', $current_path);
        $id = $path_args[2];
        $text = '<a href="/admin/structure/commission_rate/add/commission_rate?destination=user/'. $id .'&user_id='. $id .'">Create new commission item</a>';
        return [
            '#type' => 'processed_text',
            '#text' => $text,
            '#format' => 'full_html',
        ];
    }
}
