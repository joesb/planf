<?php
/**
 * @file
 * Contains \Drupal\plf_client\Entity\ClientEntity.
 */

namespace Drupal\plf_client\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\plf_client\ClientInterface;
use Drupal\user\UserInterface;

/**
 * Class Client
 * @package Drupal\plf_client\Entity
 *
 * @ContentEntityType(
 *   id = "plf_client",
 *   label = @Translation("Client entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\plf_client\Form\ClientForm",
 *       "edit" = "Drupal\plf_client\Form\ClientForm",
 *       "delete" = "Drupal\plf_client\Form\ClientDeleteForm",
 *     },
 *     "access" = "Drupal\plf_client\ClientAccessControlHandler",
 *   },
 *   base_table = "client",
 *   admin_permission = "administer clients",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/client/{plf_client}",
 *     "edit-form" = "/client/{plf_client}/edit",
 *     "delete-form" = "/client/{plf_client}/delete",
 *     "collection" = "/client"
 *   },
 *   field_ui_base_route = "plf_client.client_settings",
 * )
 */
class Client extends ContentEntityBase implements ClientInterface {

  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setChangedTime($timestamp) {
    $this->set('changed', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTimeAcrossTranslations()  {
    $changed = $this->getUntranslated()->getChangedTime();
    foreach ($this->getTranslationLanguages(FALSE) as $language)    {
      $translation_changed = $this->getTranslation($language->getId())->getChangedTime();
      $changed = max($translation_changed, $changed);
    }
    return $changed;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Contact entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Contact entity.'))
      ->setReadOnly(TRUE);

    // Name field for the contact.
    // We set display options for the view as well as the form.
    // Users with correct privileges can change the view and edit configuration.

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the client.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

//    $fields['first_name'] = BaseFieldDefinition::create('string')
//      ->setLabel(t('First Name'))
//      ->setDescription(t('The first name of the Contact entity.'))
//      ->setSettings(array(
//        'default_value' => '',
//        'max_length' => 255,
//        'text_processing' => 0,
//      ))
//      ->setDisplayOptions('view', array(
//        'label' => 'above',
//        'type' => 'string',
//        'weight' => -5,
//      ))
//      ->setDisplayOptions('form', array(
//        'type' => 'string',
//        'weight' => -5,
//      ))
//      ->setDisplayConfigurable('form', TRUE)
//      ->setDisplayConfigurable('view', TRUE);

    // Gender field for the contact.
    // ListTextType with a drop down menu widget.
    // The values shown in the menu are 'male' and 'female'.
    // In the view the field content is shown as string.
    // In the form the choices are presented as options list.
//    $fields['gender'] = BaseFieldDefinition::create('list_string')
//      ->setLabel(t('Gender'))
//      ->setDescription(t('The gender of the Contact entity.'))
//      ->setSettings(array(
//        'allowed_values' => array(
//          'female' => 'female',
//          'male' => 'male',
//        ),
//      ))
//      ->setDisplayOptions('view', array(
//        'label' => 'above',
//        'type' => 'string',
//        'weight' => -4,
//      ))
//      ->setDisplayOptions('form', array(
//        'type' => 'options_select',
//        'weight' => -4,
//      ))
//      ->setDisplayConfigurable('form', TRUE)
//      ->setDisplayConfigurable('view', TRUE);

    // Owner field of the contact.
    // Entity reference field, holds the reference to the user object.
    // The view shows the user name field of the user.
    // The form presents a auto complete field for the user name.
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Name'))
      ->setDescription(t('The Name of the associated user.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'entity_reference',
        'weight' => -3,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
        'weight' => -3,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of ContentEntityExample entity.'));
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }
}