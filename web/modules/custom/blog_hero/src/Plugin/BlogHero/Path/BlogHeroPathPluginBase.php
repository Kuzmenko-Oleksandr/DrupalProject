<?php

namespace Drupal\blog_hero\Plugin\BlogHero\Path;

use Drupal\blog_hero\Plugin\BlogHero\BlogHeroPluginBase;

/**
 * The base for BlogHero path plugin.
 */
abstract class BlogHeroPathPluginBase extends BlogHeroPluginBase implements BlogHeroPathPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getMatchPath(): array {
    return $this->pluginDefinition['match_path'];
  }

  /**
   * {@inheritdoc}
   */
  public function getMatchType(): string {
    return $this->pluginDefinition['match_type'];
  }

}
