<?php

namespace Drupal\node_export;
use Drupal\node\Entity\Node;

class nodeImport {
  public static function NodeImport ($node, &$context){
    $message = 'Importing Nodes...';
    $results = array();
    //die();
    $nodenew = Node::create([
          'type'        => $node['type']['target_id'],
          'title'       => $node['title'],
          'body'        => $node['body'],
          'field_image' => $node['field_image'],
          'field_tags' => $node['field_tags'],
          'comment' => $node['comment'],
        ]);
        $nodenew->save();
    $context['message'] = $message;
    $context['results'] = $results;
  }
  function NodeImportFinishedCallback($success, $results, $operations) {
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
    drupal_set_message($message);
  }
  
}
