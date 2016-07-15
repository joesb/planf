<?php
/**
 * @file
 * Contains \Drupal\plf_client\Entity\Controller\ClientListBuilder.
 */

namespace Drupal\plf_client\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;

/**
 * Provides a list controller for plf_client entity.
 *
 * @ingroup plf_client
 */
class ClientListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   *
   * We override ::render() so that we can add our own content above the table.
   * parent::render() is where EntityListBuilder creates the table using our
   * buildHeader() and buildRow() implementations.
   */
  public function render() {
    $build['description'] = array(
      '#markup' => $this->t('PlanF Client implements a Clients model. These clients are fieldable entities. You can manage the fields on the <a href="@adminlink">Clients admin page</a>.', array(
        '@adminlink' => \Drupal::urlGenerator()->generateFromRoute('plf_client.client_settings'),
      )),
    );
    $build['table'] = parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the client list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['id'] = $this->t('ClientID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\plf_client\Entity\Client */
    $row['id'] = $entity->id();
    $row['name'] = $entity->link();
//    $row['first_name'] = $entity->first_name->value;
//    $row['gender'] = $entity->gender->value;
    return $row + parent::buildRow($entity);
  }

}