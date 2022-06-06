<?php
//        ..／＞...フ
//　　　　　| 　_　 _|
//　 　　　／`ミ _x 彡
//　　 　 /　　　 　 |
//　　　 /　 ヽ　　 ﾉ
//　／￣|　　 |　|　|
//　| (￣ヽ＿_ヽ_)_)
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
 * Class GalleryBehavior
 *
 * @ParagraphsBehavior(
 *   id = "blog_paragraphs_gallery",
 *   label = @Translation("Gallery Settings"),
 *   description = @Translation("Settings for gallery paragraph type."),
 *   weight = 0,
 * )
 */
class GalleryBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type): bool {
    return $paragraphs_type->id() === 'gallery';
  }

  /**
   * Extends the paragraph render array with behavior;
   *
   * @param array $build
   * @param \Drupal\paragraphs\Entity\Paragraph $paragraph
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   * @param $view_mode
   *
   * @return array
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    $image_per_row = $paragraph->getBehaviorSetting($this->getPluginId(), 'items_per_row', 4);
    $bem_block = 'paragraph-' . $paragraph->bundle() . ($view_mode === 'default' ? '' : '-' . $view_mode);
    $build['#attributes']['class'][] = Html::getClass($bem_block . '--images-per-row-' . $image_per_row);

    if (isset($build['field_image']['#formatter']) && $build['field_image']['#formatter'] === 'photoswipe_field_formatter') {
      $image_style = match ($image_per_row) {
        3 => 'paragraph_gallery_image_3_of_12',
        2 => 'paragraph_gallery_image_2_of_12',
        default => 'paragraph_gallery_image_4_of_12',
      };

      for ($i = 0; $i < count($build['field_image']['#items']); $i++) {
        $build['field_image'][$i]['#display_settings']['photoswipe_node_style'] = $image_style;
      }

      $build['field_image'][0]['#image_style'] = $image_style;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state): array {
    $form['items_per_row'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of images per row:'),
      '#options' => [
        '2' => $this->formatPlural(2, '(1) photo per row', '@count photos per row'),
        '3' => $this->formatPlural(3, '(1) photo per row', '@count photos per row'),
        '4' => $this->formatPlural(4, '(1) photo per row', '@count photos per row'),
      ],
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'items_per_row', 4),
    ];

    return $form;
  }

}
