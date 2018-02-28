<?php
/**
 * @file
 * Contains \Drupal\node_export\Form\MultipleNodeExportConfirm
 */
namespace Drupal\node_Export\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity;
use Drupal\node\Entity\Node; 
use Drupal\Core\Access\AccessibleInterface;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Plugin\views\argument_default;

/**
 * Provides a Node Export form.
 */
class MultipleNodeExportConfirm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'multiple_node_export_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $content_type = \Drupal::routeMatch()->getParameter('content_type');
    $nids = \Drupal::entityQuery('node')->condition('type',$content_type)->execute();

    $batch = array(
      'title' => t('Generating Export Code...'),
      'operations' => [],
      'init_message'     => t('Exporting '),
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => '\Drupal\node_export\nodeExport::NodeExportFinishedCallback',
    );
     foreach ($nids as $nid) {
      $batch['operations'][] = ['\Drupal\node_export\nodeExport::NodeExport',[$nid]];
    }  

    batch_set($batch);

    // $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
    
    $count=0;
    $result=array();
    foreach ($nodes as $node) {
      foreach ($node as $key=>$value) {
        $result[$count][$key]=$node->get($key)->getValue()[0];
      }
      $count++;
    }    
     $json=json_encode($result);
    //  print_r($json);
    // die();
    $form['export_code'] = [
      '#type' => 'textarea',
      '#value' => $json,
      '#title' => t('Node Export Code is :'),
      '#rows' => '25',
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Download'),
    );
    $form['nid'] = array(
      '#type' => 'hidden',
      '#value' => $nid,
    );
    
    return $form;
  }



  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
  
    
    drupal_set_message(t('Node Content Type has been changed succesfully.'));
  }
}