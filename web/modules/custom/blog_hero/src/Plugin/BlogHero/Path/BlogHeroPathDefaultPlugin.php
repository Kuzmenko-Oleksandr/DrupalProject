<?php

namespace Drupal\blog_hero\Plugin\BlogHero\Path;

use Drupal\blog_hero\Annotation\BlogHeroPath;

/**
 * Default plugin which will be used if non of others met their requirements.
 *
 * @BlogHeroPath(
 *   id = "blog_hero_path_default",
 *   match_path = {"*"},
 *   weight = -100,
 * )
 */
class BlogHeroPathDefaultPlugin extends BlogHeroPathPluginBase {

}
