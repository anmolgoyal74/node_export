<?php
/**
 * @file
 * Contains \Drupal\node_export\Form\NodeImportForm
 */
namespace Drupal\node_Export\Form;

use Drupal\node\Plugin\views\argument_default;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node; 
use Drupal\Core\Entity;
use Drupal\Core\Access\AccessibleInterface;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a Node Import form.
 */
class NodeImportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'node_import_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form['paste'] = array(
    '#type' => 'textarea',
    '#default_value' => '',
    '#rows' => 15,
    '#description' => t('Paste the code of a node export here.'),
    '#wysiwyg' => FALSE,
  );
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Import'),
  );
    return $form;
  }

  /**
   * {@inheritdoc}
   */

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $contentTypes = \Drupal::service('entity.manager')->getStorage('node_type')->loadMultiple();
    $contentTypesList = [];    
    foreach ($contentTypes as $contentType) {
      $contentTypesList[$contentType->id()] =$contentType->id();// $contentType->label();
    }
    $json=$form_state->getValue('paste');
    $nodes=json_decode($json,true);
    
    foreach ($nodes as $node) {
      # code...
    if(in_array($node['type']['target_id'], $contentTypesList))
    {
      
    }
    else {
      $form_state->setErrorByName('Content Type', $this->t('The content type of the node you are trying to insert does not match any content type in your Drupal site'));  
    }
  }
}
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) { 
      $json=$form_state->getValue('paste');
      $nodes=json_decode($json,true);

      // foreach ($nodes as $node) {
      $batch = array(
      'title' => t('Importing Nodes...'),
      'operations' => [],
      'init_message'     => t('Imporitng'),
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => '\Drupal\node_export\nodeImport::NodeImportFinishedCallback',
    );
     foreach ($nodes as $node) {
      $batch['operations'][] = ['\Drupal\node_export\nodeImport::NodeImport',[$node]];
    }  
    batch_set($batch);

        // $nodenew = Node::create([
        //   'type'        => $node['type']['target_id'],
        //   'title'       => $node['title'],
        //   'body'        => $node['body'],
        //   'field_image' => $node['field_image'],
        //   'field_tags' => $node['field_tags'],
        //   'comment' => $node['comment'],
        // ]);
        // $nodenew->save();
      
      // print_r($node->title->value);
      // print_r($node->type->value);
      // $node->is_new = true;
      // unset($node->nid);
      // unset($node->vid);
      // unset($node->tnid);
      
     // node_save($node);

    drupal_set_message(t('Node has been imported succesfully.'));
  }
}