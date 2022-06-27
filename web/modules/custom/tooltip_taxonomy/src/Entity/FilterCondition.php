<?php

namespace Drupal\tooltip_taxonomy\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\Annotation\ConfigEntityType;

/**
 * Defines the FilterCondition entity.
 *
 * @ConfigEntityType(
 *   id = "filter_condition",
 *   label = @Translation("Filter condition"),
 *   handlers = {
 *     "list_builder" = "Drupal\tooltip_taxonomy\Controller\FilterConditionListBuilder",
 *     "form" = {
 *       "add" = "Drupal\tooltip_taxonomy\Form\FilterConditionForm",
 *       "edit" = "Drupal\tooltip_taxonomy\Form\FilterConditionForm",
 *       "delete" = "Drupal\tooltip_taxonomy\Form\FilterConditionDeleteForm",
 *     }
 *   },
 *   config_prefix = "filter_condition",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "cid",
 *     "label" = "name",
 *     "weight" = "weight",
 *   },
 *   config_export = {
 *     "cid",
 *     "name",
 *     "weight",
 *     "vids",
 *     "contentTypes",
 *     "formats",
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/content/tooltip_taxonomy/{filter_condition}",
 *     "delete-form" = "/admin/config/content/tooltip_taxonomy/{filter_condition}/delete",
 *   }
 * )
 */
class FilterCondition extends ConfigEntityBase implements FilterConditionInterface
{

  /**
   * The filter condition ID.
   *
   * @var string
   */
  public $cid;

  /**
   * The filter condition name.
   *
   * @var string
   */
  public $name;

  /**
   * The weight of the condition.
   *
   * @var int
   */
  protected $weight = 0;

  /**
   * The taxonomies IDs.
   *
   * @var array
   */
  protected $vids = [];

  /**
   * The content types applied to this condition.
   *
   * @var array
   */
  protected $contentTypes = [];

  /**
   * The text format that the condition applied to.
   *
   * @var array
   */
  protected $formats = [];

  /**
   * Get the entity id.
   *
   * @see \Drupal\Core\Entity\Entity::id()
   */
  public function id()
  {
    return $this->cid;
  }

  /**
   * Get the entity label.
   *
   * @see \Drupal\Core\Entity\Entity::label()
   */
  public function label()
  {
    return $this->name;
  }

}
