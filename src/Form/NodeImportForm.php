<?php
/**
 * @file
 * Contains \Drupal\node_export\Form\NodeImportForm
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
    '#rows' => 30,
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
  public function submitForm(array &$form, FormStateInterface $form_state) { 
      $node=$form_state->getValue('paste');

      //$new = clone($node);
      $node->is_new = true;
      die();
      unset($node->nid);
      unset($node->vid);
      unset($node->tnid);
      
      node_save($node);

    drupal_set_message(t('Node Content Type has been changed succesfully.'));
  }
}