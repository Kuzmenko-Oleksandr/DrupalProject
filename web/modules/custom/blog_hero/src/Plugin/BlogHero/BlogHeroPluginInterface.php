<?php

namespace Drupal\blog_hero\Plugin\BlogHero;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Common interface for all BlogHero plugin types.
 */
interface BlogHeroPluginInterface extends PluginInspectionInterface {

  /**
   * The plugin status.
   * Gets plugin status.
   *
   * @return bool
   */
  public function getEnabled(): bool;

  /**
   * The plugin weight.
   * Gets plugin weight.
   *
   * @return int
   */
  public function getWeight(): int;


  /**
   * The title.
   * Gets hero title.
   *
   * @return string|array|null
   */
  public function getHeroTitle(): string|array|null;

  /**
   * The subtitle.
   * Gets hero subtitle.
   *
   * @return string
   */
  public function getHeroSubtitle(): string;

  /**
   * The URI of image.
   * Gets hero image URI.
   *
   * @return string
   */
  public function getHeroImage(): string;

  /**
   * Gets hero video URI.
   * An array with videos URI's.
   *
   * Keys of array is represents their type and value is file URI.
   *
   * @code
   * return [
   *  'video/mp4' => 'big-buck-bunny.mp4'
   *  'video/ogg' => 'big-buck-bunny.ogg'
   *  'video/webm' => 'big-buck-bunny.webm'
   * ];
   * @endcode
   *
   * @return array
   */
  public function getHeroVideo(): array;

}
