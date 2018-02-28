<?php

namespace Drupal\node_export;
use Drupal\node\Entity\Node;

class nodeExport {
  public static function NodeExport ($nid, &$context){
    $message = 'Exporting Nodes...';
    $results = array();
    print_r($nid);
    die();
    $context['results'][$nid] =  \Drupal\node\Entity\Node::load($nid);
   
    $context['message'] = $message;
    $context['results'] = $results;
  }
  function NodeExportFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One node processed.', '@count posts processed.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
      print_r(count($results));
  
    die();
    drupal_set_message($nodes);
  }
  
}
