<?php

namespace Drupal\blog_paragraphs\Plugin\paragraphs\Behavior;

use Drupal\Component\Utility\Html;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Annotation\ParagraphsBehavior;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * @ParagraphsBehavior(
 *   id = "blog_paragraphs_paragraph_title_and_text",
 *   label = @Translation("Paragraph title and text settings"),
 *   description= @Translation("Allows to configure paragraph title behavior."),
 *   weight = 0,
 * )
 */
class ParagraphTitleAndTextBehavior extends ParagraphsBehaviorBase
{

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type)
  {
    return TRUE;
  }

  /**
   * Extends the paragraph render array with behavior.
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode)
  {

  }

  /**
   * {@inheritdoc}
   */
  public function preprocess(&$variables)
  {
    /** @var \Drupal\paragraphs\ParagraphInterface $paragraph */
    $paragraph = $variables['paragraph'];
    $variables['title_wrapper' || 'text_wrapper'] = $paragraph->getBehaviorSetting($this->getPluginId(), 'title_wrapper' || 'text_wrapper', 'h2');
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state)
  {

    if ($paragraph->hasField('field_title')) {
      $form['title_wrapper'] = [
        '#type' => 'select',
        '#title' => $this->t('Title wrapper element'),
        '#options' => $this->getHeadingOptions(),
        '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'title_wrapper', 'h2'),
      ];
    } else if ($paragraph->hasField('field_text')) {
      $form['text_wrapper'] = [
        '#type' => 'select',
        '#title' => $this->t('Text wrapper element'),
        '#options' => $this->getHeadingOptions(),
        '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'text_wrapper', 'h2'),
      ];
    }

    return $form;
  }

  /**
   * Defines list of available options for title element.
   */
  public function getHeadingOptions()
  {
    return [
      'h2' => 'h2',
      'h3' => 'h3',
      'h4' => 'h4',
      'div' => 'div',
    ];
  }

}
