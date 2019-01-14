<?php
  namespace Drupal\pcx_misc\Controller;
  use Drupal\node\Entity\Node;
  use Drupal\Core\Database\Database;
  
  class MiscModule {
    
    public function MiscPath(){

global $base_url;
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

// exit;
      $output  = "A";
         return array(
          '#title' => 'Import IDGNS XML',
          '#markup' => $output,
        );
      }
 }
