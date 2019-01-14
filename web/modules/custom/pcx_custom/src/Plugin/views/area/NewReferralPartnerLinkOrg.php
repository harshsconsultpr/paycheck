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
 * @ViewsArea("new_referral_partner_link_org")
 */
class NewReferralPartnerLinkOrg extends AreaPluginBase {

    /**
     * {@inheritdoc}
     */
    public function render($empty = FALSE) {
        // Get current term id from URL
        $current_path = \Drupal::service('path.current')->getPath();
        $path_args = explode('/', $current_path);
        $id = $path_args[4];
        //dpm($path_args);
        $text = '<a href="/admin/people/create?org_id=' . $id .'&role=referral_partner&destination=admin/structure/organization/' . $id .'">Create new referral partner</a>';
        return [
            '#type' => 'processed_text',
            '#text' => $text,
            '#format' => 'full_html',
        ];
    }
}
