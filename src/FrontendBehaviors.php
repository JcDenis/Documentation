<?php

declare(strict_types=1);

namespace Dotclear\Plugin\Documentation;

use ArrayObject;
use Dotclear\App;
use Dotclear\Core\Frontend\{ Url, Utility };
use Dotclear\Helper\File\Path;

/**
 * @brief       Documentation module frontend behaviors.
 * @ingroup     Documentation
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */
class FrontendBehaviors
{
    /**
     * Check if current post is a documenation post and serve documentation template.
     *
     * @param   ArrayObject<string, mixed>  $params
     */
    public static function publicPostBeforeGetPosts(ArrayObject $params, ?string $args): void
    {
        // try to bypass plugin rosetta behavior FrontendBehaviors::findTranslatedEntry(),publicPostBeforeGetPosts
        if (!empty($params['post_type'])) {
            return;
        }

        $posts = App::blog()->getPosts($params);
        if (!$posts->isEmpty()
            && Core::isDocumentationCategory((int) $posts->f('cat_id'))
        ) {
            App::frontend()->context()->posts = $posts;
            self::serveTemplate('post');
            exit;
        }
    }

    /**
     * Check if current category is a documentation category and serve categories template.
     *
     * @param   ArrayObject<string, mixed>  $params
     */
    public static function publicCategoryBeforeGetCategories(ArrayObject $params, ?string $args): void
    {
        $categories = App::blog()->getCategories($params);
        if (!$categories->isEmpty()
            && Core::isDocumentationCategory((int) $categories->f('cat_id'))
        ) {
            App::frontend()->context()->categories = $categories;
            self::serveTemplate('category');
            exit;
        }
    }

    /**
     * Put selected post on first then by date on category page.
     *
     * @param   array<string, string>       $tpl
     * @param   ArrayObject<string, mixed>  $attr
     */
    public static function templatePrepareParams(array $tpl, ArrayObject $attr, string $content): string
    {
        if ($tpl['tag'] == 'Entries' 
            && $tpl['method'] == 'blog::getPosts'
            && in_array(App::url()->getType(), ['category'])
        ) {
            return 
                "if (". Core::class . "::isDocumentationCategory((int)App::frontend()->context()->categories->cat_id)){" .
                "\$params['order'] = 'post_selected DESC, post_url ASC' . (!empty(\$params['order']) ? ', ' . \$params['order'] : '');" .
                "}\n";
        }

        return '';
    }

    public static function publicHeadContent(): void
    {
        $tplset = App::themes()->moduleInfo(App::blog()->settings()->get('system')->get('theme'), 'tplset');
        if (in_array($tplset, ['dotty', 'mustek'])) {
            echo My::cssLoad('frontend-' . $tplset);
        }
    }

    /**
     * Serve template.
     */
    private static function serveTemplate(string $template): void
    {
        // use only dotty tplset
        $tplset = App::themes()->moduleInfo(App::blog()->settings()->get('system')->get('theme'), 'tplset');
        if ($tplset != 'dotty') { //if (!in_array($tplset, ['dotty', 'mustek'])) {
            Url::p404();
        }

        $default_template = Path::real(App::plugins()->moduleInfo(My::id(), 'root')) . DIRECTORY_SEPARATOR . Utility::TPL_ROOT . DIRECTORY_SEPARATOR;
        if (is_dir($default_template . $tplset)) {
            App::frontend()->template()->setPath(App::frontend()->template()->getPath(), $default_template . $tplset);
        }

        Url::serveDocument(My::id() . '-' . $template . '.html');
    }
}
