<?php

namespace Drupal\blog_hero\Plugin\BlogHero\Entity;

use Drupal\blog_hero\Plugin\BlogHero\BlogHeroPluginBase;
use Drupal\Core\Entity\EntityInterface;

/**
 * The base for BlogHero entity plugin type.
 */
abstract class BlogHeroEntityPluginBase extends BlogHeroPluginBase implements BlogHeroEntityPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getEntityType(): string {
    return $this->pluginDefinition['entity_type'];
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityBundle(): array {
    return $this->pluginDefinition['entity_bundle'];
  }

  /**
   * {@inheritdoc}
   */
  public function getEntity(): EntityInterface|NULL {
    return $this->configuration['entity'];
  }

}
