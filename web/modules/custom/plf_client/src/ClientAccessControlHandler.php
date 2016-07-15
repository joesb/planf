<?php
/**
 * @file \Drupal\plf_client\ClientAccessControlHandler.
 */

namespace Drupal\plf_client;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;


/**
 * Access controller for the client entity.
 */
class ClientAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   *
   * Link the activities to the permissions. checkAccess is called with the
   * $operation as defined in the routing.yml file.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermissions($account, ['view clients', 'administer clients'], 'OR');

      case 'edit':
        return AccessResult::allowedIfHasPermissions($account, ['edit clients', 'administer clients'], 'OR');

      case 'delete':
        return AccessResult::allowedIfHasPermissions($account, ['delete clients', 'administer clients'], 'OR');
    }
    return AccessResult::forbidden();
  }

  /**
   * {@inheritdoc}
   *
   * Separate from the checkAccess because the entity does not yet exist, it
   * will be created during the 'add' process.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add client entity');
  }

}