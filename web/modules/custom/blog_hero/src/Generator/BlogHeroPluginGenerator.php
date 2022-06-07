<?php

namespace Drupal\blog_hero\Generator;

use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\Utils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class BlogHeroPluginGenerator.
 */
class BlogHeroPluginGenerator extends BaseGenerator {

  /**
   * {@inheritdoc}
   */
  protected $name = 'plugin-blog-hero';

  /**
   * {@inheritdoc}
   */
  protected $alias = 'bh';

  /**
   * {@inheritdoc}
   */
  protected $description = 'Generates BlogHero plugin.';

  /**
   * {@inheritdoc}
   */
  protected $templatePath = __DIR__ . '/templates';

  /**
   * The Entity Manager;
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entity_type_manager, string $name = NULL) {
    parent::__construct($name);

    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function interact(InputInterface $input, OutputInterface $output) {
    $questions = Utils::pluginQuestions() + Utils::moduleQuestions();
    $this->askForPluginType($questions);

    $questions['is_title'] = new ConfirmationQuestion(t('Do you want to customize title?'), FALSE);
    $questions['is_subtitle'] = new ConfirmationQuestion(t('Do you want to add subtitle?'), FALSE);
    $questions['is_image'] = new ConfirmationQuestion(t('Do you want to specify image?'), FALSE);
    $questions['is_video'] = new ConfirmationQuestion(t('Do you want to specify video?'), FALSE);

    $vars = &$this->collectVars($input, $output, $questions);
    $vars['name'] = Utils::camelize($vars['plugin_id']);
    $vars['type'] = Utils::camelize($vars['blog_hero_plugin_type']);
    $vars['twig_template'] = 'blog-hero-' . $vars['blog_hero_plugin_type'] . '-plugin.html.twig';

    //Additional questions.
    if ($vars['blog_hero_plugin_type'] === 'path') {
      $questions['match_type'] = new ChoiceQuestion(t('Match type for path?'), [
        'listed' => t('Only for listed page'),
        'unlisted' => t('Only for listed page'),
      ], 'listed');
    }

    if ($vars['blog_hero_plugin_type'] === 'entity') {
      $entity_types = [];
      foreach ($this->entityTypeManager->getDefinitions() as $entity_type_id => $entity_type) {
        if ($entity_type instanceof ContentEntityType) {
          $entity_types[$entity_type_id] = $entity_type->getLabel();
        }
      }
      $questions['entity_type'] = new ChoiceQuestion(t('Entity type'), $entity_types);
    }

    $vars = &$this->collectVars($input, $output, $questions, $vars);

    $this->addFile()
      ->path('src/Plugin/BlogHero/{type}/{name}.php')
      ->template($vars['twig_template']);
  }

  /**
   * Ask for preferred plugin type.
   *
   * @param $question
   */
  public function askForPluginType(&$question) {
    $blog_hero_plugin_types = [
      'path' => 'BlogHero Path plugin',
      'entity' => 'BlogHero Entity plugin',
    ];

    $question['blog_hero_plugin_type'] = new ChoiceQuestion(
      t('What plugin type do you want to create?'),
      $blog_hero_plugin_types
    );
  }

}
