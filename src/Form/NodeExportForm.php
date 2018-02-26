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
    print_r($node);
    die();
    ob_start();
    var_dump($node);
    $result = ob_get_clean();
  
    $form['export_code'] = [
      '#type' => 'textarea',
      '#value' => $result,
      '#title' => t('Node Export Code is :'),
      '#cols' => '50',
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