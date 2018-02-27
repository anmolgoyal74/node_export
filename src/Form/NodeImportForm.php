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
  public function submitForm(array &$form, FormStateInterface $form_state) { 
      $json=$form_state->getValue('paste');

      $node=json_decode($json,true);
  //     $node2 = Entity::create('node', array(
  //        'type' => 'new_content_type',
  //        'title' =>'Creating another node',
  //        'body' => array(
  //          'value' =>'The body of the content',
  //          'format' => 'full_html',
  //            ),
  //        'field_mail'=>'email@gmail.com',
  //        'field_link'=>'http://www.example.com',
  //        'field_date'=>[ '2017-07-22'],
  //        )
  // );
      $nodenew = Node::create([
        'type'        => $node['type']['target_id'],
        'title'       => $node['title'],
        'body'        => $node['body'],
        'field_image' => $node['field_image'],
        'field_tags' => $node['field_tags'],
        'comment' => $node['comment'],
      ]);
      $nodenew->save();
      // print_r($node->title->value);
      // print_r($node->type->value);
      // $node->is_new = true;
      // unset($node->nid);
      // unset($node->vid);
      // unset($node->tnid);
      
     // node_save($node);

    drupal_set_message(t('Node Content Type has been changed succesfully.'));
  }
}