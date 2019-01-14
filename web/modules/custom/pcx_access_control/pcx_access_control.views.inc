<?php

/**
 * @file
 * Provide views data for custom employee access
 */

/**
 * Implements hook_views_data().
 */
function pcx_access_control_views_data() {
  $data['views']['employee'] = [
      'title' => t('Custom Employee Access Control'),
      'help' => t('Provide custom access control for Employee Roles.'),
      'access' => [
          'id' => 'employee',
      ],
  ];
  return $data;
}