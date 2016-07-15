<?php

namespace Drupal\plf_client;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;

class ClientViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    parent::alterBuild($build, $entity, $display, $view_mode);
    $build['#contextual_links']['plf_client'] = array(
      'route_parameters' => array('plf_client' => $entity->id()),
      'metadata' => array('changed' => $entity->getChangedTime()),
    );
  }

}