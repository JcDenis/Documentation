<?php

declare(strict_types=1);

namespace Dotclear\Plugin\Documentation;

use ArrayObject;
use Dotclear\App;
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
    private static bool $loop = false;

    /**
     * Serve custom template if post or category is a documentation.
     */
    public static function urlHandlerBeforeGetData(): void
    {
        if (!self::$loop) {
            foreach(['posts', 'categories'] as $ctx) {
                if (App::frontend()->context()->exists($ctx) 
                    && Core::isDocumentationCategory((int) App::frontend()->context()->__get($ctx)->f('cat_id'))
                ) {
                    self::$loop = true;
                    self::serveTemplate(App::frontend()->context()->current_tpl);
                    exit();
                }
            }
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

    /**
     * Add CSS.
     */
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
            App::url()::p404();
        }

        $default_template = Path::real(App::plugins()->moduleInfo(My::id(), 'root')) . DIRECTORY_SEPARATOR . App::frontend()::TPL_ROOT . DIRECTORY_SEPARATOR;
        if (is_dir($default_template . $tplset)) {
            App::frontend()->template()->setPath(App::frontend()->template()->getPath(), $default_template . $tplset);
        }

        App::url()::serveDocument(My::id() . '-' . $template);
    }
}
