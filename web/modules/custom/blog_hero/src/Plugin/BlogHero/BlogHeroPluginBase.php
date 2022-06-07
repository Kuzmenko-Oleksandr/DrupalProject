<?php


namespace Drupal\blog_hero\Plugin\BlogHero;


use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Controller\TitleResolverInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The base for all BlogHero plugins.
 */
abstract class BlogHeroPluginBase extends PluginBase implements BlogHeroPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected Request $request;

  /**
   * The current route.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected CurrentRouteMatch $routeMatch;

  /**
   * The title resolver.
   *
   * @var array|string|null
   */
  protected null|array|string $titleResolver;

  /**
   * The Entity type manger.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * BlogHeroPluginBase constructor.
   *
   * @param array $configuration
   * The configuration.
   * @param $plugin_id
   * The plugin ID.
   * @param $plugin_definition
   * The plugin definition.
   * @param \Symfony\Component\HttpFoundation\Request $request
   * The current request.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   * The current route.
   * @param \Drupal\Core\Controller\TitleResolverInterface $title_resolver
   * The title resolver.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * The Entity type manger.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    Request $request,
    CurrentRouteMatch $current_route_match,
    TitleResolverInterface $title_resolver,
    EntityTypeManagerInterface $entity_type_manager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->request = $request;
    $this->routeMatch = $current_route_match;
    $this->titleResolver = $title_resolver->getTitle($this->request, $this->routeMatch->getRouteObject());
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('current_route_match'),
      $container->get('title_resolver'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * The current request.
   *
   * Gets current request.
   *
   * @return \Symfony\Component\HttpFoundation\Request
   */
  public function getRequest(): Request {
    return $this->request;
  }

  /**
   * Gets current route.
   *
   * @return \Drupal\Core\Routing\CurrentRouteMatch
   * The current route.
   */
  public function getRouteMatch(): CurrentRouteMatch {
    return $this->routeMatch;
  }

  /**
   * Gets current Title.
   *
   * @return array|string|null
   * The current resolver.
   */
  public function getPageTitle(): array|string|null {
    return $this->titleResolver;
  }

  /**
   * Gets entity type manger.
   *
   * @return \Drupal\Core\Entity\EntityTypeManagerInterface
   * The Entity type manager.
   */
  public function getEntityTypeManger(): EntityTypeManagerInterface {
    return $this->entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getEnabled(): bool {
    return $this->pluginDefinition['enabled'];
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight(): int {
    return $this->pluginDefinition['weight'];
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroTitle(): string|array|null {
    return $this->getPageTitle();
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroSubtitle(): string {
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroImage(): string {
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getHeroVideo(): array {
    return [];
  }

}
