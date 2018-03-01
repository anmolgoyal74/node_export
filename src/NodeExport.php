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
    $node = \Drupal\node\Entity\Node::load($nid);
    $context['results'][] = $node; 
    $context['message'] = $message;
  }
  function nodeExportFinishedCallback($success, $results, $operations){
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One node exported.', '@count nodes exported.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }  
    $json=json_encode($results);
    $result=array();
    $count=0;
    foreach ($results as $node) {
      foreach ($node as $key=>$value) {
        $result[$count][$key]=$node->get($key)->getValue()[0];
      }
      $count++;
    }    
    $json=json_encode($result);
    $_SESSION['json']=$json;
    // Download the node string as json file.
    header('Content-type: application/json');
    header('Content-Disposition: attachment;filename="node_string.json"');
    header('Pragma: no-cache');
    print_r($json);
    // This part has to be sorted.redirect call after batch completion is printed in the json file.
    die(); 
  }  
}
