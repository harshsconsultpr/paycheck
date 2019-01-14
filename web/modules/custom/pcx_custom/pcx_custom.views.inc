<?php

/**
 * @file
 * Provide views data for the Order module.
 */

/**
 * Implements hook_views_data().
 */
function pcx_custom_views_data() {
    $data['views']['redirect'] = [
        'title' => t('Product View Redirect'),
        'help' => t('Redirects empty behaviour to product view listing.'),
        'area' => [
            'id' => 'redirect',
        ],
    ];
    $data['views']['add_commission_org'] = [
        'title' => t('Creates a link to add a new commission item for an ORG View.'),
        'help' => t(''),
        'area' => [
            'id' => 'new_commission_link_org',
        ],
    ];
    $data['views']['add_commission_user'] = [
        'title' => t('Creates a link to add a new commission item for an USER Profile.'),
        'help' => t(''),
        'area' => [
            'id' => 'new_commission_link_user',
        ],
    ];
    $data['views']['add_referral_partner_org'] = [
        'title' => t('Creates a link to add a new referral partner from an ORG View.'),
        'help' => t(''),
        'area' => [
            'id' => 'new_referral_partner_link_org',
        ],
    ];
    $data['views']['add_referral_partner_user'] = [
        'title' => t('Creates a link to add a new referenced partner organization from an USER Profile.'),
        'help' => t(''),
        'area' => [
            'id' => 'new_referral_partner_link_user',
        ],
    ];
    return $data;
}
