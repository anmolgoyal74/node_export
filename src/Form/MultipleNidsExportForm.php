<?php
/**
 * @file
 * Contains \Drupal\node_export\Form\MultipleNidsExportForm
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
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;

/**
 * Provides a Multiple Node Export form using Nids.
 */
class MultipleNidsExportForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'multiple_nids_export_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];
    $form['nids'] = [
      '#type' => 'textarea', 
      '#title' => 'Node Ids',
      '#cols' => 10,
      '#rows' => 10,
      '#description' => t('Enter the line separated node ids'),
      '#required' => TRUE,  
    ];
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Export'),
    );
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $nids = $form_state->getValue('nids');
    $array = [];
    $array = explode("\n",$nids);

    foreach ($array as $nid) {    
      $nid = trim($nid);
      if(!filter_var($nid, FILTER_VALIDATE_INT)) {
        $form_state->setErrorByName('NIDS', $this->t('Please enter valid list of Node Ids.  "'.$nid.'" is not in right format')); 
      }
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nid = $form_state->getValue('nids');
    $nids = [];
    $nids = explode("\n",$nid);
    
    $batch = array(
      'title' => t('Exporting Nodes...'),
      'operations' => [],
      'init_message'     => t('Commencing'),
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => '\Drupal\node_export\nodeExport::NodeExportFinishedCallback',
    );
    foreach ($nids as $nid) {
      $nidt=trim($nid);
      $batch['operations'][] = ['\Drupal\node_export\nodeExport::NodeExport',[$nidt]];
    }  

    batch_set($batch);
    drupal_set_message(t('Please copy the Export Code and paste in your other drupal site.'));
  }
}