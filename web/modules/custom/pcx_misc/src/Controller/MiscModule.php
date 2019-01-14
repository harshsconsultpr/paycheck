<?php
  namespace Drupal\pcx_misc\Controller;
  use Drupal\node\Entity\Node;
  use Drupal\Core\Database\Database;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\pcx_order_export\Entity\OrderExportInterface;
use Drupal\pcx_order_export\Entity\OrderExport;

  
  class MiscModule {
    
    public function MiscPath(){

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


    $query = \Drupal::entityQuery('order_export')
    ->condition('status', 1);
  $entity_ids = $query->execute();
  $entities = entity_load_multiple('order_export', $entity_ids);
foreach ($entities as $order_export) {

}

$entity_ids = array('100'=>'100','101'=>'101');


/*

foreach ($entity_ids as $key => $value) {
  if ($value == '86') {
        unset($entity_ids[$key]);
    }
}


*/
echo "<pre>";
print_r($entity_ids);



print_r($entities);

















die();
/*global $base_url;
       $imgLink = drupal_get_path('module', 'pcx_misc') . '/images/data.csv';
       $imgLink1 = $base_url .'/'. $imgLink; // Image path
      $infos = array();
      $file_name = "data.csv";
      $fileHandle = fopen($imgLink1, "r");
      while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
        $images2[$row[1]] = explode("@",$row[2]);
      }
 //echo "<pre>";    print_r($images2);
   //  die();
     foreach ($images2 as $key => $value1) {
     foreach ($value1 as $key2 => $value2) {
           $cr_update_no= db_update('commerce_product__field_remote_image')
                ->fields(array('field_remote_image_uri'=>$value2))
                ->condition('entity_id', $key, '=')
                ->condition('delta', $key2, '=')
                ->execute();
     }
    }
*/
      $output  = "A";
         return array(
          '#title' => 'Import IDGNS XML',
          '#markup' => $output,
        );
      }
 }
