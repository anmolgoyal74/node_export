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
    // Reads the Node id from URL
    $nid = \Drupal::routeMatch()->getParameter('node');
    // Load the node
    $node = \Drupal\node\Entity\Node::load($nid);
    $result=array();
    $count=0;
    foreach ($node as $key=>$value) {
      $result[$count][$key]=$node->get($key)->getValue()[0];
    }

    $json=json_encode($result);
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
    drupal_set_message(t('Node has been expoted'));
  }
}