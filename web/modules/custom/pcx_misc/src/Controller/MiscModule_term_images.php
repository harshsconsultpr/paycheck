<?php
  namespace Drupal\pcx_misc\Controller;
  use Drupal\node\Entity\Node;
  use Drupal\Core\Database\Database;
  
  class MiscModule {
    
    public function MiscPath(){
     global $base_url;
       $imgLink = drupal_get_path('module', 'pcx_misc') . '/images/taxonomy_update_path.csv';
       $imgLink1 = $base_url .'/'. $imgLink; // Image path
      $infos = array();
      $file_name = "taxonomy_update_path.csv";
      $fileHandle = fopen($imgLink1, "r");
      while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
        $images2[$row[1]] =$row[2];   
      }
// echo "<pre>";    print_r($images2);
 //  die();
     foreach ($images2 as $key => $value1) {
     foreach ($value1 as $key2 => $value2) {
           $cr_update_no= db_update('taxonomy_term__field_category_image')
                ->fields(array('field_category_image_uri'=>$value2))
                ->condition('entity_id', $key, '=')
               // ->condition('delta', $key2, '=')
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