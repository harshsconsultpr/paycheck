<?php

namespace Drupal\pcx_searchapi_view_breadcrumbs\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

class PCXSearchAPIViewBreadcrumbsBuilder implements BreadcrumbBuilderInterface{

    /**
     * {@inheritdoc}
     */
    public function applies(RouteMatchInterface $attributes) {
        $parameters = $attributes->getParameters()->all();

        if (isset($parameters['view_id']) && $parameters['view_id'] == 'product_display_view'){
            return TRUE;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function build(RouteMatchInterface $route_match) {

        $breadcrumb = new Breadcrumb();
        $breadcrumb->addCacheContexts(["url"]);
        $breadcrumbs = array();

        $path = \Drupal::request()->getpathInfo();
        $arg  = explode('/',$path);
        $tid = $arg[3];

        // Add parents
        //$top_term_tid = $tid;

        do{
            $parent = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($tid);
            $parent = reset($parent);
            if ($parent){
                $tid = $parent->id();
                $breadcrumbs[$tid] = $tid;
            }
        }while (!empty($parent));

       // Add home and catalog
       $url = Url::fromRoute('<front>');
       $breadcrumb->addLink(Link::fromTextAndUrl('Home', $url));
  
       $url = Url::fromRoute('view.catalog_taxonomy.page_2');
       $breadcrumb->addLink(Link::fromTextAndUrl('All Products', $url));

       $breadcrumbs = array_reverse($breadcrumbs);

        foreach ($breadcrumbs as $breadcrumb_item => $tid){
          if ($term = taxonomy_term_load($tid)) {
            if ($link = $term->toLink()) {
              if ($breadcrumb) {
                $breadcrumb->addLink($link);
              }
            }
          }
        }

        // Add top level term
      /*
        if ($top_term_tid) {
          if ($term = taxonomy_term_load($top_term_tid)) {
            if ($link = $term->toLink()) {
              if ($breadcrumb) {
                $breadcrumb->addLink($link);
              }
            }
          }
        }
*/
        return $breadcrumb;
    }

}
