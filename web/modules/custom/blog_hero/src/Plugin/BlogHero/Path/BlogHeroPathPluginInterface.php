<?php


namespace Drupal\blog_hero\Plugin\BlogHero\Path;


use Drupal\blog_hero\Plugin\BlogHero\BlogHeroPluginInterface;

/**
 * Interface BlogHeroPathPluginInterface for path plugin type.
 */
interface BlogHeroPathPluginInterface extends BlogHeroPluginInterface {

  /**
   * An array with paths.
   *
   * Gets match paths.
   *
   * @return array
   */
  public function getMatchPath(): array;

  /**
   * The match type.
   *
   * Gets match type.
   *
   * @return string
   */
  public function getMatchType(): string;

}
