<?php
/**
 * Created by PhpStorm.
 * User: joebaker
 * Date: 22/04/2016
 * Time: 12:05
 */

namespace Drupal\plf_client;

// extends SqlContentEntityStorage implements CommentStorageInterface
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

class ClientStorage extends SqlContentEntityStorage implements ClientStorageInterface {

}