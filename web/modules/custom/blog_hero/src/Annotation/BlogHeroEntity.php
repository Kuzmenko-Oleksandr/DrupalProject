<?php

namespace Drupal\blog_hero\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Class BlogHeroEntity annotation.
 *
 * @Annotation
 */
class BlogHeroEntity extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public string $id;

  /**
   * The plugin status.
   *
   * By default all plugins are enabled and this value set in TRUE.
   * You can set it to FALSE, to temporary disable plugin.
   *
   * @var bool
   */
  public bool $enabled;

  /**
   * The entity type id.
   *
   * @var string
   */
  public string $entity_type;

  /**
   * The entity bundle.
   *
   * An array of bundles from entity_type on which pages this plugin will be an available.
   * Supports for wildcard (*) to match all entity type bundles.
   *
   * E.g. {"news", "page"}
   *
   * @var array
   */
  public array $entity_bundle;

  /**
   * The weight of plugin.
   *
   * Plugin with higher with, will be used.
   *
   * @var int
   */
  public int $weight;

}
