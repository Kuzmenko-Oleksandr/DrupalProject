<?php

namespace Drupal\blog_hero\Plugin;


use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Path\PathMatcher;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Factory\ContainerFactory;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\Container;

/**
 * BlogHero plugin manager.
 */
class BlogHeroPluginManager extends DefaultPluginManager {

  /**
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  protected CurrentPathStack $pathCurrent;

  /**
   * @var \Drupal\Core\Path\PathMatcher
   */
  protected PathMatcher $pathMather;

  /**
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected CurrentRouteMatch $routeMatcher;


  /**
   * BlogHeroPluginManager constructor.
   *
   * @param $type
   * @param \Traversable $namespaces
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   * @param \Drupal\Core\Path\CurrentPathStack $path_current
   * @param \Drupal\Core\Path\PathMatcher $path_matcher
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   */
  public function __construct(
    $type,
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler,
    CurrentPathStack $path_current,
    PathMatcher $path_matcher,
    CurrentRouteMatch $current_route_match,
  ) {
    $this->pathCurrent = $path_current;
    $this->pathMather = $path_matcher;
    $this->routeMatcher = $current_route_match;

    // E.g. entity => Entity, path => Path
    $typeCamelize = Container::camelize($type);
    $subdir = "Plugin/BlogHero/{$typeCamelize}";
    $plugin_interface = "Drupal\blog_hero\Plugin\BlogHero\{$typeCamelize}\BlogHero{$typeCamelize}PluginInterface";
    $plugin_definition_annotation_name = "Drupal\blog_hero\Annotation\BlogHero{$typeCamelize}";

    parent::__construct($subdir, $namespaces, $module_handler, $plugin_interface, $plugin_definition_annotation_name);

    $this->defaults += [
      'plugin_type' => $type,
      'enabled' => TRUE,
      'weight' => 0,
    ];

    if ($type === 'path') {
      $this->defaults += [
        'match_type' => 'listed',
      ];
    }

    // Call hook_blog_hero_TYPE_alter().
    $this->alterInfo("blog_hero{$type}");
    $this->setCacheBackend($cache_backend, "blog_hero:{$type}");
    $this->factory = new ContainerFactory($this->getDiscovery());
  }

  /**
   * Gets suitable plugins for current request.
   */
  public function getSuitablePlugins(): array {
    $plugin_type = $this->defaults['plugin_type'];

    if ($plugin_type == 'entity') {
      return $this->getSuitableEntityPlugins();
    }

    if ($plugin_type == 'path') {
      return $this->getSuitablePathPlugins();
    }
  }

  /**
   * Gets plugins entity suitable current request.
   */
  protected function getSuitableEntityPlugins(): array {
    $plugins = [];

    $entity = NULL;
    foreach ($this->routeMatcher->getParameters() as $parameter) {
      if ($parameter instanceof EntityInterface) {
        $entity = $parameter;
        break;
      }
    }

    if ($entity) {
      foreach ($this->getDefinitions() as $plugin_id => $plugin) {
        if ($plugin['enabled']) {
          $same_entity_type = $plugin['entity_type'] === $entity->getEntityTypeId();
          $needed_bundle = in_array($entity->bundle(), $plugin['entity_bundle']) || in_array('*', $plugin['entity_bundle']);

          if ($same_entity_type && $needed_bundle) {
            $plugins[$plugin_id] = $plugin;
            $plugins[$plugin_id]['entity'] = $entity;
          }
        }
      }
    }

    uasort($plugins, '\Drupal\Component\Utility\SortArray::sortByWeightElement');

    return $plugins;
  }

  /**
   * Gets plugins path suitable current request.
   */
  protected function getSuitablePathPlugins(): array {
    $plugins = [];

    foreach ($this->getDefinitions() as $plugin_id => $plugin) {
      if ($plugin['enabled']) {
        $patterns = implode(PHP_EOL, $plugin['match_path']);
        $current_path = $this->pathCurrent->getPath();
        $is_match_path = $this->pathMather->matchPath($current_path, $patterns);

        $match_type = match ($plugin['match_path']) {
          default => 0,
          'unlisted' => 1,
        };

        $is_plugin_needed = ($is_match_path xor $match_type);

        if ($is_plugin_needed) {
          $plugins[$plugin_id] = $plugin;
        }
      }
    }

    uasort($plugins, '\Drupal\Component\Utility\SortArray::sortByWeightElement');

    return $plugins;
  }

}
