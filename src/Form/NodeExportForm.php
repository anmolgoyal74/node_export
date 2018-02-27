<?php
/**
 * @file
 * Contains \Drupal\node_export\Form\NodeExportForm
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

/**
 * Provides a Node Export form.
 */
class NodeExportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'node_export_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $nid = \Drupal::routeMatch()->getParameter('node');
    //$nid = $node->nid->value;


    $node = \Drupal\node\Entity\Node::load($nid);
    // print_r($node);
    // die();
    $result=array();
    foreach ($node as $key=>$value) {
      $result[$key]=$node->get($key)->getValue()[0];

      // if($key=='body')
      // {
      //   $result['format']=$node->get($key)->getValue()[0]['format'];
      //   $result['body']=$node->get($key)->getValue()[0]['value'];
      // }
      // else if($key=='type')
      // {
      // $result[$key]=$node->get($key)->getValue()[0]['target_id'];
      // }
      // else if($key=='field_image')
      // {
      // $result[$key]=$node->get($key)->getValue()[0];
      // }
      // else
      // $result[$key]=$node->get($key)->getValue()[0]['value'];
    }

     $json=json_encode($result);
     //  print_r($json);
     // die();

    // ob_start();
    // var_dump($node);
    // $result = ob_get_clean();
    //print_r($result);
    //die();
    $form['export_code'] = [
      '#type' => 'textarea',
      '#value' => $json,
      '#title' => t('Node Export Code is :'),
      '#rows' => '15',
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