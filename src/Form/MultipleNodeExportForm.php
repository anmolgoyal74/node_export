<?php
/**
 * @file
 * Contains \Drupal\node_export\Form\MultipleNodeExportForm
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
 * Provides a Node Export form.
 */
class MultipleNodeExportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'multiple_node_export_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // loads all the content typess in the drupal site
    $contentTypes = \Drupal::service('entity.manager')->getStorage('node_type')->loadMultiple();
    $contentTypesList = [];
    
    foreach ($contentTypes as $contentType) {
      $contentTypesList[$contentType->id()] = $contentType->label();
    }

    $form['ct'] = array(
      '#markup' => t('Select the content type of the node you want to export : '),
    );
    $form['export_type'] = [
      '#type' => 'select',
      '#title' => t('Select Type'),
      '#options' => $contentTypesList,
    ];
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Export'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $export_type = $form_state->getValue('export_type');

    //$content_type = \Drupal::routeMatch()->getParameter('content_type');
    // loads all the node of selected content type
    $nids = \Drupal::entityQuery('node')->condition('type',$export_type)->execute();
    $batch = array(
      'title' => t('Generating Export Code...'),
      'operations' => [],
      'init_message'     => t('Exporting '),
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => '\Drupal\node_export\NodeExport::nodeExportFinishedCallback',
    );
     foreach ($nids as $nid) {
      $batch['operations'][] = ['\Drupal\node_export\NodeExport::nodeExport',[$nid]];
    }  
    batch_set($batch);  
    drupal_set_message(t('Please copy the Export Code and paste in your other drupal site.'));
  }
}