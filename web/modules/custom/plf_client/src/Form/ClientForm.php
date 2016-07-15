<?php
/**
 * @file
 * Contains \Drupal\plf_client\Entity\ClientForm.
 */

namespace Drupal\plf_client\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the plf_client entity edit forms.
 *
 * @ingroup plf_client
 */
class ClientForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\plf_client\Entity\Client */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['langcode'] = array(
      '#title' => $this->t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->getId(),
      '#languages' => Language::STATE_ALL,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $client = $this->getEntity();
//    $form_state->setRedirect('entity.plf_client.collection');
    if ($client->access('view')) {
      $form_state->setRedirect(
        'entity.plf_client.canonical',
        array('plf_client' => $client->id())
      );
    }
    else {
      $form_state->setRedirect('entity.plf_client.collection');
    }
    $client->save();
  }

}
