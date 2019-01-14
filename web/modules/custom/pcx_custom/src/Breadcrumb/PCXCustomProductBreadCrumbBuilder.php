<?php

namespace Drupal\pcx_custom\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

class PCXCustomProductBreadCrumbBuilder implements BreadcrumbBuilderInterface{

    /**
     * {@inheritdoc}
     */
    public function applies(RouteMatchInterface $attributes) {
        $parameters = $attributes->getParameters()->all();

        if (isset($parameters['commerce_product'])) {
            return TRUE;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function build(RouteMatchInterface $route_match) {

        $breadcrumb = new Breadcrumb();
        $breadcrumb->addCacheContexts(["url"]);
        $product = $route_match->getParameter('commerce_product');
        $breadcrumb->addCacheTags(["commerce_product:{$product->product_id->value}"]);
        $breadcrumbs = array();

        if (!empty($product->field_product_category->entity)) {
            // Add parents
            $term = $product->field_product_category->entity;
            $tid = $term->id();
            $top_term_tid = $tid;
            do{
                $parent = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($tid);
                $parent = reset($parent);
                if ($parent){
                    //$breadcrumb->addLink($parent->toLink());
                    $tid = $parent->id();
                    $breadcrumbs[$tid] = $tid;
                }
            }while (!empty($parent));

        }
        
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
        if ($top_term_tid) {
          if ($term = taxonomy_term_load($top_term_tid)) {
            if ($link = $term->toLink()) {
              if ($breadcrumb) {
                $breadcrumb->addLink($link);
              }
            }
          }
        }

        return $breadcrumb;
    }

}
