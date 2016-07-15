<?php
/**
 * @file
 * Contains \Drupal\plf_client\ClientInterface.
 */

namespace Drupal\plf_client;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Client entity.
 * @ingroup content_entity_example
 */
interface ClientInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Returns the name of the client.
   *
   * @return string
   *   The name of the client.
   */
  public function getName();

}
