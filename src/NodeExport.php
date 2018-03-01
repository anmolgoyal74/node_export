<?php
/**
 * @file
 * Contains \Drupal\node_export\nodeExport
 */

namespace Drupal\node_export;

use Drupal\node\Entity\Node;

/**
 * Provides a Node Export function.
 */
class NodeExport {
  public static function nodeExport ($nid, &$context){
    $message = 'Exporting Nodes...';
    $results = array();
    // Loads a node of given id.
    $context['results'][] = "hello"; //\Drupal\node\Entity\Node::load($nid);
    $context['message'] = $message;
    $context['results'] = $results;
  }
  function nodeExportFinishedCallback($success, $results, $operations) {
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
        //drupal_set_message(t('The final result was "%final"', array('%final' => end($results))));

     print_r($results);
     die();
    // drupal_set_message($results);
  }  
}
