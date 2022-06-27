<?php

namespace Drupal\tooltip_taxonomy\Form;

use Drupal\Component\Plugin\Factory\FactoryInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * FilterConditionForm class.
 *
 */

/**
 * Form handler for the FilterCondition entity add and edit forms.
 */
class FilterConditionForm extends EntityForm
{

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * Drupal condition plugin for content type.
   *
   * @var \Drupal\Core\Condition\ConditionPluginBase
   */
  protected $contentTypeCondition;


  /**
   * Constructs an FilterConditionForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   Entity type bundle info service instance.
   * @param \Drupal\Component\Plugin\Factory\FactoryInterface $plugin_factory
   *   Plugin factory service instance.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, EntityTypeBundleInfoInterface $entity_type_bundle_info, FactoryInterface $plugin_factory)
  {
    $this->entityTypeManager = $entityTypeManager;
    $this->entityTypeBundleInfo = $entity_type_bundle_info;

    $this->contentTypeCondition = $plugin_factory
      ->createInstance('node_type');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static($container->get('entity_type.manager'),
      $container->get('entity_type.bundle.info'),
      $container->get('plugin.manager.condition'));
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state)
  {
    $form = parent::form($form, $form_state);
    $condition = $this->entity;
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this
        ->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $condition
        ->label(),
      '#description' => $this
        ->t("Name for the filter condition."),
      '#required' => TRUE,
    ];
    $form['cid'] = [
      '#type' => 'machine_name',
      '#default_value' => $condition
        ->id(),
      '#machine_name' => [
        'exists' => [
          $this,
          'exist',
        ],
        'source' => [
          'name',
        ],
      ],
      '#disabled' => !$condition
        ->isNew(),
    ];

    // Vocabularies of taxonomy terms.
    $voca_options = [];
    foreach ($this->entityTypeBundleInfo
               ->getBundleInfo('taxonomy_term') as $voc_name => $voc_info) {
      $voca_options[$voc_name] = $voc_info['label'];
    }
    $form['vids'] = [
      '#type' => 'checkboxes',
      '#title' => $this
        ->t('Vocabularies for tooltips.'),
      '#options' => $voca_options,
      '#default_value' => $condition
        ->get('vids'),
      '#required' => TRUE,
      '#description' => $this
        ->t('Selected vocabularies will be used as the content text of tooltips in following pages or content types for a certain field. If none is checked, there will not be tooltip created.'),
    ];
    $formats = filter_formats();
    $format_options = [];
    foreach ($formats as $format) {
      $format_options[$format
        ->id()] = $format
        ->label();
    }
    $form['formats'] = [
      '#type' => 'checkboxes',
      '#title' => $this
        ->t('Text formats.'),
      '#options' => $format_options,
      '#default_value' => $condition
        ->get('formats'),
      '#required' => TRUE,
      '#description' => $this
        ->t('Select which formats does this condition applys to.'),
    ];

    // Content types applied to this condition.
    $this->contentTypeCondition
      ->setConfiguration($condition
        ->get('contentTypes'));

    // Build the form element for content type settings.
    $form['node'] = $this->contentTypeCondition
      ->buildConfigurationForm([], $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $condition = $this->entity;
    $this->contentTypeCondition
      ->submitConfigurationForm($form['node'], $form_state);

    $this->contentTypeCondition
      ->setConfig('negate', 0);
    foreach ($form_state
               ->getValues() as $key => $value) {
      if ($key === 'vids') {
        $vids = [];
        foreach ($value as $vid) {
          if (!empty($vid)) {
            $vids[] = $vid;
          }
        }

        // Vocabularies settings.
        $condition
          ->set('vids', $vids);
      } else {
        $condition
          ->set($key, $value);
      }
    }

    // Content type settings.
    $condition
      ->set('contentTypes', $this->contentTypeCondition
        ->getConfiguration());
    $status = $condition
      ->save();
    if ($status) {
      $this
        ->messenger()
        ->addMessage($this
          ->t('Saved the %label Example.', [
            '%label' => $condition
              ->label(),
          ]));
    } else {
      $this
        ->messenger()
        ->addMessage($this
          ->t('The %label Example was not saved.', [
            '%label' => $condition
              ->label(),
          ]), MessengerInterface::TYPE_ERROR);
    }

    // We need to invalidate all node pages by node_view tag
    // to apply the changes make here.
    \Drupal::service('cache_tags.invalidator')
      ->invalidateTags([
        'node_view',
      ]);
    $form_state
      ->setRedirect('entity.tooltip_taxonomy.config');
  }

  /**
   * Helper function to check whether an Example configuration entity exists.
   */
  public function exist($id)
  {
    $entity = $this->entityTypeManager
      ->getStorage('filter_condition')
      ->getQuery()
      ->condition('cid', $id)
      ->execute();
    return (bool)$entity;
  }


}
