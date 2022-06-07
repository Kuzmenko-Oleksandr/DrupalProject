<?php

namespace Drupal\blog_hero\Plugin\BlogHero\Entity;

use Drupal\blog_hero\Plugin\BlogHero\BlogHeroPluginInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Interface BlogHeroEntityPluginInterface for entity plugin type.
 *
 * @package Drupal\blog_hero\Plugin\BlogHero\Entity
 */
interface BlogHeroEntityPluginInterface extends BlogHeroPluginInterface {

  /**
   * Gets entity type id.
   *
   * The entity type id.
   *
   * @return string
   */
  public function getEntityType(): string;

  /**
   * An array with entity type bundles.
   *
   * Gets entity bundle.
   *
   * @return array
   */
  public function getEntityBundle(): array;

  /**
   * Gets current entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface|NULL
   *  The entity object.
   */
  public function getEntity(): EntityInterface|NULL;

}
