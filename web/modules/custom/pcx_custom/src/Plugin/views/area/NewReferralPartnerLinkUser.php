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
 * @ViewsArea("new_referral_partner_link_user")
 */
class NewReferralPartnerLinkUser extends AreaPluginBase {

    /**
     * {@inheritdoc}
     */
    public function render($empty = FALSE) {
        // Get current term id from URL
        $current_path = \Drupal::service('path.current')->getPath();
        $path_args = explode('/', $current_path);
        $id = $path_args[2];
        //dpm($path_args);
        $text = '<a href="/user/' . $id . '/referral_partner#edit-field-rp-referral-organizations-wrapper">Refer this Partner to an Organization</a>';
        return [
            '#type' => 'processed_text',
            '#text' => $text,
            '#format' => 'full_html',
        ];
    }
}
